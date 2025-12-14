<?php
include __DIR__ . "/../config.php";
include __DIR__ . "/../header.php";

// pastikan session sudah ada dari header.php
$user_id = $_SESSION['user_id'] ?? 0;
if ($user_id <= 0) {
    die("User belum login");
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Ambil data priority + task_id yang terkait (jika ada) — pastikan hanya milik user ini
$sql = "
    SELECT p.*, t.task_id AS task_id
    FROM priority p
    LEFT JOIN tasks t ON t.priority_id = p.priority_id
    WHERE p.priority_id = ? AND p.user_id = ?
    LIMIT 1
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id, $user_id);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();
$stmt->close();

if (!$row) {
    echo "<p>Priority not found.</p>";
    include __DIR__ . "/../footer.php";
    exit;
}

// Ambil daftar task milik user untuk dropdown
$taskQuery = $conn->prepare("SELECT task_id, title FROM tasks WHERE user_id = ? ORDER BY title ASC");
$taskQuery->bind_param("i", $user_id);
$taskQuery->execute();
$taskResult = $taskQuery->get_result();
?>

<div class="form-container">
    <h2>Edit Priority</h2>

    <div class="form-box">
        <form action="update_priority.php" method="POST">

            <input type="hidden" name="id" value="<?= (int)$row['priority_id'] ?>">

            <!-- Pilih Task -->
            <div class="form-group">
                <label>Pilih Task</label>
                <select name="task_id" required>
                    <option value="">-- Pilih Task --</option>

                    <?php while ($task = $taskResult->fetch_assoc()): ?>
                        <option 
                            value="<?= $task['task_id'] ?>"
                            <?= ($task['task_id'] == ($row['task_id'] ?? 0)) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($task['title']) ?>
                        </option>
                    <?php endwhile; ?>

                </select>
            </div>

            <!-- Level -->
            <div class="form-group">
                <label>Level</label>
                <select name="level" required>
                    <option value="">-- Pilih Level --</option>
                    <option value="Low"    <?= (strtolower($row['level']) == 'low') ? 'selected' : '' ?>>🟢 Low</option>
                    <option value="Medium" <?= (strtolower($row['level']) == 'medium') ? 'selected' : '' ?>>🟠 Medium</option>
                    <option value="High"   <?= (strtolower($row['level']) == 'high') ? 'selected' : '' ?>>🔴 High</option>
                </select>
            </div>

            <button type="submit">Update Priority</button>
        </form>
    </div>

    <a class="back-link" href="list_priority.php">← Back</a>
</div>
