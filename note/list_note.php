<?php
include __DIR__ . '/../header.php';
include __DIR__ . '/../config.php';

$user_id = $_SESSION['user_id'];

// Query join untuk mengambil note milik user yang sedang login
// Sesuai ERD: Notes -> Tasks -> Users
$query = "
    SELECT 
        n.note_id,
        n.content,
        t.title AS task_title
    FROM notes n
    JOIN tasks t ON n.task_id = t.task_id
    WHERE t.user_id = ?
    ORDER BY n.created_at DESC
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$no = 1;
?>

<div class="reminder-container">

    <div class="reminder-top">
        <h2>NOTES</h2>
        <a href="add_note.php" class="btn-add">+ Add Note</a>
    </div>

    <table class="reminder-table">
        <tr>
            <th>No</th>
            <th>Task Name</th>
            <th>Note Content</th>
            <th>Action</th>
        </tr>

        <?php if ($result->num_rows > 0): ?>
            <?php $no = 1; ?>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['task_title']) ?></td>
                <td class="note-content">
                    <?= nl2br(htmlspecialchars($row['content'])) ?>
                </td>
                <td>
                    <a href="edit_note.php?id=<?= $row['note_id'] ?>" class="btn-edit">Edit</a>
                    <a href="delete_note.php?id=<?= $row['note_id'] ?>" 
                       class="btn-delete"
                       onclick="return confirm('Hapus catatan ini?')">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="4" style="text-align:center; padding:20px;">
                    Belum ada catatan. Klik <strong>+ Add Note</strong> untuk menambahkan.
                </td>
            </tr>
        <?php endif; ?>

    </table>
</div>
