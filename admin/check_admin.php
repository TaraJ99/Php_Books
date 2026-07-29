<?php
session_start();
$conn = new mysqli("localhost","root","","book_db");
if($conn->connect_error) die("Connection failed");

// Only check the currently logged-in admin
if(!isset($_SESSION['admin_username'])){
    exit(); // no session, do nothing
}

$username = $_SESSION['admin_username'];

// Check if this admin exists
$result = $conn->query("SELECT admin_id FROM admin WHERE admin_username='$username'");
if($result->num_rows === 0){
    echo "deleted"; // This account was deleted
}else{
    echo "exists";
}
$conn->close();
?>