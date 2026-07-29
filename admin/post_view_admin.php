<?php
// Optional: start session if you're using login sessions
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Access Restricted</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f4f6f9;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background: white;
            padding: 40px;
            width: 500px;
            text-align: center;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        h2 {
            color: #e74c3c;
            margin-bottom: 15px;
        }

        p {
            color: #555;
            margin-bottom: 30px;
        }

        .btn {
            display: inline-block;
            padding: 12px 20px;
            margin: 10px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            transition: 0.3s ease;
        }

        .edit-btn {
            background-color: #3498db;
            color: white;
        }

        .edit-btn:hover {
            background-color: #2980b9;
        }

        .view-btn {
            background-color: #2ecc71;
            color: white;
        }

        .view-btn:hover {
            background-color: #27ae60;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>❌ You are not allowed to delete books</h2>
    <p>But, you are allowed to edit them.</p>

        <p>When you click this button it will take you back to Book View Admin and if you want to edit you will see the edit 
            button of what book you want to edit .</p>
    <a href="http://localhost/books/public/admin/book_view_admin.php" class="btn view-btn">
        Back to Book View Admin
    </a>
</div>

</body>
</html>