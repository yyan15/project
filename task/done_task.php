<?php
include __DIR__ . '/../config.php';

$id = $_GET['id'];

$stmt = $conn->prepare("UPDATE tasks SET is_done = 1 WHERE task_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: list_task.php");
exit();
?>
