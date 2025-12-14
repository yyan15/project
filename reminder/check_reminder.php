<?php
include __DIR__ . '/../config.php';

$now = date('Y-m-d H:i:s');

$notif = $conn->query("
    SELECT r.reminder_id, t.title
    FROM reminders r
    JOIN tasks t ON r.task_id = t.task_id
    WHERE r.reminder_time <= NOW()
      AND r.is_sent = 0
");

$data = [];

while ($row = $notif->fetch_assoc()) {
    $data[] = $row;
}

if (count($data) > 0) {
    $conn->query("
        UPDATE reminders
        SET is_sent = 1
        WHERE reminder_time <= NOW()
          AND is_sent = 0
    ");
}

echo json_encode($data);
