<?php
include __DIR__ . '/../config.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    $stmt = $conn->prepare("DELETE FROM notes WHERE note_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: list_note.php");
exit;
?>