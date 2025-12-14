<?php 
include __DIR__ . '/../header.php';
include __DIR__ . '/../config.php';
$user_id = $_SESSION['user_id'];

$query = "
    SELECT priority_id, priority_name, level, created_at
    FROM priority
    WHERE user_id = ?
    ORDER BY priority_id ASC
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$no = 1;
?>

<div class="reminder-container">

    <div class="reminder-top">
        <h2>PRIORITY</h2>
        <a href="add_priority.php" class="btn-add">+ Priority</a>
    </div>

    <table class="reminder-table">
        <tr>
            <th>No</th>
            <th>Priority Name</th>
            <th>Level</th>
            <th>Created At</th>
            <th>Action</th>
        </tr>

        <?php if ($result->num_rows > 0): ?>
            <?php $no = 1; ?>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['priority_name']) ?></td>
                <td>
                    <?php
                    $level = strtoupper(trim($row['level'] ?? ''));

                    if ($level === 'HIGH') echo "🔴 High";
                    elseif ($level === 'MEDIUM') echo "🟡 Medium";
                    elseif ($level === 'LOW') echo "🟢 Low";
                    else echo "-";
                    ?>
                </td>
                <td><?= htmlspecialchars($row['created_at']) ?></td>
                <td>
                    <a href="edit_priority.php?id=<?= $row['priority_id'] ?>" class="btn-edit">Edit</a>
                    <a href="delete_priority.php?id=<?= $row['priority_id'] ?>"
                       class="btn-delete"
                       onclick="return confirm('Hapus priority ini?')">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="5" style="text-align:center; padding:20px;">
                    Belum ada priority. Klik <strong>+ Priority</strong> untuk menambahkan.
                </td>
            </tr>
        <?php endif; ?>

    </table>

</div>


