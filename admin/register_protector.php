<?php
session_start();

if (!isset($_SESSION['register_protector_logs']) || !is_array($_SESSION['register_protector_logs'])) {
    $_SESSION['register_protector_logs'] = [];
}

if (isset($_GET['clear']) && $_GET['clear'] == '1') {
    $_SESSION['register_protector_logs'] = [];
    unset($_SESSION['register_log']);
    header("Location: register_protector.php");
    exit();
}

$success_message = $_SESSION['register_log'] ?? null;
unset($_SESSION['register_log']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register Protector Logs</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 30px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .success-log {
            text-align: center;
            font-weight: bold;
            color: #1e8449;
            margin: 0 auto 20px auto;
        }
        .log-container {
            max-width: 800px;
            margin: 20px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }
        .log-entry {
            background: #ecf0f1;
            margin: 10px 0;
            padding: 12px;
            border-radius: 5px;
            font-size: 15px;
            color: #2c3e50;
        }
        .log-entry:nth-child(odd) {
            background: #dce6f0;
        }
        .actions {
            text-align: center;
            margin-top: 15px;
        }
        .actions a {
            display: inline-block;
            margin: 10px 10px 0 0;
            text-decoration: none;
            padding: 10px 15px;
            background: #3498db;
            color: white;
            border-radius: 5px;
        }
        .actions a:hover {
            background: #2980b9;
        }
    </style>
</head>
<body>
    <h1>Register Protector Logs</h1>

    <?php if ($success_message): ?>
        <p class="success-log"><?php echo htmlspecialchars($success_message); ?></p>
    <?php endif; ?>

    <div class="log-container">
        <?php if (!empty($_SESSION['register_protector_logs'])): ?>
            <?php foreach (array_reverse($_SESSION['register_protector_logs']) as $entry): ?>
                <div class="log-entry"><?php echo htmlspecialchars($entry); ?></div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="text-align:center; font-weight:bold;">No registered book logs yet.</p>
        <?php endif; ?>
    </div>

    <div class="actions">
        <a href="?clear=1">Clear Logs</a>
    </div>

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
