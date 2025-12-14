<?php
include __DIR__ . '/../header.php';
include __DIR__ . '/../config.php';

$user_id = $_SESSION['user_id'];

$tasks = $conn->prepare("
    SELECT task_id, title
    FROM tasks
    WHERE user_id = ?
");
$tasks->bind_param("i", $user_id);
$tasks->execute();
$tasks = $tasks->get_result();



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task_id = $_POST['task_id'];
    $time = $_POST['reminder_time'];

    $stmt = $conn->prepare("
    INSERT INTO reminders (task_id, user_id, reminder_time)
    VALUES (?, ?, ?)
");

$stmt->bind_param("iis", $task_id, $user_id, $time);

    $stmt->execute();

    header("Location: list_reminder.php");
    exit();
}
?>

<div class="form-container">

    <h2>Add Reminder</h2>

    <div class="form-box">
        <form method="post">

            <div class="form-group">
                <label>Task</label>
                <select name="task_id" required>
                    <?php while ($t = $tasks->fetch_assoc()): ?>
                        <option value="<?= $t['task_id'] ?>">
                            <?= htmlspecialchars($t['title']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Reminder Time</label>
                <input type="datetime-local" name="reminder_time" required>
            </div>

            <button type="submit">Save Reminder</button>

        </form>
    </div>

    <a href="list_reminder.php" class="back-link">← Back</a>

</div>
