<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

if (($_SESSION['admin_username'] ?? '') !== 'hey') {
    $_SESSION['delete_error'] = "Only the hey account can undo deleted books.";
    header("Location: book_view_admin.php");
    exit();
}

if (!isset($_SESSION['deleted_book']) || !isset($_SESSION['deleted_time']) || time() - $_SESSION['deleted_time'] > 60) {
    // No undo or expired
    header("Location: book_view_admin.php");
    exit();
}

$book = $_SESSION['deleted_book'];

$conn = new mysqli('localhost', 'root', '', 'book_db');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Insert book back
$stmt = $conn->prepare("INSERT INTO books (book_id, book_name, no_of_pages, illustration, author, publisher, summary, genre) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param(
    "isisssss",
    $book['book_id'],
    $book['book_name'],
    $book['no_of_pages'],
    $book['illustration'],
    $book['author'],
    $book['publisher'],
    $book['summary'],
    $book['genre']
);
$undo_success = $stmt->execute();
$stmt->close();
$conn->close();

if ($undo_success) {
    // Save restored for redo
    $_SESSION['restored_book'] = $book;
    $_SESSION['restored_time'] = time();
    $_SESSION['undo_log'] = "hey restored book_id ({$book['book_id']})";

    // Clear deleted undo
    unset($_SESSION['deleted_book'], $_SESSION['deleted_time']);
} else {
    $_SESSION['delete_error'] = "Undo failed. Please try again.";
}

header("Location: book_view_admin.php");
exit();
?>
