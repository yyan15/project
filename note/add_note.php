<?php
include __DIR__ . '/../header.php';
include __DIR__ . '/../config.php';

$user_id = $_SESSION['user_id'];

// Ambil daftar task milik user untuk dropdown
$stmt = $conn->prepare("SELECT task_id, title FROM tasks WHERE user_id = ? ORDER BY title ASC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$tasks = $stmt->get_result();
?>

<div class="form-container">
    <h2>Add Note</h2>

    <div class="form-box">
        <form method="post" action="store_note.php">
            
            <div class="form-group">
                <label>Select Task</label>
                <select name="task_id" required>
                    <option value="">-- Pilih Task --</option>
                    <?php while ($t = $tasks->fetch_assoc()): ?>
                        <option value="<?= $t['task_id'] ?>">
                            <?= htmlspecialchars($t['title']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Note Content</label>
                <textarea name="content" rows="5" required style="width:100%; padding:10px; border-radius:6px; border:1px solid #ccc;"></textarea>
            </div>

            <button type="submit">Save Note</button>
        </form>
    </div>

    <a href="list_note.php" class="back-link">← Back</a>
</div>