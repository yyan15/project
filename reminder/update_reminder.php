<?php
include __DIR__ . '/../config.php';

$id   = $_POST['id'];
$task = $_POST['task_id'];
$time = $_POST['reminder_time'];

$stmt = $conn->prepare("
    UPDATE reminders 
    SET task_id = ?, 
        reminder_time = ?, 
        is_sent = 0
    WHERE reminder_id = ?
");

$stmt->bind_param("isi", $task, $time, $id);
$stmt->execute();

header("Location: list_reminder.php");
exit();