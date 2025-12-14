<?php 
include __DIR__ . '/../header.php';
include __DIR__ . '/../config.php';
$user_id = $_SESSION['user_id'];

$query = "
    SELECT 
        r.reminder_id,
        t.title,
        r.reminder_time,
        r.is_sent
    FROM reminders r
    JOIN tasks t ON r.task_id = t.task_id
    WHERE r.user_id = $user_id
    ORDER BY r.reminder_time ASC
";


$result = $conn->query($query);
$no = 1;

?>

<script>
const sound = new Audio('/project/assets/audio/italian-brainrot-ringtone.mp3');

function checkReminder() {
    fetch('check_reminder.php')
        .then(res => res.json())
        .then(data => {

            if (data.length > 0) {

                sound.play();

                data.forEach(rem => {

                    alert("REMINDER:\n" + rem.title);

                    // update tabel tanpa refresh
                    const row = document.querySelector(`tr[data-id="${rem.reminder_id}"]`);
                    if (row) {
                        row.querySelector('.status').innerHTML = "Sent ✅";
                    }

                });
            }

        });
}

setInterval(checkReminder, 5000);
</script>

<div class="reminder-container">
    <div class="reminder-top">
        <h2>REMINDER</h2>
        <a href="add_reminder.php" class="btn-add">+ Add Reminder</a>
    </div>

    <table class="reminder-table">
        <tr>
            <th>No</th>
            <th>Reminder</th>
            <th>Date</th>
            <th>Time</th>
            <th>Action</th>
        </tr>

        <?php if ($result->num_rows > 0): ?>
            <?php $no = 1; ?>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr data-id="<?= $row['reminder_id'] ?>">
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td><?= htmlspecialchars($row['reminder_time']) ?></td>
                <td class="status"><?= $row['is_sent'] ? "Sent ✅" : "Pending ⏳" ?></td>
                <td>
                    <a href="edit_reminder.php?id=<?= $row['reminder_id'] ?>" class="btn-edit">Edit</a>
                    <a href="delete_reminder.php?id=<?= $row['reminder_id'] ?>" class="btn-delete"
                       onclick="return confirm('Hapus task ini?')">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="5" style="text-align:center; padding:20px;">
                    Belum ada reminder. Klik <strong>+ Add Reminder</strong> untuk menambahkan.
                </td>
            </tr>
        <?php endif; ?>
    </table>
</div>
