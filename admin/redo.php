<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

if (($_SESSION['admin_username'] ?? '') !== 'hey') {
    $_SESSION['delete_error'] = "Only the hey account can redo deleted books.";
    header("Location: book_view_admin.php");
    exit();
}

if (!isset($_SESSION['restored_book']) || !isset($_SESSION['restored_time']) || time() - $_SESSION['restored_time'] > 60) {
    // No redo or expired
    header("Location: book_view_admin.php");
    exit();
}

$book_id = $_SESSION['restored_book']['book_id'];

$conn = new mysqli('localhost', 'root', '', 'book_db');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("DELETE FROM books WHERE book_id = ?");
$stmt->bind_param("i", $book_id);
$redo_success = $stmt->execute();
$stmt->close();
$conn->close();

if ($redo_success) {
    $_SESSION['redo_log'] = "hey re-deleted book_id ({$book_id})";

    // Clear redo session so undo does not appear again
    unset($_SESSION['restored_book'], $_SESSION['restored_time']);
} else {
    $_SESSION['delete_error'] = "Redo failed. Please try again.";
}

header("Location: book_view_admin.php");
exit();
?>
