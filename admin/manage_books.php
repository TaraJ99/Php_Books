<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Books</title>
    <link rel="stylesheet" href="dashboard.css">
    <style>
        /* Center the image under the title inside the white header */
        .header img {
            display: block;
            margin: 20px auto 0 auto;
            max-width: 100%; /* responsive */
            height: auto;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Book Menu</h2>
        <ul>
            <li><a href="http://localhost/books/public/admin/dashboard.php"><h2>Back to dashboard</h2></a></li>
            <li><a href="http://localhost/books/public/book_view.php"><p><h2>Book View</h2></p></a></li>
            <li><a href="http://localhost/books/public/details.php"><p><h2>Details</h2></p></a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main">
<div class="header">
    <h1>Manage Books</h1>
    <img src="images/books.jpg" alt="Books Image">
</div>    </div>

</body>
</html>