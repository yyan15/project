<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: /project/auth/login.php");
    exit;
}

$current = $_SERVER['REQUEST_URI'];

function isActive($path){
    global $current;
    return str_contains($current, $path) ? 'active' : '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listify</title>

    <!-- CSS -->
    <link rel="stylesheet" href="/project/assets/css/styles.css">
</head>

<body>

<div class="box-container">

    <!-- TOP HEADER -->
    <nav class="header">
        <span>LISTIFY</span>

        <div class="user-box">
            <span>👤 <?= htmlspecialchars($_SESSION['username']) ?></span>
            <a href="/project/auth/logout.php" class="logout-btn">Logout</a>
        </div>
    </nav>

    <!-- NAV MENU -->
    <nav class="nav">
        <ul>
            <li><a href="/project/task/list_task.php" class="<?= isActive('/task/') ?>">TASK</a></li>
            <li><a href="/project/category/category_list.php" class="<?= isActive('/category/') ?>">CATEGORY</a></li>
            <li><a href="/project/priorty/list_priority.php" class="<?= isActive('/priorty/') ?>">PRIORITY</a></li>
            <li><a href="/project/reminder/list_reminder.php" class="<?= isActive('/reminder/') ?>">REMINDER</a></li>
            <li><a href="/project/note/list_note.php" class="<?= isActive('/note/') ?>">NOTE</a></li>
        </ul>
    </nav>

    <!-- CONTENT -->
    <div class="content">
