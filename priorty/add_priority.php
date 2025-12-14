<?php
include __DIR__ . '/../header.php';   // sudah ada session_start()
include __DIR__ . '/../config.php';

$user_id = $_SESSION['user_id'] ?? 0;
if ($user_id <= 0) die("User belum login");

// Ambil task milik user
$taskQuery = $conn->prepare("SELECT task_id, title FROM tasks WHERE user_id = ? ORDER BY title ASC");
$taskQuery->bind_param("i", $user_id);
$taskQuery->execute();
$taskResult = $taskQuery->get_result();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task_id = (int) ($_POST['task_id'] ?? 0);
    $level   = trim($_POST['level'] ?? '');

    if ($task_id <= 0 || $level === '') {
        echo "<p style='color:red; text-align:center;'>Pilih task dan level terlebih dahulu.</p>";
    } else {
        // Ambil title task milik user
        $getTask = $conn->prepare("SELECT title FROM tasks WHERE task_id = ? AND user_id = ?");
        $getTask->bind_param("ii", $task_id, $user_id);
        $getTask->execute();
        $taskRes = $getTask->get_result();

        if ($taskRes && $taskRow = $taskRes->fetch_assoc()) {

            $priority_name = $taskRow['title'];

            // Cek duplikasi priority
            $check = $conn->prepare(
                "SELECT priority_id FROM priority WHERE user_id = ? AND priority_name = ?"
            );
            $check->bind_param("is", $user_id, $priority_name);
            $check->execute();
            $checkRes = $check->get_result();

            if ($checkRes->num_rows > 0) {
                echo "<p style='color:red; text-align:center;'>Priority untuk task ini sudah ada.</p>";
            } else {

                // INSERT priority baru
                $ins = $conn->prepare(
                    "INSERT INTO priority (user_id, priority_name, level, created_at)
                     VALUES (?, ?, ?, NOW())"
                );
                $ins->bind_param("iss", $user_id, $priority_name, $level);

                if ($ins->execute()) {
                    $priority_id = $ins->insert_id;

                    // update task -> isi priority_id
                    $upd = $conn->prepare("UPDATE tasks SET priority_id=? WHERE task_id=?");
                    $upd->bind_param("ii", $priority_id, $task_id);
                    $upd->execute();

                    header("Location: list_priority.php");
                    exit;
                } else {
                    echo "<p style='color:red; text-align:center;'>Gagal menyimpan priority.</p>";
                }
            }
        } else {
            echo "<p style='color:red; text-align:center;'>Task tidak ditemukan.</p>";
        }
    }
}
?>


<div class="form-container">
    <h2>Add Priority</h2>

    <div class="form-box">
        <form method="POST">
            <div class="form-group">
                <label>Pilih Task</label>
                <select name="task_id" required>
                    <option value="">-- Pilih Task --</option>
                    <?php while ($task = $taskResult->fetch_assoc()): ?>
                        <option value="<?= $task['task_id'] ?>"><?= htmlspecialchars($task['title']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Level</label>
                <select name="level" required>
                    <option value="">Pilih Level</option>
                    <option value="Low">🟢 Low</option>
                    <option value="Medium">🟡 Medium</option>
                    <option value="High">🔴 High</option>
                </select>
            </div>

            <button type="submit">Save Priority</button>
        </form>
    </div>

    <a class="back-link" href="list_priority.php">← Back</a>
</div>

