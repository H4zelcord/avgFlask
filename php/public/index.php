<?php
session_start();

// Redirect to login page if the user is not authenticated
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header('Location: /avgFlask/php/public/login.php');
    exit();
}

// Initialize variables
$result = null;
$generated_tweet = null;

/**
 * Handle manual tweet generation.
 * This block processes the form submission for generating the AI tweet.
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'generate_tweet') {
    $theme = isset($_POST['theme']) ? $_POST['theme'] : null;
    $adjective = isset($_POST['adjective']) ? $_POST['adjective'] : null;

    if ($theme && $adjective) {
        $script_path = "../../python/generate_tweet.py"; // Relative path
        $command = escapeshellcmd("python \"$script_path\" \"$theme\" \"$adjective\"");
        $output = shell_exec($command);

        // Log the executed command and its output for debugging
        printf("Command: $command");
        printf("Output: $output");

        $result = json_decode($output, true);

        // Handle invalid JSON output
        if ($result === null) {
            error_log("Failed to decode JSON output from Python script.");
            error_log("Raw Output: $output");
            $result = ["status" => "error", "message" => "The operation was completed, but the response could not be parsed."];
        } elseif ($result['status'] === 'success') {
            $generated_tweet = $result['message'];
        }
    } else {
        $result = ["status" => "error", "message" => "Both theme and adjective are required."];
    }
}

/**
 * Handle tweet posting.
 * This block processes the form submission for confirming and posting the tweet.
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'post_tweet') {
    $tweet_content = isset($_POST['tweet_content']) ? $_POST['tweet_content'] : null;

    if ($tweet_content) {
        $script_path = "../../python/post_tweet.py"; // Relative path
        $command = escapeshellcmd("python \"$script_path\" \"$tweet_content\"");
        $output = shell_exec($command);

        // Log the executed command and its output for debugging
        error_log("Command: $command");
        error_log("Output: $output");

        $result = json_decode($output, true);

        // Handle invalid JSON output
        if ($result === null) {
            error_log("Failed to decode JSON output from Python script.");
            error_log("Raw Output: $output");
            $result = ["status" => "error", "message" => "The operation was completed, but the response could not be parsed."];
        }
    } else {
        $result = ["status" => "error", "message" => "Tweet content is required."];
    }
}

/**
 * Handle scheduling tweets.
 * This block processes the form submission for scheduling tweets.
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'schedule_tweets') {
    $theme = isset($_POST['theme']) ? $_POST['theme'] : null;
    $interval = isset($_POST['interval']) ? $_POST['interval'] : null;

    if ($theme && $interval) {
        $script_path = "../../python/schedule_tweets.py"; // Relative path
        $command = escapeshellcmd("python \"$script_path\" \"$theme\" \"$interval\"");
        $output = shell_exec($command);

        // Log the executed command and its output for debugging
        printf("Command: $command");
        printf("Output: $output");

        $result = json_decode($output, true);

        // Handle invalid JSON output
        if ($result === null) {
            error_log("Failed to decode JSON output from Python script.");
            error_log("Raw Output: $output");
            $result = ["status" => "error", "message" => "The operation was completed, but the response could not be parsed."];
        } elseif ($result['status'] === 'success') {
            $generated_tweet = $result['message'];
        }
    } else {
        $result = ["status" => "error", "message" => "Both theme and interval are required."];
    }
}

// Debugging: Log the $result variable
if (isset($result)) {
    error_log("Result: " . print_r($result, true));
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
    <style>
        /* Vanilla modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }
        .modal-content {
            background-color: #fff;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 8px;
        }
        .modal-header, .modal-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .modal-header h5 {
            margin: 0;
        }
        .close {
            cursor: pointer;
            font-size: 1.5rem;
            font-weight: bold;
            border: none;
            background: none;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Bot Administration Panel</h1>
        <p class="text-center">Manage your bot's settings and tweets here.</p>

        <!-- Manual AI Tweet Section -->
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title">Generate a Manual Tweet</h5>
                <?php if (isset($result) && $result['status'] === 'error'): ?>
                    <div class="alert alert-danger">
                        <?php echo $result['message']; ?>
                    </div>
                <?php endif; ?>
                <form method="POST" action="">
                    <input type="hidden" name="action" value="generate_tweet">
                    <div class="mb-3">
                        <label for="theme" class="form-label">Theme:</label>
                        <input type="text" id="theme" name="theme" class="form-control" placeholder="Enter the theme (e.g., AI, technology)" required>
                    </div>
                    <div class="mb-3">
                        <label for="adjective" class="form-label">Adjective:</label>
                        <input type="text" id="adjective" name="adjective" class="form-control" placeholder="Enter the adjective (e.g., creative, funny)" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Generate Tweet</button>
                </form>
            </div>
        </div>

        <!-- AI Tweet Modal -->
        <?php if (isset($generated_tweet)): ?>
            <div id="tweetModal" class="modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5>Generated Tweet</h5>
                        <button class="close" onclick="closeModal()">×</button>
                    </div>
                    <div class="modal-body">
                        <p><?php echo htmlspecialchars($generated_tweet); ?></p>
                    </div>
                    <div class="modal-footer">
                        <form method="POST" action="">
                            <input type="hidden" name="action" value="post_tweet">
                            <input type="hidden" name="tweet_content" value="<?php echo htmlspecialchars($generated_tweet); ?>">
                            <button type="submit" class="btn btn-success">Post Tweet</button>
                        </form>
                        <button class="btn btn-secondary" onclick="closeModal()">Go Back</button>
                    </div>
                </div>
            </div>
            <script>
                // Automatically open the modal after form submission if the tweet is generated
                document.addEventListener('DOMContentLoaded', function () {
                    const modal = document.getElementById('tweetModal');
                    if (modal) {
                        modal.style.display = 'block';
                    }
                });

                // JavaScript to handle modal
                function closeModal() {
                    document.getElementById('tweetModal').style.display = 'none';
                }
            </script>
        <?php endif; ?>

        <!-- New Card for Scheduled Tweets -->
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title">Generate Random Scheduled Tweets</h5>
                <?php if (isset($result) && $result['status'] === 'error' && isset($_POST['action']) && $_POST['action'] === 'schedule_tweets'): ?>
                    <div class="alert alert-danger">
                        <?php echo $result['message']; ?>
                    </div>
                <?php endif; ?>
                <form method="POST" action="">
                    <input type="hidden" name="action" value="schedule_tweets">
                    <div class="mb-3">
                        <label for="schedule_theme" class="form-label">Theme:</label>
                        <input type="text" id="schedule_theme" name="theme" class="form-control" placeholder="Enter the theme (e.g., AI, sports)" required>
                    </div>
                    <div class="mb-3">
                        <label for="interval" class="form-label">Interval (minutes):</label>
                        <input type="number" id="interval" name="interval" class="form-control" placeholder="Enter interval in minutes" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Start Scheduling</button>
                </form>
            </div>
        </div>
        <!-- End of New Card -->

        <!-- Logout Button -->
        <div class="text-center mt-4">
            <a href="/avgFlask/php/public/logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>
</body>
</html>
