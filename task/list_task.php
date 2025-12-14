<?php 
include __DIR__ . '/../header.php';
include __DIR__ . '/../config.php';

$user_id = $_SESSION['user_id'];

$query = "
    SELECT 
        t.task_id,
        t.title,
        c.category_name AS category,
        p.priority_name AS priority,
        p.level AS priority_level,
        t.due_date,
        t.status,
        COUNT(n.note_id) AS note_count
    FROM tasks t
    LEFT JOIN categories c ON t.category_id = c.category_id
    LEFT JOIN priority p ON t.priority_id = p.priority_id
    LEFT JOIN notes n ON t.task_id = n.task_id
    WHERE t.user_id = $user_id
    GROUP BY t.task_id
    ORDER BY t.task_id DESC
";

$result = $conn->query($query);
?>

<div class="page-top">
    <h2>TASK LIST</h2>
    <a href="add_task.php" class="btn-add">+ Add Task</a>
</div>

<table class="data-table">
    <tr>
        <th>Task</th>
        <th>Category</th>
        <th>Priority</th>
        <th>Deadline</th>
        <th>Note</th>
        <th>Status</th>
        <th>Action</th>
    </tr>

    <?php if ($result->num_rows > 0): ?>
        <?php $no = 1; ?>
        <?php while ($row = $result->fetch_assoc()): ?>
        <?php
            // background hijau kalau done
            $row_bg = ($row['status'] === 'done') ? 'background:#e9ffe9;' : '';
        ?>
        <tr style="<?= $row_bg ?>">
            <td><?= htmlspecialchars($row['title']) ?></td>
            <td><?= $row['category'] ?? '-' ?></td>
            <td>
                <?php
                $level = strtoupper($row['priority_level'] ?? '');
                if ($level === 'LOW') echo "🟢 Low";
                elseif ($level === 'MEDIUM') echo "🟡 Medium";
                elseif ($level === 'HIGH') echo "🔴 High";
                elseif ($level === 'URGENT') echo "🔴⚡ Urgent";
                else echo "-";
                ?>
            </td>
            <td><?= $row['due_date'] ?: '-' ?></td>
            <td class="note-cell">
                <?php if ($row['note_count'] > 0): ?>
                    <button class="btn-note" data-task="<?= $row['task_id'] ?>">
                        📝 <?= $row['note_count'] ?>
                    </button>
                <?php else: ?>
                    -
                <?php endif; ?>
            </td>
            <td>
                <form method="POST" action="update_status.php">
                    <input type="hidden" name="task_id" value="<?= $row['task_id'] ?>">
                    <select name="status" onchange="this.form.submit()">
                        <option value="pending" <?= $row['status']=='pending'?'selected':'' ?>>Pending ⏳</option>
                        <option value="in_progress" <?= $row['status']=='in_progress'?'selected':'' ?>>In Progress 🔄</option>
                        <option value="done" <?= $row['status']=='done'?'selected':'' ?>>Done ✅</option>
                    </select>
                </form>
            </td>
            <td>
                <a href="edit_task.php?id=<?= $row['task_id'] ?>" class="btn-edit">Edit</a>
                <a href="delete_task.php?id=<?= $row['task_id'] ?>" 
                   class="btn-delete"
                   onclick="return confirm('Hapus task ini?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="7" style="text-align:center; padding:20px;">
                Belum ada task. Klik <strong>+ Add Task</strong> untuk menambahkan.
            </td>
        </tr>
    <?php endif; ?>
</table>

<!-- NOTE MODAL -->
<div id="noteModal" class="note-modal">
    <div class="note-box">
        <span class="note-close">&times;</span>
        <h3>Task Notes</h3>
        <div id="noteContent">Loading...</div>
    </div>
</div>

<script>
const modal = document.getElementById("noteModal");
const noteContent = document.getElementById("noteContent");
const closeBtn = document.querySelector(".note-close");

document.querySelectorAll(".btn-note").forEach(btn => {
    btn.onclick = () => {
        modal.style.display = "flex";
        noteContent.innerHTML = "Loading...";

        fetch("get_note.php?task_id=" + btn.dataset.task)
            .then(res => res.text())
            .then(html => noteContent.innerHTML = html);
    };
});

closeBtn.onclick = () => modal.style.display = "none";

window.onclick = e => {
    if (e.target === modal) modal.style.display = "none";
};
</script>
