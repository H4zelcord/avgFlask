<?php
session_start();

// Redirect to login if not authenticated
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header('Location: /avgFlask/php/public/login.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bot Administration</title>
</head>
<body>
    <h1>Welcome to the Bot Administration Panel</h1>
    <p>Manage your bot's settings and tweets here.</p>
    <a href="/avgFlask/php/public/logout.php">Logout</a>
</body>
</html>
