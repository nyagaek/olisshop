<?php
/**
 * place_order.php
 * 1. Receives cart items from shop.php
 * 2. Creates an order + order_items in the database
 * 3. Triggers STK Push to customer's phone
 * 4. Returns checkoutRequestId to shop.php for polling
 */

header('Content-Type: application/json');
require_once 'db.php';

// ─── CONFIG (same as your stk_push.php) ──────────────────────
define('CONSUMER_KEY',    'j1z0fbnKTvIhvoabJjWXmUeJy9jdkOhZfSD6vfMsJ33Jxfa4');
define('CONSUMER_SECRET', 'RolCZQwi7O8Tc2mQRcFcttos2TBNKJ81Tz4zLS314WMohY4DptFB7ACXF7ldwTJz');
define('SHORTCODE',       '174379');
define('PASSKEY',         'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919');
define('CALLBACK_URL',    'https://robust-lanky-scallion.ngrok-free.dev');
define('ENVIRONMENT',     'sandbox');

define('BASE_URL', ENVIRONMENT === 'live'
    ? 'https://api.safaricom.co.ke'
    : 'https://sandbox.safaricom.co.ke');

// ─── READ CART DATA FROM shop.php ────────────────────────────
$input = json_decode(file_get_contents('php://input'), true);

$customerName  = trim($input['name']  ?? '');
$customerPhone = formatPhone($input['phone'] ?? '');
$total         = (float) ($input['total'] ?? 0);
$items         = $input['items'] ?? [];

// ─── VALIDATE ─────────────────────────────────────────────────
if (!$customerPhone) {
    echo json_encode(['success' => false, 'message' => 'Invalid phone number.']);
    exit;
}
if (empty($items)) {
    echo json_encode(['success' => false, 'message' => 'Cart is empty.']);
    exit;
}
if ($total <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid total amount.']);
    exit;
}

// ─── STEP 1: Get Access Token from Safaricom ─────────────────
try {
    $accessToken = getAccessToken();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Auth failed: ' . $e->getMessage()]);
    exit;
}

// ─── STEP 2: Generate Order Number ───────────────────────────
// Format: ORD-20260510-0001 (date + random number)
$orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

// ─── STEP 3: Save Order to Database ──────────────────────────
$db = getDB();

try {
    $db->beginTransaction(); // start — either all saves succeed or none do

    // Insert the main order row
    $stmt = $db->prepare("
        INSERT INTO orders
            (order_number, customer_phone, customer_name, total_amount, status)
        VALUES
            (:order_number, :phone, :name, :total, 'pending')
    ");
    $stmt->execute([
        ':order_number' => $orderNumber,
        ':phone'        => $customerPhone,
        ':name'         => $customerName,
        ':total'        => $total,
    ]);
    $orderId = $db->lastInsertId(); // get the auto-generated order id

    // Insert each cart item as a separate order_items row
    $itemStmt = $db->prepare("
        INSERT INTO order_items
            (order_id, product_id, name, size, quantity, price)
        VALUES
            (:order_id, :product_id, :name, :size, :quantity, :price)
    ");
    foreach ($items as $item) {
        $itemStmt->execute([
            ':order_id'   => $orderId,
            ':product_id' => $item['id'],
            ':name'       => $item['name'],
            ':size'       => $item['size'] ?? null,
            ':quantity'   => (int) $item['qty'],
            ':price'      => (float) $item['price'],
        ]);
    }

    $db->commit(); // all saved successfully

} catch (Exception $e) {
    $db->rollBack(); // something failed — undo everything
    error_log("Order save failed: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Could not save order. Please try again.']);
    exit;
}

// ─── STEP 4: Generate STK Push Password ──────────────────────
$timestamp = date('YmdHis');
$password  = base64_encode(SHORTCODE . PASSKEY . $timestamp);

// ─── STEP 5: Send STK Push to Customer's Phone ───────────────
$payload = [
    'BusinessShortCode' => SHORTCODE,
    'Password'          => $password,
    'Timestamp'         => $timestamp,
    'TransactionType'   => 'CustomerPayBillOnline',
    'Amount'            => (int) $total,
    'PartyA'            => $customerPhone,
    'PartyB'            => SHORTCODE,
    'PhoneNumber'       => $customerPhone,
    'CallBackURL'       => CALLBACK_URL,
    'AccountReference'  => $orderNumber,   // customer sees this on their phone
    'TransactionDesc'   => 'OlisShop Payment',
];

$curl = curl_init(BASE_URL . '/mpesa/stkpush/v1/processrequest');
curl_setopt_array($curl, [
    CURLOPT_HTTPHEADER     => [
        'Authorization: Bearer ' . $accessToken,
        'Content-Type: application/json',
    ],
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => json_encode($payload),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_SSL_VERIFYPEER => false,
]);

$response = json_decode(curl_exec($curl), true);
$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);

// ─── STEP 6: Save Checkout ID and link to order ──────────────
if ($httpCode === 200 && isset($response['ResponseCode']) && $response['ResponseCode'] === '0') {

    $checkoutId = $response['CheckoutRequestID'];

    // Save to transactions table
    $db->prepare("
        INSERT INTO transactions
            (checkout_request_id, phone, amount, account_reference, description, status)
        VALUES
            (:checkout_id, :phone, :amount, :ref, :desc, 'pending')
    ")->execute([
        ':checkout_id' => $checkoutId,
        ':phone'       => $customerPhone,
        ':amount'      => $total,
        ':ref'         => $orderNumber,
        ':desc'        => 'OlisShop Payment',
    ]);

    // Link the transaction to the order
    $db->prepare("
        UPDATE orders SET checkout_request_id = :checkout_id WHERE id = :order_id
    ")->execute([
        ':checkout_id' => $checkoutId,
        ':order_id'    => $orderId,
    ]);

    echo json_encode([
        'success'           => true,
        'checkoutRequestId' => $checkoutId,
        'orderNumber'       => $orderNumber,
        'message'           => 'STK Push sent. Ask customer to enter PIN.',
    ]);

} else {
    // STK Push failed — mark order as cancelled
    $db->prepare("UPDATE orders SET status = 'cancelled' WHERE id = :id")
       ->execute([':id' => $orderId]);

    $msg = $response['errorMessage'] ?? $response['ResponseDescription'] ?? 'Safaricom error. Try again.';
    echo json_encode(['success' => false, 'message' => $msg]);
}


// ─── FUNCTIONS ────────────────────────────────────────────────

function getAccessToken() {
    $credentials = base64_encode(CONSUMER_KEY . ':' . CONSUMER_SECRET);
    $curl = curl_init(BASE_URL . '/oauth/v1/generate?grant_type=client_credentials');
    curl_setopt_array($curl, [
        CURLOPT_HTTPHEADER     => ['Authorization: Basic ' . $credentials],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
    ]);
    $response = json_decode(curl_exec($curl), true);
    $httpCode  = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    if ($httpCode !== 200 || !isset($response['access_token'])) {
        throw new Exception('Could not get access token. Check your Consumer Key and Secret.');
    }
    return $response['access_token'];
}

function formatPhone($phone) {
    $phone = preg_replace('/\D/', '', $phone);
    if (substr($phone, 0, 1) === '0') {
        $phone = '254' . substr($phone, 1);
    }
    return $phone;
}
