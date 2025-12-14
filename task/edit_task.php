<?php 
include __DIR__ . '/../header.php';
include __DIR__ . '/../config.php';

$user_id = $_SESSION['user_id'];
$id = (int) $_GET['id'];

/* ambil data task */
$task = $conn->prepare("
    SELECT task_id, title, due_date, description, category_id
    FROM tasks
    WHERE task_id = ? AND user_id = ?
");
$task->bind_param("ii", $id, $user_id);
$task->execute();
$data = $task->get_result()->fetch_assoc();

if (!$data) {
    exit('Task tidak ditemukan');
}

/* ambil category user */
$cat = $conn->prepare("
    SELECT category_id, category_name
    FROM categories
    WHERE user_id = ?
");
$cat->bind_param("i", $user_id);
$cat->execute();
$categories = $cat->get_result();

/* update */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title       = $_POST['title'];
    $category_id = $_POST['category_id'];
    $deadline    = $_POST['deadline'];
    $note        = $_POST['note'];

    $stmt = $conn->prepare("
        UPDATE tasks 
        SET title=?, category_id=?, due_date=?, description=?
        WHERE task_id=? AND user_id=?
    ");

    $stmt->bind_param(
        "sissii",
        $title,
        $category_id,
        $deadline,
        $note,
        $id,
        $user_id
    );

    $stmt->execute();

    header("Location: list_task.php");
    exit();
}
?>


<div class="form-container">

    <h2>Edit Task</h2>

    <div class="form-box">
        <form method="POST">

            <div class="form-group">
                <label>Task Name</label>
                <input type="text" name="title"
                       value="<?= htmlspecialchars($data['title']) ?>" required>
            </div>

            <div class="form-group">
                <label>Category</label>
                <select name="category_id" required>
                    <option value="">-- Pilih Category --</option>

                    <?php while ($c = $categories->fetch_assoc()): ?>
                        <option value="<?= $c['category_id'] ?>"
                            <?= $c['category_id'] == $data['category_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($c['category_name']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Deadline</label>
                <input type="date" name="deadline"
                       value="<?= $data['due_date'] ?>" required>
            </div>
            <button type="submit">Update Task</button>
        </form>
    </div>

    <a href="list_task.php" class="back-link">← Back</a>

</div>
