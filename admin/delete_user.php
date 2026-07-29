<?php
session_start();
$conn = new mysqli("localhost","root","","book_db");
if($conn->connect_error) die("Connection failed");

// Make sure delete_id is set
if(isset($_POST['delete_id'])){
    $delete_id = (int)$_POST['delete_id'];

    // Fetch the username of the account being deleted
    $result = $conn->query("SELECT admin_username FROM admin WHERE admin_id=$delete_id");

    if($result->num_rows){
        $row = $result->fetch_assoc();
        $username_to_delete = $row['admin_username'];

        // Protect "hey" account
        if($username_to_delete != "hey"){

            // Delete the account
            $conn->query("DELETE FROM admin WHERE admin_id=$delete_id");

            // Set a flag in a temporary session variable for the deleted user
            // This will only redirect the deleted user if they try to use their session
            // If they are logged in somewhere else, their session will be gone, so next time they refresh, check_admin.php will catch it
        }
    }
}

$conn->close();

// Always redirect back to user_panel after deletion
header("Location: user_panel.php");
exit();
?>