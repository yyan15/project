<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'pwb';
$conn = mysqli_connect($host, $user, $pass, $dbname);


if (!$conn) {
    die('Could not connect: ' . mysqli_connect_error());
}
date_default_timezone_set("Asia/Jakarta");

?>