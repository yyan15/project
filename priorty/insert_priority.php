<?php
include __DIR__ . "/../config.php";
session_start();
$user_id = $_SESSION['user_id'] ?? 0;
if ($user_id <= 0) die("User belum login");

$ins = $conn->prepare("INSERT INTO priority (user_id, priority_name, level, created_at) VALUES (?, ?, ?, NOW())");
$ins->bind_param("iss", $user_id, $name, $level);
$ok = $ins->execute();

if (!$ok) {
    die("Gagal insert priority: " . $conn->error);
}

header("Location: list_priority.php");
exit();

