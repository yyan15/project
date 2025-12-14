<?php
include __DIR__ . '/../header.php';
include __DIR__ . '/../config.php';

$user_id = $_SESSION['user_id'];
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// 1. Ambil data note yang akan diedit
// Kita join ke tasks untuk memastikan note ini memang akses user tersebut
$query = "
    SELECT n.* FROM notes n
    JOIN tasks t ON n.task_id = t.task_id
    WHERE n.note_id = ? AND t.user_id = ?
";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $id, $user_id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

if (!$data) {
    die("Catatan tidak ditemukan atau Anda tidak memiliki akses.");
}

// 2. Ambil daftar task untuk dropdown
$taskStmt = $conn->prepare("SELECT task_id, title FROM tasks WHERE user_id = ? ORDER BY title ASC");
$taskStmt->bind_param("i", $user_id);
$taskStmt->execute();
$tasks = $taskStmt->get_result();
?>

<div class="form-container">
    <h2>Edit Note</h2>

    <div class="form-box">
        <form method="post" action="update_note.php">
            <input type="hidden" name="note_id" value="<?= $data['note_id'] ?>">

            <div class="form-group">
                <label>Select Task</label>
                <select name="task_id" required>
                    <option value="">-- Pilih Task --</option>
                    <?php while ($t = $tasks->fetch_assoc()): ?>
                        <option value="<?= $t['task_id'] ?>" 
                            <?= $t['task_id'] == $data['task_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($t['title']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Note Content</label>
                <textarea name="content" rows="5" required style="width:100%; padding:10px; border-radius:6px; border:1px solid #ccc;"><?= htmlspecialchars($data['content']) ?></textarea>
            </div>

            <button type="submit">Update Note</button>
        </form>
    </div>

    <a href="list_note.php" class="back-link">← Back</a>
</div>