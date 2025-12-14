<?php
include __DIR__ . '/../header.php';
include __DIR__ . '/../config.php';

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("
    SELECT category_id, category_name
    FROM categories
    WHERE user_id = ?
    ORDER BY category_id ASC
");

$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();

$no = 1;

?>

<div class="reminder-container">

    <div class="reminder-top">
        <h2>CATEGORIES</h2>
        <a href="category_add.php" class="btn-add">+ Add Category</a>
    </div>

    <table class="reminder-table">
        <tr>
            <th>No</th>
            <th>Category</th>
            <th>Action</th>
        </tr>

        <?php if ($result->num_rows > 0): ?>
            <?php $no = 1; ?>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['category_name']) ?></td>
                <td>
                    <a href="category_edit.php?id=<?= $row['category_id'] ?>" class="btn-edit">Edit</a>
                    <a href="category_delete.php?id=<?= $row['category_id'] ?>"
                       onclick="return confirm('Hapus kategori ini?')"
                       class="btn-delete">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="3" style="text-align:center; padding:20px;">
                    Belum ada kategori. Klik <strong>+ Add Category</strong> untuk menambahkan.
                </td>
            </tr>
        <?php endif; ?>

    </table>
</div>
