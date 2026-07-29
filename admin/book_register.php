<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

// Database connection
$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'book_db';

$conn = new mysqli($hostname, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$success_message = $_SESSION['register_success'] ?? '';
unset($_SESSION['register_success']);

$error_message = '';

// Register a new book
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $book_id = (int)$_POST['book_id'];
    $book_name = trim($_POST['book_name']);
    $no_of_pages = (int)$_POST['no_of_pages'];
    $illustration = trim($_POST['illustration']);
    $author = trim($_POST['author']);
    $publisher = trim($_POST['publisher']);
    $summary = trim($_POST['summary']);
    $genre = trim($_POST['genre']);

    $stmt = $conn->prepare("INSERT INTO books (book_id, book_name, no_of_pages, illustration, author, publisher, summary, genre) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isisssss", $book_id, $book_name, $no_of_pages, $illustration, $author, $publisher, $summary, $genre);

    if ($stmt->execute()) {
        $admin_username = $_SESSION['admin_username'] ?? 'admin';
        $log_entry = "{$admin_username} registered a new book ({$book_id})";

        if (!isset($_SESSION['register_protector_logs']) || !is_array($_SESSION['register_protector_logs'])) {
            $_SESSION['register_protector_logs'] = [];
        }

        $_SESSION['register_protector_logs'][] = $log_entry;
        $_SESSION['register_log'] = $log_entry;
        $_SESSION['register_success'] = $log_entry;

        $stmt->close();
        $conn->close();

        header("Location: manage_admin.php");
        exit();
    }

    $error_message = "Error: " . $stmt->error;
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Book Manager</title>
    <style>
        body { font-family: Arial; background: #f4f6f9; margin: 0; padding: 30px; }
        h2 { text-align: center; color: #333; }
        form { background: white; max-width: 600px; margin: 0 auto; padding: 25px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
        label { font-weight: bold; color: #555; }
        input[type="text"], input[type="number"], textarea { width: 100%; padding: 8px; margin-top: 5px; border-radius: 5px; border: 1px solid #ccc; box-sizing: border-box; transition: 0.2s; }
        input[type="text"]:focus, input[type="number"]:focus, textarea:focus { border-color: #3498db; outline: none; box-shadow: 0 0 4px rgba(52,152,219,0.4); }
        textarea { resize: vertical; min-height: 80px; }
        input[type="submit"] { background: #2ecc71; color: white; border: none; padding: 10px 18px; border-radius: 5px; cursor: pointer; font-weight: bold; }
        input[type="submit"]:hover { background: #27ae60; }
        .status-message { text-align: center; font-weight: bold; }
        .success-message { color: #1e8449; }
        .error-message { color: #c0392b; }
        .back-button { display: inline-block; background: #3498db; color: white; border: none; padding: 10px 18px; border-radius: 5px; cursor: pointer; font-size: 1rem; margin: 10px auto; text-align: center; }
        .back-button:hover { background: #2980b9; }
    </style>
</head>
<body>

<h2>Register a New Book</h2>

    <button type="button" class="back-button" onclick="window.location.href='manage_admin.php'">Go back</button>

<?php if ($success_message): ?>
    <p class="status-message success-message"><?php echo htmlspecialchars($success_message); ?></p>
<?php endif; ?>

<?php if ($error_message): ?>
    <p class="status-message error-message"><?php echo htmlspecialchars($error_message); ?></p>
<?php endif; ?>

<form method="POST" action="">
    <input type="hidden" name="register" value="1">
    <label>Book ID:</label><br>
    <input type="number" name="book_id" required><br><br>
    <label>Book Name:</label><br>
    <input type="text" name="book_name" required><br><br>
    <label>No Of Pages:</label><br>
    <input type="number" name="no_of_pages" required><br><br>
    <label>Illustration:</label><br>
    <input type="text" name="illustration" required><br><br>
    <label>Author:</label><br>
    <input type="text" name="author" required><br><br>
    <label>Publisher:</label><br>
    <input type="text" name="publisher" required><br><br>
    <label>Summary:</label><br>
    <textarea name="summary" required></textarea><br><br>
    <label>Genre:</label><br>
    <input type="text" name="genre" required><br><br>
    <input type="submit" value="Register Book">
</form>

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

</body>
</html>
