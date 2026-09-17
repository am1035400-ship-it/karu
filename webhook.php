<?php
/**
 * Direct FamGateway Webhook Handler (Standalone / Fallback)
 */
header('Content-Type: text/plain');
require_once __DIR__ . '/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    die("FamGateway Webhook Endpoint is Active & Ready.");
}

/**
 * =========================================================================
 * 🔑 FAMGATEWAY API KEY
 * Replace with your API key from https://famgateway.in/api-keys.php
 * =========================================================================
 */
$apiKey = getenv('FAMGATEWAY_API_KEY') ?: "YOUR_FAMGATEWAY_API_KEY_HERE";

$rawPost = file_get_contents('php://input');
$signature = $_SERVER['HTTP_X_FAMGATEWAY_SIGNATURE'] ?? '';

if (empty($rawPost)) {
    http_response_code(400);
    die("Empty payload");
}

$expectedSig = hash_hmac('sha256', $rawPost, $apiKey);
$isValid = (!empty($signature) && hash_equals($expectedSig, $signature));

$data = json_decode($rawPost, true);

// If signature check failed, try direct verification with FamGateway API
if (!$isValid && $data && isset($data['order_id'])) {
    $verifyUrl = "https://famgateway.in/api/verify-order.php?" . http_build_query([
        'api_key' => $apiKey,
        'order_id' => $data['order_id']
    ]);
    $ch = curl_init($verifyUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $res = curl_exec($ch);
    curl_close($ch);

    $verifyData = json_decode($res, true);
    if ($verifyData && isset($verifyData['data']) && in_array(strtolower($verifyData['data']['status'] ?? ''), ['success', 'paid'])) {
        $isValid = true;
        $data = $verifyData['data'];
        $data['event'] = 'payment.success';
    }
}

if ($isValid && $data && ($data['event'] ?? '') === 'payment.success') {
    $orderId = mysqli_real_escape_string($conn, $data['order_id']);
    $amount = (float)($data['amount'] ?? 0);
    $utr = mysqli_real_escape_string($conn, $data['utr'] ?? ($data['transaction_id'] ?? ''));

    // Check payment record
    $chk = mysqli_query($conn, "SELECT * FROM `payments` WHERE `order_id` = '$orderId'");
    if ($chk && mysqli_num_rows($chk) > 0) {
        $paymentRow = mysqli_fetch_assoc($chk);
        if ($paymentRow['status'] !== 'PAID') {
            $userId = (int)$paymentRow['user_id'];
            $now = date('Y-m-d H:i:s');

            // Update payment
            mysqli_query($conn, "UPDATE `payments` SET `status` = 'PAID', `utr` = '$utr', `updated_at` = '$now' WHERE `order_id` = '$orderId'");

            // Update user balance
            mysqli_query($conn, "UPDATE `users` SET `saldo` = `saldo` + $amount WHERE `id_users` = $userId");

            // Insert into history
            $info = "Recharged ₹" . number_format($amount, 2) . " via UPI (Order: $orderId, UTR: $utr)";
            $infoEsc = mysqli_real_escape_string($conn, $info);
            $userDo = mysqli_real_escape_string($conn, $paymentRow['username']);
            mysqli_query($conn, "INSERT INTO `history` (`keys_id`, `user_do`, `info`, `created_at`, `updated_at`) VALUES ('RECHARGE', '$userDo', '$infoEsc', '$now', '$now')");
        }
    }

    http_response_code(200);
    echo "Webhook processed successfully.";
    exit;
}

http_response_code(400);
echo "Invalid signature or payload.";
?>
