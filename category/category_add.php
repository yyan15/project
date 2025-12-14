<?php
include __DIR__ . '/../header.php';
?>

<div class="form-container">
    <h2>Add Category</h2>

    <div class="form-box">
        <form method="post" action="category_store.php">
            <div class="form-group">
                <label>Category Name</label>
                <input type="text" name="category_name" required>
            </div>
            <button type="submit">Save</button>
        </form>
    </div>

    <a href="category_list.php" class="back-link">← Back</a>
</div>
