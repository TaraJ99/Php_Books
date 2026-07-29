<?php
session_start();
$conn = new mysqli("localhost","root","","book_db");
if($conn->connect_error) die("Connection failed");

// Fetch all admins
$result = $conn->query("SELECT admin_id, admin_username FROM admin ORDER BY admin_id ASC");
?>

<!DOCTYPE html>
<html>
<head>
<title>User Panel</title>
<style>
table{width:80%;margin:auto;border-collapse:collapse;}
th,td{border:1px solid #ccc;padding:10px;text-align:center;}
th{background:#333;color:white;}
.delete-btn{background:red;color:white;padding:6px 12px;border:none;cursor:pointer;}
</style>
</head>
<body>

<h2 style="text-align:center;">User Panel</h2>

<table>
<tr><th>No</th><th>Username</th><th>Action</th></tr>

<?php $number=1; while($row=$result->fetch_assoc()){ ?>
<tr>
<td><?php echo $number++; ?></td>
<td><?php echo htmlspecialchars($row['admin_username']); ?></td>
<td>
<?php if($row['admin_username'] != "hey"){ ?>
<form method="POST" action="delete_user.php">
<input type="hidden" name="delete_id" value="<?php echo $row['admin_id']; ?>">
<button class="delete-btn">Delete</button>
</form>
<?php } else { echo "Protected"; } ?>
</td>
</tr>
<?php } ?>
</table>

</body>
</html>