<?php
require __DIR__ . '/../config.php';

if (!isset($_GET['id'])) {
    die("Task ID tidak ditemukan.");
}

$id = $_GET['id'];

// Hapus data dari tabel tasks
$stmt = $conn->prepare("DELETE FROM tasks WHERE task_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

// Kembali ke halaman list
header("Location: list_task.php");
exit();
?>
