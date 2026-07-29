<?php
$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'book_db';

// Create connection
$conn = new mysqli($hostname, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);  // Check for connection errors
}

$sql = "SELECT book_name, no_of_pages, illustration, author, publisher, summary, genre FROM books";
$result = $conn->query($sql);

// Debugging: Check if the query was successful
if ($result === false) {
    echo "Error executing query: " . $conn->error;  // Show any query errors
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book View Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: transparent;
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .book-banner {
            width: min(100%, 1200px);
            height: 600px;
            margin: 0 auto 30px;
            background-color: transparent;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 22px 45px rgba(16, 31, 53, 0.22);
        }

        .book-banner img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        th {
            background-color: #16a8d9;
            color: white;
            padding: 12px;
            text-transform: capitalize;
        }

        td {
            padding: 10px;
            border: 1px solid #ddd;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #e6f2ff;
        }

        @media (max-width: 768px) {
            .book-banner {
                height: 420px;
                border-radius: 18px;
            }
        } 
               a:hover {
            text-decoration: underline;
        }
        .back-button { display: inline-block; background: #3498db; color: white; border: none; padding: 10px 18px; border-radius: 5px; cursor: pointer; font-size: 1rem; margin: 0 0 20px; text-align: center; }
        .back-button:hover { background: #2980b9; }

    </style>
</head>
<body>
    <button type="button" class="back-button" onclick="window.location.href='admin/signup.php'">Sign up to have admin privileges (free)</button>
    <section class="book-banner">
        <img src="images/book_banner.png" alt="Books banner">
    </section>

    <h1>Book View Details</h1>

        <button type="button" class="back-button" onclick="window.location.href='admin/manage_books.php'">Go back</button>

    <table>
        <tr>
            <th>book_name</th>
            <th>no_of_pages</th>
            <th>illustration</th>
            <th>author</th>
            <th>publisher</th>
            <th>summary</th>
            <th>genre</th>
        </tr>

        <?php 
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["book_name"] . "</td>";
                echo "<td>" . $row["no_of_pages"] . "</td>";
                echo "<td>" . $row["illustration"] . "</td>";
                echo "<td>" . $row["author"] . "</td>";
                echo "<td>" . $row["publisher"] . "</td>";
                echo "<td>" . $row["summary"] . "</td>";
                echo "<td>" . $row["genre"] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='8'>No books found.</td></tr>";
        }

        $conn->close();  // Close the connection
        ?>
    </table>
</body>
</html>
