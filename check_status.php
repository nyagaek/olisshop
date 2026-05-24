<?php
/**
 * check_status.php
 * The frontend polls this every 3 seconds to check if the
 * payment callback has arrived from Safaricom.
 */

require_once 'db.php'; // ADD THIS LINE
header('Content-Type: application/json');

$checkoutId = $_GET['id'] ?? '';

if (!$checkoutId) {
    echo json_encode(['status' => 'pending']);
    exit;
}

// READ from database instead of transactions.json
$db   = getDB();
$stmt = $db->prepare("
    SELECT status, amount, receipt_number, phone, result_desc, transaction_date
    FROM transactions
    WHERE checkout_request_id = :checkout_id
    LIMIT 1
");
$stmt->execute([':checkout_id' => $checkoutId]);
$txn = $stmt->fetch();

if (!$txn) {
    echo json_encode(['status' => 'pending']);
    exit;
}

echo json_encode([
    'status'  => $txn['status'],
    'amount'  => $txn['amount'],
    'receipt' => $txn['receipt_number'],
    'phone'   => $txn['phone'],
    'message' => $txn['result_desc'],
    'date'    => $txn['transaction_date'],
]);