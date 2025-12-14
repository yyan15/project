<?php
include __DIR__ . '/../config.php';

if(isset($_POST['task_id'], $_POST['status'])) {
    $task_id = (int)$_POST['task_id'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE tasks SET status=? WHERE task_id=?");
    $stmt->bind_param("si", $status, $task_id);
    $stmt->execute();
}

header("Location: list_task.php");
exit;
