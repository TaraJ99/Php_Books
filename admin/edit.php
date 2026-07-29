<?php
session_start(); // for session logs

// Database connection
$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'book_db';

$conn = new mysqli($hostname, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = '';

if (!isset($_GET['book_id'])) {
    die("No book_id provided.");
}

$original_book_id = (int)$_GET['book_id'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $book_id = (int)$_POST['book_id'];
    $book_name = trim($_POST['book_name']);
    $no_of_pages = (int)$_POST['no_of_pages'];
    $illustration = trim($_POST['illustration']);
    $author = trim($_POST['author']);
    $publisher = trim($_POST['publisher']);
    $summary = trim($_POST['summary']);
    $genre = trim($_POST['genre']);

    // Check duplicate book_id
    if ($book_id !== $original_book_id) {
        $check_stmt = $conn->prepare("SELECT COUNT(*) FROM books WHERE book_id = ?");
        $check_stmt->bind_param("i", $book_id);
        $check_stmt->execute();
        $check_stmt->bind_result($count);
        $check_stmt->fetch();
        $check_stmt->close();

        if ($count > 0) {
            $message = "<p style='color:red;'>Update failed: The new book_id {$book_id} already exists.</p>";
        }
    }

    if (empty($message)) {
        // Fetch old values for logging
        $old_stmt = $conn->prepare("SELECT book_id, book_name, no_of_pages, illustration, author, publisher, summary, genre FROM books WHERE book_id=?");
        $old_stmt->bind_param("i", $original_book_id);
        $old_stmt->execute();
        $old_result = $old_stmt->get_result();
        $old_values = $old_result->fetch_assoc();
        $old_stmt->close();

        $stmt = $conn->prepare("UPDATE books SET book_id=?, book_name=?, no_of_pages=?, illustration=?, author=?, publisher=?, summary=?, genre=? WHERE book_id=?");
        $stmt->bind_param("isisssssi", $book_id, $book_name, $no_of_pages, $illustration, $author, $publisher, $summary, $genre, $original_book_id);

        if ($stmt->execute()) {
            $username = $_SESSION['admin_username'] ?? "admin";

            // Log each changed field and send to edit_protector.php
            $fields = ['book_id','book_name','no_of_pages','illustration','author','publisher','summary','genre'];
            foreach ($fields as $field) {
                if ((string)$old_values[$field] !== (string)$$field) {
                    $old_val = $old_values[$field];
                    $new_val = $$field;

                    // Format book_id properly
                    $book_id_display = "book_id ({$book_id})";

                    // Send log to edit_protector.php (no redirect)
                    $log_url = "edit_protector.php?"
                             . "book_id=" . urlencode($book_id)
                             . "&field=" . urlencode($field)
                             . "&old=" . urlencode($old_val)
                             . "&new=" . urlencode($new_val);
                    @file_get_contents($log_url);

                    // Store in session log for display
                    if (!isset($_SESSION['edit_protector_logs'])) {
                        $_SESSION['edit_protector_logs'] = [];
                    }
                    $_SESSION['edit_protector_logs'][] = "{$username} edited {$book_id_display} {$field} from '{$old_val}' to '{$new_val}'";
                }
            }

            // ✅ Redirect to book_view_admin.php after update
            header("Location: book_view_admin.php");
            exit;

        } else {
            $message = "<p style='color:red;'>Update failed: " . $stmt->error . "</p>";
        }

        $stmt->close();
    }
}

// Fetch book data
$stmt = $conn->prepare("SELECT * FROM books WHERE book_id=?");
$stmt->bind_param("i", $original_book_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die("Book not found.");
}

$book = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Book</title>
<style>
body { font-family: Arial, sans-serif; background: #f4f6f9; margin: 0; padding: 30px; }
h1 { text-align: center; color: #333; }
form { background: white; max-width: 600px; margin: 0 auto; padding: 25px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.08);}
label { font-weight: bold; color: #555; }
input[type="text"], input[type="number"], textarea { width:100%; padding:8px; margin-top:5px; border-radius:5px; border:1px solid #ccc; box-sizing:border-box; transition:0.2s;}
input[type="text"]:focus, input[type="number"]:focus, textarea:focus { border-color:#3498db; outline:none; box-shadow:0 0 4px rgba(52,152,219,0.4);}
textarea { resize: vertical; }
input[type="submit"] { background:#3498db; color:white; border:none; padding:10px 18px; border-radius:5px; cursor:pointer; font-weight:bold; margin-right:10px;}
input[type="submit"]:hover { background:#2980b9;}
a { text-decoration:none; padding:10px 18px; border-radius:5px; background:#95a5a6; color:white;}
a:hover { background:#7f8c8d;}
p { text-align:center; font-weight:bold;}
</style>
</head>
<body>
<h1>Edit Book</h1>
<?php echo $message; ?>
<form method="post" action="edit.php?book_id=<?php echo $original_book_id; ?>">
    <label>Book Id:</label><br>
    <input type="number" name="book_id" value="<?php echo htmlspecialchars($book['book_id']); ?>" required><br><br>
    <label>Book Name:</label><br>
    <input type="text" name="book_name" value="<?php echo htmlspecialchars($book['book_name']); ?>" required><br><br>
    <label>Number of Pages:</label><br>
    <input type="number" name="no_of_pages" value="<?php echo htmlspecialchars($book['no_of_pages']); ?>" required><br><br>
    <label>Illustration:</label><br>
    <input type="text" name="illustration" value="<?php echo htmlspecialchars($book['illustration']); ?>"><br><br>
    <label>Author:</label><br>
    <input type="text" name="author" value="<?php echo htmlspecialchars($book['author']); ?>" required><br><br>
    <label>Publisher:</label><br>
    <input type="text" name="publisher" value="<?php echo htmlspecialchars($book['publisher']); ?>"><br><br>
    <label>Summary:</label><br>
    <textarea name="summary" rows="4" cols="50"><?php echo htmlspecialchars($book['summary']); ?></textarea><br><br>
    <label>Genre:</label><br>
    <input type="text" name="genre" value="<?php echo htmlspecialchars($book['genre']); ?>"><br><br>
    <input type="submit" value="Update Book">
    <a href="book_view_admin.php">Cancel</a>
</form>
</body>
</html>