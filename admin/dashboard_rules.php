<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rights and restrictions</title>
    <link rel="stylesheet" href="dashboard_rules.css">
</head>
<body>
    <div class="page-shell">
        <div class="rules-card">
            <div class="rules-header">
                <h1>Dashboard Rules</h1>
                <p>These are your rights and restrictions for this page below. You should read them before continuing to enter the admin dashboard.</p>
            </div>

            <div class="table-wrap">
                <table class="rules-table">
                    <thead>
                        <tr>
                            <th>Right</th>
                            <th>Restrictions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="placeholder">You can edit book</td>
                            <td class="placeholder">You can't delete books</td>
                        </tr>
                        <tr>
                            <td class="placeholder">You can make multiple accounts by just logging in</td>
                            <td class="placeholder">You can't delete accouunts</td>
                        </tr>
                        <tr>
                            <td class="placeholder">You can register books</td>
                            <td class="placeholder">You cant see how many people edited or registered books.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="rules-footer">
                <a href="dashboard.php" class="continue-button">Continue</a>
            </div>
        </div>
    </div>
</body>
</html>
