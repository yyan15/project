<?php
include __DIR__ . '/../config.php';

$user_id = $_SESSION['user_id'];
$task_id = (int) $_GET['task_id'];

$stmt = $conn->prepare("
    SELECT content, created_at
    FROM notes
    WHERE task_id = ? AND user_id = ?
    ORDER BY created_at DESC
");
$stmt->bind_param("ii", $task_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<p>Tidak ada note.</p>";
    exit;
}

while ($row = $result->fetch_assoc()) {
    echo "
        <div class='note-item'>
            <div class='note-text'>
                " . nl2br(htmlspecialchars($row['content'])) . "
            </div>
            <small class='note-time'>{$row['created_at']}</small>
        </div>
        <hr>
    ";
}
