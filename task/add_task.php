<?php
include __DIR__ . '/../header.php';
include __DIR__ . '/../config.php';

$user_id = $_SESSION['user_id'];

/* =========================
   AMBIL CATEGORY (UNTUK FORM)
   ========================= */
$catStmt = $conn->prepare("
    SELECT category_id, category_name
    FROM categories
    WHERE user_id = ?
");
$catStmt->bind_param("i", $user_id);
$catStmt->execute();
$categories = $catStmt->get_result();

/* =========================
   SIMPAN TASK
   ========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title       = $_POST['title'];
    $category_id = $_POST['category_id'];
    $deadline    = $_POST['deadline'];
    $note        = $_POST['note'] ?? '';

    $stmt = $conn->prepare("
        INSERT INTO tasks (user_id, category_id, title, due_date, description)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->bind_param(
        "iisss",
        $user_id,
        $category_id,
        $title,
        $deadline,
        $note
    );
    $stmt->execute();

    header("Location: list_task.php");
    exit;
}
?>



<div class="form-container">

    <h2>Add Task</h2>

    <div class="form-box">
        <form action="" method="POST">

            <div class="form-group">
                <label>Task Name</label>
                <input type="text" name="title" required>
            </div>

            <div class="form-group">
                <label>Category</label>
                <select name="category_id" required>
                    <option value="">-- Pilih Category --</option>
                    <?php while ($cat = $categories->fetch_assoc()): ?>
                        <option value="<?= $cat['category_id'] ?>">
                            <?= htmlspecialchars($cat['category_name']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Deadline</label>
                <input type="date" name="deadline" required>
            </div>

            <button type="submit">Save Task</button>
        </form>
    </div>

    <a href="list_task.php" class="back-link">← Back</a>

</div>

