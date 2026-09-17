<?php
namespace App\Controllers;

use App\Models\CodeModel;
use App\Models\UserModel;
use CodeIgniter\Config\Services;

// Include database connection
include('conn.php');

$url = "";
$CompName = "";

// ✅ Avoid direct object access outside method
$session = session();
$userid = $session->get('userid');
$model = new UserModel();
$user = $model->getUser($userid);
$username = getName($user); // Assuming this function returns a string

// ✅ Check if username is not null
$usermail = null;
if (!empty($username)) {
    $sql = "SELECT `email` FROM `users` WHERE `username` = '" . mysqli_real_escape_string($conn, $username) . "'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $usermail = $row['email'] ?? null;
}

date_default_timezone_set('Asia/Calcutta');
$timestamp = date('d/m/Y h:i:sa');
$accesstime = date('h:i:sa');
$webpage = $_SERVER['REQUEST_URI'] ?? '';
$browser = $_SERVER['HTTP_USER_AGENT'] ?? '';
$url = $_SERVER['SERVER_NAME'] ?? '';
$server = $_SERVER['HTTP_HOST'] ?? '';

function getUserIP()
{
    $clientIp  = $_SERVER['HTTP_CLIENT_IP'] ?? '';
    $forwardIp = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? '';
    $remoteIp  = $_SERVER['REMOTE_ADDR'] ?? '';

    if (filter_var($clientIp, FILTER_VALIDATE_IP)) {
        return $clientIp;
    } elseif (filter_var($forwardIp, FILTER_VALIDATE_IP)) {
        return $forwardIp;
    }
    return $remoteIp;
}

$user_ip = getUserIP();

// ✅ Only send email if both username and email are valid
if (!empty($username) && !empty($usermail)) {
    $email = \Config\Services::email();
    $email->setFrom('noreply@example.com', 'Login Notifier'); // ← Replace with your email and name
    $email->setTo($usermail);

    $email->setSubject("[$server]✔ Logged in as $username at $timestamp");

    $email->setMessage("
        <html>
        <body>
            <h3>Login Notification</h3>
            <p><strong>Username:</strong> $username</p>
            <p><strong>Time:</strong> $timestamp</p>
            <p><strong>IP Address:</strong> $user_ip</p>
            <p><strong>Browser:</strong> $browser</p>
            <p><strong>Visited Page:</strong> $webpage</p>
        </body>
        </html>
    ");

    $email->setMailType('html'); // For HTML formatting
    $email->send();
}
?>