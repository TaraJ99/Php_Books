<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

if (($_SESSION['admin_username'] ?? '') !== 'hey') {
    $_SESSION['delete_error'] = "Only the hey account can delete books.";
    header("Location: book_view_admin.php");
    exit();
}

if (!isset($_GET['book_id'])) {
    header("Location: book_view_admin.php");
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'book_db');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$book_id = intval($_GET['book_id']);

// Fetch the book to save for undo
$stmt = $conn->prepare("SELECT * FROM books WHERE book_id = ?");
$stmt->bind_param("i", $book_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $book = $result->fetch_assoc();

    // Save book for undo
    $_SESSION['deleted_book'] = $book;
    $_SESSION['deleted_time'] = time();

    // Clear redo session
    unset($_SESSION['restored_book'], $_SESSION['restored_time']);

    // Delete book
    $stmt_del = $conn->prepare("DELETE FROM books WHERE book_id = ?");
    $stmt_del->bind_param("i", $book_id);
    $stmt_del->execute();
    $stmt_del->close();

    $_SESSION['delete_log'] = "hey deleted book_id ({$book_id})";
}

$stmt->close();
$conn->close();

header("Location: book_view_admin.php");
exit();
?>
