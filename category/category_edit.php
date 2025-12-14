<?php
include __DIR__ . '/../header.php';
include __DIR__ . '/../config.php';

$user_id = $_SESSION['user_id'];
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $conn->prepare("
    SELECT category_id, category_name
    FROM categories
    WHERE category_id = ?
    AND user_id = ?
");
$stmt->bind_param("ii", $id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    exit('Data tidak ditemukan');
}
?>



<div class="form-container">

    <h2>Edit Category</h2>

    <div class="form-box">
        <form method="post" action="category_update.php">

            <input type="hidden" name="id" value="<?= $data['category_id'] ?>">

            <div class="form-group">
                <label>Category Name</label>
                <input type="text"
                       name="category_name"
                       value="<?= htmlspecialchars($data['category_name']) ?>"
                       required>
            </div>

            <button type="submit">Update</button>

        </form>
    </div>

    <a href="category_list.php" class="back-link">← Back</a>

</div>
