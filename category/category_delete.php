<?php
include __DIR__ . '/../header.php';
include __DIR__ . '/../config.php';

$user_id = $_SESSION['user_id'];
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    $stmt = $conn->prepare("
        DELETE FROM categories
        WHERE category_id = ?
        AND user_id = ?
    ");
    $stmt->bind_param("ii", $id, $user_id);
    $stmt->execute();
    $stmt->close();
}

header("Location: category_list.php");
exit;
