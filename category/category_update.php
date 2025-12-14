<?php
session_start();
include __DIR__ . '/../config.php';

$user_id = $_SESSION['user_id'];
$id      = (int) $_POST['id'];
$name    = trim($_POST['category_name']);

$stmt = $conn->prepare("
    UPDATE categories
    SET category_name = ?
    WHERE category_id = ?
    AND user_id = ?
");

$stmt->bind_param("sii", $name, $id, $user_id);
$stmt->execute();

header('Location: category_list.php');
exit;
