# 🌸 Aryanispe Pink Kuro Panel with FamGateway Automated UPI

An advanced VIP Kuro Game Key Management Panel built with **CodeIgniter 4**, featuring an **Automated 24/7 UPI Wallet Recharge System** integrated with **FamGateway**.

---

## ✨ Features

- **🔑 Complete Key Management:** Generate, Edit, Reset, Extend, and Delete Game Keys.
- **⚡ Automated UPI Wallet Recharge:** Resellers and Users can top up their wallet 24/7 with zero manual intervention.
- **🛡️ FamGateway Integration:**
  - Official Hosted Checkout & QR code integration.
  - Supports Google Pay, PhonePe, Paytm, FamPay, BHIM UPI.
  - Real-time Webhook verification (HMAC SHA-256).
  - Live Bank UTR recording & status tracking.
  - Auto-Expiry timeout (5 mins) for unpaid pending orders.
  - Zero-Bypass security with double-fulfillment guard.
- **🎨 Pink Kuro Aesthetic UI:** Modern gradient cards, responsive layout, and dark-glow buttons.

---

## 📋 System Requirements

- **PHP Version:** PHP 8.1 or PHP 8.2 (Recommended: `ea-php81`)
- **Web Server:** Apache / LiteSpeed (cPanel, Plesk, VPS)
- **Database:** MySQL 5.7+ / MariaDB 10.3+ (InnoDB)
- **PHP Extensions:** `mysqli`, `curl`, `mbstring`, `intl`, `json`

---

## 🚀 Installation Guide

### Step 1: Upload Files
1. Upload the zip contents to your hosting directory (e.g. `/public_html`).
2. Extract all files into the root directory.

### Step 2: Create & Import Database
1. Go to your cPanel **MySQL Databases** and create a new database & database user.
2. Open **phpMyAdmin**, select your newly created database.
3. Click **Import** and upload the included `database.sql` file.

### Step 3: Configure Database & Domain
1. Open the `.env` file in the root directory and update:
   ```ini
   CI_ENVIRONMENT = production
   app.baseURL = 'https://yourdomain.com/'
   app.appTimezone = 'Asia/Kolkata'

   database.default.hostname = localhost
   database.default.database = your_database_name
   database.default.username = your_database_user
   database.default.password = your_database_password
   ```
2. Open `conn.php` in the root directory and update your database credentials:
   ```php
   $servername = "localhost";
   $username = "your_database_user";
   $password = "your_database_password";
   $dbname = "your_database_name";
   ```

---

## 🔑 How to Setup FamGateway API Key

You can configure your FamGateway API Key in either of the two ways:

### Method 1 (Recommended - via `.env`):
Open `.env` and paste your FamGateway API Key:
```ini
FAMGATEWAY_API_KEY = 'fam_your_api_key_here'
```

### Method 2 (Directly in Code Files):
1. **File 1:** Open `app/Libraries/FamGateway.php` and replace:
   ```php
   private $apiKey = "YOUR_FAMGATEWAY_API_KEY_HERE";
   ```
2. **File 2:** Open `webhook.php` and replace:
   ```php
   $apiKey = "YOUR_FAMGATEWAY_API_KEY_HERE";
   ```

---

## 🔗 Setup Webhook in FamGateway Dashboard

1. Log into your [FamGateway Merchant Dashboard](https://famgateway.in/dashboard.php).
2. Go to **Settings** &rarr; **Webhooks**.
3. Paste your Webhook URL:
   ```
   https://yourdomain.com/api/webhook/famgateway
   ```
   *(Fallback URL: `https://yourdomain.com/webhook.php`)*
4. Click **Save Changes**.

---

## 👤 Default Login Credentials

- **URL:** `https://yourdomain.com/login`
- **Username:** `admin`
- **Password:** `password`

*(Please change your password immediately after logging into the admin panel).*

---

## 💖 Credits & Support
- **Source Modded by:** Aryan Gupta (@aryanispe)
- **Telegram Support:** [@aryanispe](https://telegram.me/aryanispe)
- **Payment Gateway:** [FamGateway.in](https://famgateway.in/)
