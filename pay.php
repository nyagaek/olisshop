<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>M-Pesa STK Push Demo</title>
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Segoe UI', sans-serif; background: #f0f2f5; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
    .container { width: 100%; max-width: 440px; padding: 16px; }

    .card { background: #fff; border-radius: 16px; padding: 32px; box-shadow: 0 2px 16px rgba(0,0,0,0.08); }

    .logo { text-align: center; margin-bottom: 28px; }
    .logo-badge { display: inline-flex; align-items: center; gap: 10px; background: #006400; color: #fff; padding: 10px 20px; border-radius: 50px; font-weight: 700; font-size: 18px; }
    .logo-badge span { font-size: 24px; }

    h2 { font-size: 18px; font-weight: 600; color: #1a1a1a; margin-bottom: 4px; text-align: center; }
    .subtitle { font-size: 13px; color: #666; text-align: center; margin-bottom: 24px; }

    .order-box { background: #f8f9fa; border-radius: 10px; padding: 14px 16px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; }
    .order-label { font-size: 13px; color: #666; }
    .order-amount { font-size: 22px; font-weight: 700; color: #006400; }

    label { display: block; font-size: 13px; font-weight: 500; color: #444; margin-bottom: 6px; }
    input { width: 100%; padding: 12px 14px; border: 1.5px solid #e0e0e0; border-radius: 10px; font-size: 15px; outline: none; transition: border 0.2s; margin-bottom: 16px; }
    input:focus { border-color: #006400; }

    .btn { width: 100%; padding: 14px; background: #006400; color: #fff; border: none; border-radius: 10px; font-size: 16px; font-weight: 600; cursor: pointer; transition: background 0.2s; }
    .btn:hover { background: #005000; }
    .btn:disabled { background: #aaa; cursor: not-allowed; }

    .divider { border: none; border-top: 1px solid #f0f0f0; margin: 20px 0; }

    .test-numbers { background: #fffbe6; border: 1px solid #ffe58f; border-radius: 10px; padding: 14px 16px; margin-bottom: 20px; }
    .test-numbers h4 { font-size: 12px; font-weight: 600; color: #856404; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.05em; }
    .test-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px; }
    .test-row:last-child { margin-bottom: 0; }
    .test-row span { font-size: 13px; color: #555; }
    .test-row code { font-size: 13px; background: #fff3cd; padding: 2px 8px; border-radius: 4px; font-family: monospace; cursor: pointer; border: 1px solid #ffe58f; }
    .test-row code:hover { background: #ffeaa7; }

    #status { display: none; margin-top: 16px; border-radius: 10px; padding: 14px 16px; font-size: 14px; line-height: 1.5; }
    .status-loading { background: #e8f4fd; border: 1px solid #90cdf4; color: #1a5276; }
    .status-success { background: #e6f9ee; border: 1px solid #6fcf97; color: #1a5e36; }
    .status-error   { background: #fdecea; border: 1px solid #f5a3a3; color: #7b1a1a; }
    .status-pin     { background: #fff8e1; border: 1px solid #ffd54f; color: #5d4037; }

    .spinner { display: inline-block; width: 14px; height: 14px; border: 2px solid currentColor; border-top-color: transparent; border-radius: 50%; animation: spin 0.7s linear infinite; vertical-align: middle; margin-right: 6px; }
    @keyframes spin { to { transform: rotate(360deg); } }

    .footer { text-align: center; font-size: 12px; color: #aaa; margin-top: 16px; }
  </style>
</head>
<body>
<div class="container">
  <div class="card">

    <div class="logo">
      <div class="logo-badge"><span>📱</span> Lipa na M-Pesa</div>
    </div>

    <h2>Complete Your Payment</h2>
    <p class="subtitle">Enter your M-Pesa number to receive the payment prompt</p>

    
<!--
  <div class="order-box">
      <div>
        <div class="order-label">Order #001</div>
        <div class="order-label" style="margin-top:2px;font-size:12px;color:#999;">Web Design Service</div>
      </div>
      <div class="order-amount">KES 1</div>
    </div>
-->
    <label for="phone">M-Pesa Phone Number</label>
    <input type="tel" id="phone" placeholder="e.g. 0712345678" maxlength="13">

    <button class="btn" id="payBtn" onclick="initiatePayment()">Pay KES 1 via M-Pesa</button>

    <div id="status"></div>

    <hr class="divider">
    <p class="footer">Powered by Safaricom Daraja API &nbsp;·&nbsp;</p>
  </div>
</div>

<script>
function fillPhone(number) {
  document.getElementById('phone').value = number;
}

function setStatus(type, html) {
  const el = document.getElementById('status');
  el.style.display = 'block';
  el.className = 'status-' + type;
  el.innerHTML = html;
}

async function initiatePayment() {
  const phone = document.getElementById('phone').value.trim();

  if (!phone || phone.length < 9) {
    setStatus('error', '⚠️ Please enter a valid M-Pesa phone number.');
    return;
  }

  const btn = document.getElementById('payBtn');
  btn.disabled = true;
  btn.textContent = 'Sending request...';

  setStatus('loading', '<span class="spinner"></span> Contacting Safaricom servers...');

  try {
    const res = await fetch('stk_push.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ phone, amount: 1, ref: 'Order001', desc: 'Web Design Service' })
    });

    const data = await res.json();

    if (data.success) {
      setStatus('pin',
        `<strong>📲 Check your phone!</strong><br>` +
        `A payment prompt has been sent to <strong>${phone}</strong>.<br><br>` +
        `Enter your M-Pesa PIN to complete the payment.<br>` +
        `<small style="color:#888;margin-top:6px;display:block">Checkout ID: ${data.checkoutRequestId}</small>`
      );
      btn.textContent = 'Waiting for PIN...';

      // Poll for payment result
      pollPaymentStatus(data.checkoutRequestId, phone, btn);

    } else {
      setStatus('error', `❌ <strong>Failed:</strong> ${data.message}`);
      btn.disabled = false;
      btn.textContent = 'Pay KES 1 via M-Pesa';
    }

  } catch (err) {
    setStatus('error', '❌ Network error. Make sure your server is running.');
    btn.disabled = false;
    btn.textContent = 'Pay KES 1 via M-Pesa';
  }
}

function pollPaymentStatus(checkoutId, phone, btn) {
  let attempts = 0;
  const max = 10; // poll for ~30 seconds

  const interval = setInterval(async () => {
    attempts++;
    if (attempts > max) {
      clearInterval(interval);
      setStatus('error', '⏱️ Timed out waiting for payment. Please try again.');
      btn.disabled = false;
      btn.textContent = 'Pay KES 1 via M-Pesa';
      return;
    }

    try {
      const res = await fetch(`check_status.php?id=${encodeURIComponent(checkoutId)}`);
      const data = await res.json();

      if (data.status === 'success') {
        clearInterval(interval);
        setStatus('success',
          `✅ <strong>Payment Received!</strong><br>` +
          `Amount: <strong>KES ${data.amount}</strong><br>` +
          `Receipt: <strong>${data.receipt}</strong><br>` +
          `Phone: ${data.phone}`
        );
        btn.textContent = 'Payment Complete ✓';

      } else if (data.status === 'failed') {
        clearInterval(interval);
        setStatus('error', `❌ <strong>Payment failed:</strong> ${data.message}`);
        btn.disabled = false;
        btn.textContent = 'Try Again';
      }
      // if status is 'pending', keep polling
    } catch (e) { /* keep polling */ }

  }, 3000); // check every 3 seconds
}
</script>
</body>
</html>
