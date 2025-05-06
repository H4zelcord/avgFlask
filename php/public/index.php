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

require __DIR__ . '/../../vendor/autoload.php'; // Ensure ReactPHP is autoloaded

use React\EventLoop\Factory;
use React\ChildProcess\Process;

require_once __DIR__ . '/../task_manager.php'; // Updated path for task_manager.php
require_once __DIR__ . '/../server_request.php'; // Include server_request.php

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bot Administration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/avgFlask/php/public/styles/styles.css"> <!-- Updated path for styles.css -->
    <script src="/avgFlask/php/scripts/main.js" defer></script> <!-- Updated path for main.js -->
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Bot Administration Panel</h1>
        <p class="text-center">Manage your bot's settings and tweets here.</p>

        <!-- Card Grid Section -->
        <div class="row row-cols-1 row-cols-md-2 g-4">
            <!-- Manual AI Tweet Section -->
            <div class="col">
                <div class="card h-100">
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
            </div>

            <!-- Generate Random Tweet Section -->
            <div class="col">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Generate Random Tweet</h5>
                        <div class="mb-3">
                            <label for="interval" class="form-label">Interval (minutes):</label>
                            <input type="number" id="interval" name="interval" class="form-control" placeholder="Enter interval in minutes" required>
                        </div>
                        <div class="mb-3">
                            <label for="tweet_count" class="form-label">Number of Tweets:</label>
                            <input type="number" id="tweet_count" name="tweet_count" class="form-control" placeholder="Enter the number of tweets to post" required>
                        </div>
                        <button onclick="startRandomTweet()" class="btn btn-primary w-100">Generate Random Tweet</button>
                        <button onclick="stopRandomTweet()" class="btn btn-danger w-100 mt-2">Stop Random Tweet</button>
                    </div>
                </div>
            </div>

            <!-- Directly Prompt the AI Section -->
            <div class="col">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Directly Prompt the AI</h5>
                        <form method="POST" action="">
                            <input type="hidden" name="action" value="direct_prompt">
                            <div class="mb-3">
                                <label for="ai_prompt" class="form-label">Enter your prompt:</label>
                                <textarea id="ai_prompt" name="ai_prompt" class="form-control" rows="5" placeholder="Type your full prompt for the AI here..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Submit Prompt</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Placeholder for Modal -->
            <div class="col">
                <div id="modalContainer" class="h-100"></div>
            </div>
        </div>
        <!-- End of Card Grid Section -->

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
        <?php endif; ?> 
        
        <!-- Logout Button -->
        <div class="text-center mt-4">
            <a href="/avgFlask/php/public/logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>

    <?php
        var_dump(json_encode($_POST))
    ?>
</body>
</html>
