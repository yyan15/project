<?php
require __DIR__ . '/../config.php';

$id = $_GET['id'];

$stmt = $conn->prepare("DELETE FROM reminders WHERE reminder_id=?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: list_reminder.php");
exit();

