<?php
include __DIR__ . "/../config.php";
include __DIR__ . "/../header.php";

session_start();
$user_id = $_SESSION['user_id'] ?? 0;
if ($user_id <= 0) die("User belum login");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = (int)$_POST['id'];
    $task_id = (int)$_POST['task_id'];
    $level = trim($_POST['level']);

    if ($id <= 0 || $task_id <= 0 || $level === '') {
        die("Input tidak lengkap");
    }

    // 1. Pastikan priority milik user
    $chk = $conn->prepare("SELECT priority_id FROM priority WHERE priority_id = ? AND user_id = ?");
    $chk->bind_param("ii", $id, $user_id);
    $chk->execute();
    $cek = $chk->get_result();
    if ($cek->num_rows === 0) {
        die("Priority tidak valid");
    }

    // 2. Ambil title task -> untuk priority_name
    $getTask = $conn->prepare("SELECT title FROM tasks WHERE task_id = ? AND user_id = ?");
    $getTask->bind_param("ii", $task_id, $user_id);
    $getTask->execute();
    $task = $getTask->get_result()->fetch_assoc();
    if (!$task) die("Task tidak ditemukan");

    $priority_name = $task['title'];

    // 3. Update priority
    $upd = $conn->prepare("
        UPDATE priority 
        SET priority_name = ?, level = ? 
        WHERE priority_id = ? AND user_id = ?
    ");
    $upd->bind_param("ssii", $priority_name, $level, $id, $user_id);
    $upd->execute();

    // 4. Clear priority_id di task lama
    $clear = $conn->prepare("UPDATE tasks SET priority_id = NULL WHERE priority_id = ? AND user_id = ?");
    $clear->bind_param("ii", $id, $user_id);
    $clear->execute();

    // 5. Set priority ke task baru
    $set = $conn->prepare("UPDATE tasks SET priority_id = ? WHERE task_id = ? AND user_id = ?");
    $set->bind_param("iii", $id, $task_id, $user_id);
    $set->execute();
}

header("Location: list_priority.php");
exit;
