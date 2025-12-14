<?php
include __DIR__ . '/../config.php';

$user_id = $_SESSION['user_id'];
$name = trim($_POST['category_name']);

$stmt = $conn->prepare("
    INSERT INTO categories (user_id, category_name)
    VALUES (?, ?)
");

$stmt->bind_param("is", $user_id, $name);
$stmt->execute();
$stmt->close();

header('Location: category_list.php');
exit;
