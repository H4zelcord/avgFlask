<?php
session_start();

// Redirect to login if not authenticated
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header('Location: /avgFlask/php/public/login.php');
    exit();
}

// Handle manual tweet submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $theme = isset($_POST['theme']) ? $_POST['theme'] : null;
    $adjective = isset($_POST['adjective']) ? $_POST['adjective'] : null;

    if ($theme && $adjective) {
        // Call Python script to generate and post the tweet
        $command = escapeshellcmd("python ../python/manual_tweet.py '$theme' '$adjective'");
        $output = shell_exec($command);
        $result = json_decode($output, true);
    } else {
        $result = ["status" => "error", "message" => "Both theme and adjective are required."];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bot Administration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/avgFlask/php/public/styles.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Bot Administration Panel</h1>
        <p class="text-center">Manage your bot's settings and tweets here.</p>

        <!-- Manual Tweet Section -->
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title">Post a Manual Tweet</h5>
                <?php if (isset($result)): ?>
                    <div class="alert alert-<?php echo $result['status'] === 'success' ? 'success' : 'danger'; ?>">
                        <?php echo $result['message']; ?>
                    </div>
                <?php endif; ?>
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="theme" class="form-label">Theme:</label>
                        <input type="text" id="theme" name="theme" class="form-control" placeholder="Enter the theme (e.g., AI, technology)" required>
                    </div>
                    <div class="mb-3">
                        <label for="adjective" class="form-label">Adjective:</label>
                        <input type="text" id="adjective" name="adjective" class="form-control" placeholder="Enter the adjective (e.g., creative, funny)" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Post Tweet</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
