<?php

$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'book_db';

// Create connection
$conn = new mysqli($hostname, $username, $password, $database);

if($conn->connect_error) {
    die("Connection failed:". $conn->connect_error);
} 

// Added book_id here
$sql = "SELECT `book_id`, `book_name`, `no_of_pages`, `illustration`, `author`, `publisher`, `summary`, `genre` FROM `books` WHERE 1";
$result = $conn->query($sql);

$conn->close(); // Close the connection after querying
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book View Details</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            margin: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
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

        /* Optional: makes the book name look clickable */
        a {
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }
        .back-button { display: inline-block; background: #3498db; color: white; border: none; padding: 10px 18px; border-radius: 5px; cursor: pointer; font-size: 1rem; margin: 0 0 20px; text-align: center; }
        .back-button:hover { background: #2980b9; }
    </style>
</head>
<body>

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
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                
                // Only change is here (clickable book name)
                echo "<td><a href='book_pages/book_id_" . $row["book_id"] . ".php'>" . $row["book_name"] . "</a></td>";
                
                echo "<td>" . $row["no_of_pages"] . "</td>";
                echo "<td>" . $row["illustration"] . "</td>";
                echo "<td>" . $row["author"] . "</td>";
                echo "<td>" . $row["publisher"] . "</td>";
                echo "<td>" . $row["summary"] . "</td>";
                echo "<td>" . $row["genre"] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='8'>No results found</td></tr>";
        }
        ?>

    </table>
</body>
</html>