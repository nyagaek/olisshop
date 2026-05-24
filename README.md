# M-Pesa STK Push Demo — Setup Guide

## Files in this folder
```
mpesa_demo/
├── index.php         → Checkout page (what the customer sees)
├── stk_push.php      → Sends the STK Push request to Safaricom
├── callback.php      → Receives the payment result from Safaricom
├── check_status.php  → Frontend polls this to know if payment arrived
└── transactions.json → Auto-created; stores payment results
```

---

## Step 1 — Get your Daraja sandbox credentials

1. Go to https://developer.safaricom.co.ke and sign up (free)
2. Click **My Apps** → **Add a New App**
3. Give it a name, check **Lipa na M-Pesa Sandbox**, click **Create App**
4. Click on your new app → copy the **Consumer Key** and **Consumer Secret**

---

## Step 2 — Paste your credentials into stk_push.php

Open `stk_push.php` and replace these two lines:

```php
define('CONSUMER_KEY',    'YOUR_CONSUMER_KEY');
define('CONSUMER_SECRET', 'YOUR_CONSUMER_SECRET');
```

Leave everything else as-is for sandbox testing.

---

## Step 3 — Install and start your local server

### Option A — XAMPP (recommended for beginners on Windows)
1. Download XAMPP from https://www.apachefriends.org
2. Copy the `mpesa_demo` folder into `C:\xampp\htdocs\`
3. Open XAMPP Control Panel → Start **Apache**
4. Visit http://localhost/mpesa_demo/

### Option B — WAMP (Windows)
1. Download from https://www.wampserver.com
2. Copy folder to `C:\wamp64\www\`
3. Start WAMP, visit http://localhost/mpesa_demo/

### Option C — Built-in PHP server (any OS)
```bash
cd mpesa_demo
php -S localhost:8000
```
Visit http://localhost:8000

---

## Step 4 — Expose your localhost with ngrok (for the callback)

Safaricom needs to send a result to your `callback.php`.
Since you are on localhost, you need ngrok to create a public URL.

1. Download ngrok from https://ngrok.com (free)
2. Run: `ngrok http 80` (or `ngrok http 8000` if using PHP built-in server)
3. Copy the HTTPS URL it gives you — looks like: `https://abc123.ngrok.io`
4. Open `stk_push.php` and update:

```php
define('CALLBACK_URL', 'https://abc123.ngrok.io/mpesa_demo/callback.php');
```

---

## Step 5 — Test!

Open the checkout page in your browser and use these test phone numbers:

| Phone Number | Result |
|---|---|
| 0708374149 | ✅ Payment succeeds |
| 0703999997 | ❌ Insufficient funds |
| 0703999998 | 🚫 User cancels |

Click the number on the page to auto-fill it, then click Pay.

---

## Debugging tips

- **"Auth failed"** → Your Consumer Key or Secret is wrong
- **Callback not arriving** → Your CALLBACK_URL is wrong or ngrok is not running
- **Check `callback_log.txt`** → It logs every callback Safaricom sends
- **Check `transactions.json`** → It shows all transaction statuses
- Make sure your ngrok URL uses **https://** not http://

---

## Going live (when ready)

1. Apply for a Safaricom Go Live account at the Daraja portal
2. Replace sandbox credentials with live credentials in `stk_push.php`
3. Change `define('ENVIRONMENT', 'sandbox')` to `'live'`
4. Update `CALLBACK_URL` to your real hosted domain
5. Set `CURLOPT_SSL_VERIFYPEER` to `true`
