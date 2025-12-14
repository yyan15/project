<?php
include __DIR__ . '/../header.php';
include __DIR__ . '/../config.php';

$id = (int) $_GET['id'];

$query = "
    SELECT r.reminder_id, r.reminder_time, t.task_id, t.title
    FROM reminders r
    JOIN tasks t ON r.task_id = t.task_id
    WHERE r.reminder_id = $id
";
$data = $conn->query($query)->fetch_assoc();

if (!$data) {
    echo "Reminder tidak ditemukan";
    exit;
}

$tasks = $conn->query("SELECT task_id, title FROM tasks");
?>

<div class="form-container">

    <h2>Edit Reminder</h2>

    <div class="form-box">
        <form method="post" action="update_reminder.php">

            <input type="hidden" name="id" value="<?= $data['reminder_id'] ?>">

            <div class="form-group">
                <label>Task</label>
                <select name="task_id" required>
                    <?php while ($t = $tasks->fetch_assoc()): ?>
                        <option value="<?= $t['task_id'] ?>"
                            <?= $t['task_id'] == $data['task_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($t['title']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Reminder Time</label>
                <input
                    type="datetime-local"
                    name="reminder_time"
                    value="<?= date('Y-m-d\TH:i', strtotime($data['reminder_time'])) ?>"
                    required
                >
            </div>

            <button type="submit">Update Reminder</button>

        </form>
    </div>

    <a href="list_reminder.php" class="back-link">← Back</a>

</div>
