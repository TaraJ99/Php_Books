<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

$is_delete_admin = (($_SESSION['admin_username'] ?? '') === 'hey');

// Expire undo after 1 min
if (isset($_SESSION['deleted_time']) && time() - $_SESSION['deleted_time'] > 60) {
    unset($_SESSION['deleted_book'], $_SESSION['deleted_time']);
}

// Expire redo after 1 min
if (isset($_SESSION['restored_time']) && time() - $_SESSION['restored_time'] > 60) {
    unset($_SESSION['restored_book'], $_SESSION['restored_time']);
}

$can_undo_delete = $is_delete_admin && isset($_SESSION['deleted_book'], $_SESSION['deleted_time']);
$can_redo_delete = $is_delete_admin && !$can_undo_delete && isset($_SESSION['restored_book'], $_SESSION['restored_time']);

$conn = new mysqli('localhost', 'root', '', 'book_db');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query("SELECT * FROM books ORDER BY book_id ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Books View Admin</title>

    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f9; margin: 0; padding: 20px; }
        h1 { text-align: center; color: #333; }
        table { background: white; box-shadow: 0 4px 12px rgba(0,0,0,0.08); border-radius: 8px; overflow: hidden; width: 100%; border-collapse: collapse;}
        th { background: #2c3e50; color: white; padding: 12px; text-align: left; }
        td { padding: 10px; }
        tr:nth-child(even) { background: #f2f2f2; }
        tr:hover { background: #e8f4ff; transition: 0.2s; }
        .action-button { padding: 6px 12px; text-decoration: none; border-radius: 5px; font-size: 14px; margin-right: 5px; display: inline-block; }
        .edit-button { background: #3498db; color: white; }
        .edit-button:hover { background: #2980b9; }
        .delete-button { background: #e74c3c; color: white; }
        .delete-button:hover { background: #c0392b; }

        /* LOG MESSAGE STYLE */
        .edit-log { text-align: center; font-weight: bold; color: green; margin-bottom: 15px; }
        .error-log { text-align: center; font-weight: bold; color: #c0392b; margin-bottom: 15px; }
        .page-actions { text-align: center; margin-bottom: 20px; }
        .status-button { color: white; margin: 0; }
        .undo-button { background: #27ae60; }
        .undo-button:hover { background: #1f8b4d; }
        .redo-button { background: #f39c12; }
        .redo-button:hover { background: #d68910; }
        .back-button { display: inline-block; background: #3498db; color: white; border: none; padding: 10px 18px; border-radius: 5px; cursor: pointer; font-size: 1rem; margin: 0 auto 20px; text-align: center; }
        .back-button:hover { background: #2980b9; }
    </style>
</head>
<body>

<h1>Books View Admin</h1>

    <button type="button" class="back-button" onclick="window.location.href='manage_admin.php'">Go back</button>

<?php
// ✅ Display edit log message if set
if (isset($_SESSION['edit_log'])) {
    echo "<p class='edit-log'>" . $_SESSION['edit_log'] . "</p>";
    unset($_SESSION['edit_log']); // remove it so it only shows once
}

if (isset($_SESSION['delete_log'])) {
    echo "<p class='edit-log'>" . $_SESSION['delete_log'] . "</p>";
    unset($_SESSION['delete_log']);
}

if (isset($_SESSION['delete_error'])) {
    echo "<p class='error-log'>" . $_SESSION['delete_error'] . "</p>";
    unset($_SESSION['delete_error']);
}

if (isset($_SESSION['undo_log'])) {
    echo "<p class='edit-log'>" . $_SESSION['undo_log'] . "</p>";
    unset($_SESSION['undo_log']);
}

if (isset($_SESSION['redo_log'])) {
    echo "<p class='edit-log'>" . $_SESSION['redo_log'] . "</p>";
    unset($_SESSION['redo_log']);
}
?>

<?php if ($can_undo_delete): ?>
    <div class="page-actions">
        <a href="undo.php" class="action-button status-button undo-button">Undo Delete</a>
    </div>
<?php elseif ($can_redo_delete): ?>
    <div class="page-actions">
        <a href="redo.php" class="action-button status-button redo-button">Redo Delete</a>
    </div>
<?php endif; ?>

<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>Book ID</th>
            <th>Book Name</th>
            <th>No Of Pages</th>
            <th>Illustration</th>
            <th>Author</th>
            <th>Publisher</th>
            <th>Summary</th>
            <th>Genre</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result->num_rows === 0): ?>
            <tr><td colspan="9" style="text-align:center;">No books found.</td></tr>
        <?php else: ?>
            <?php while ($book = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($book['book_id']) ?></td>
                    <td><?= htmlspecialchars($book['book_name']) ?></td>
                    <td><?= htmlspecialchars($book['no_of_pages']) ?></td>
                    <td><?= htmlspecialchars($book['illustration']) ?></td>
                    <td><?= htmlspecialchars($book['author']) ?></td>
                    <td><?= htmlspecialchars($book['publisher']) ?></td>
                    <td><?= nl2br(htmlspecialchars($book['summary'])) ?></td>
                    <td><?= htmlspecialchars($book['genre']) ?></td>
                    <td>
                        <a href="edit.php?book_id=<?= urlencode($book['book_id']) ?>" class="action-button edit-button">Edit</a>
                        <?php if ($is_delete_admin): ?>
                            <a href="delete.php?book_id=<?= urlencode($book['book_id']) ?>" class="action-button delete-button" onclick="return confirm('Are you sure you want to delete this book?');">Delete</a>
                        <?php else: ?>
                            <a href="post_view_admin.php" class="action-button delete-button">Delete</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php endif; ?>
    </tbody>
</table>

<!-- REDIRECT IF DELETED -->
<script>
setInterval(function(){
    fetch("check_admin.php")
    .then(res => res.text())
    .then(data => {
        if(data.trim() === "deleted"){
            window.location.href = "deleted_admin.php";
        }
    });
}, 2000);
</script>

<?php $conn->close(); ?>
</body>
</html>
