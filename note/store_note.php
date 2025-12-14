<?php
session_start();
include __DIR__ . '/../config.php';

$user_id = $_SESSION['user_id']; // Ambil ID user dari session

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task_id = $_POST['task_id'];
    $content = $_POST['content'];

    if (empty($task_id) || empty($content)) {
        die("Data tidak lengkap.");
    }

    // UPDATE: Tambahkan user_id ke dalam query INSERT
    $stmt = $conn->prepare("INSERT INTO notes (task_id, user_id, content, created_at) VALUES (?, ?, ?, NOW())");
    
    // UPDATE: Bind param menjadi "iis" (integer, integer, string)
    $stmt->bind_param("iis", $task_id, $user_id, $content);

    if ($stmt->execute()) {
        header("Location: list_note.php");
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
}
?>