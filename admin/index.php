<?php
session_start();

$conn = new mysqli("localhost", "root", "", "book_db");
if ($conn->connect_error) {
    die("Connection failed");
}

$message = "";

function normalize_ip($ip) {
    if (strpos($ip, "::ffff:") === 0) {
        return substr($ip, 7);
    }

    return $ip;
}

function is_trusted_hey_device() {
    $remote_addr = normalize_ip($_SERVER['REMOTE_ADDR'] ?? '');
    $allowed_addresses = ['127.0.0.1', '::1'];

    if (!empty($_SERVER['SERVER_ADDR'])) {
        $allowed_addresses[] = normalize_ip($_SERVER['SERVER_ADDR']);
    }

    $host_ip = gethostbyname(gethostname());
    if (!empty($host_ip) && $host_ip !== gethostname()) {
        $allowed_addresses[] = normalize_ip($host_ip);
    }

    return in_array($remote_addr, array_unique($allowed_addresses), true);
}

if (isset($_POST['login'])) {

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (strcasecmp($username, "hey") === 0 && !is_trusted_hey_device()) {
        $message = "The hey account can only be used on this device.";
    } else {
        $stmt = $conn->prepare("SELECT admin_id, admin_password FROM admin WHERE admin_username=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {

            $stmt->bind_result($admin_id, $db_password);
            $stmt->fetch();

            if (password_verify($password, $db_password)) {

                $_SESSION['admin_id'] = $admin_id;
                $_SESSION['admin_username'] = $username;

                header("Location: dashboard_rules.php");
                exit();

            } else {
                $message = "Wrong password.";
            }

        } else {
            $message = "Admin not found.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Login</title>

<style>

body{
    font-family: Arial, Helvetica, sans-serif;
    background: linear-gradient(135deg,#4e73df,#1cc88a);
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
}

.login-container{
    background:white;
    padding:35px;
    border-radius:12px;
    width:320px;
    box-shadow:0 10px 25px rgba(0,0,0,0.2);
    text-align:center;
}

h2{
    margin-bottom:20px;
}

input{
    width:100%;
    padding:10px;
    border:1px solid #ccc;
    border-radius:6px;
    margin-top:5px;
}

.password-container{
    position:relative;
}

.toggle-password{
    position:absolute;
    right:10px;
    top:50%;
    transform:translateY(-50%);
    cursor:pointer;
}

button{
    width:100%;
    padding:10px;
    border:none;
    border-radius:6px;
    background:#4e73df;
    color:white;
    font-weight:bold;
    cursor:pointer;
    transition:0.3s;
}

button:hover{
    background:#2e59d9;
}

.signup-btn{
    background:#1cc88a;
}

.signup-btn:hover{
    background:#17a673;
}

.message{
    color:red;
    margin-bottom:10px;
}

a{
    text-decoration:none;
}

</style>

</head>
<body>

<div class="login-container">

<h2>Admin Login</h2>

<p class="message"><?php echo $message; ?></p>

<form method="POST">

Username:<br>
<input type="text" name="username" required><br><br>

Password:<br>

<div class="password-container">
<input type="password" name="password" id="password" required>
<span id="togglePassword" class="toggle-password">👁️</span>
</div>

<br>

<button name="login">Login</button>

</form>

<br>

<a href="signup.php">
<button class="signup-btn">Sign Up</button>
</a>

</div>

<script>
const password = document.getElementById("password");
const toggle = document.getElementById("togglePassword");

toggle.addEventListener("click", () => {
    if (password.type === "password") {
        password.type = "text";
        toggle.textContent = "🙈";
    } else {
        password.type = "password";
        toggle.textContent = "👁️";
    }
});
</script>

</body>
</html>
