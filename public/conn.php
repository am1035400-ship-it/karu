<?php

$servername = "localhost";
$username = "fsxoclla_kuro";
$password = "Kuro@2026#SecurePass!";
$dbname = "fsxoclla_kuro";

$conn = mysqli_connect($servername,$username,$password,$dbname);

if(!$conn) {
    die(" PROBLEM WITH CONNECTION : " . mysqli_connect_error());
}
  
?>