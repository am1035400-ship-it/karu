<?php

$servername = getenv('DB_HOST') ?: "localhost";
$username = getenv('DB_USER') ?: "your_database_user";
$password = getenv('DB_PASS') ?: "your_database_password";
$dbname = getenv('DB_NAME') ?: "your_database_name";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Database Connection Error: " . mysqli_connect_error());
}
  
?>