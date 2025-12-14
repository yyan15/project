<?php
session_start();
include __DIR__ . '/../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $note_id = (int)$_POST['note_id'];
    $task_id = (int)$_POST['task_id'];
    $content = $_POST['content'];

    // Update data
    $stmt = $conn->prepare("UPDATE notes SET task_id = ?, content = ? WHERE note_id = ?");
    $stmt->bind_param("isi", $task_id, $content, $note_id);

    if ($stmt->execute()) {
        header("Location: list_note.php");
        exit;
    } else {
        echo "Gagal update: " . $conn->error;
    }
}
?>