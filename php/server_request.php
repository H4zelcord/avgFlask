<?php

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "avgflask";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

/**
 * Handle manual tweet generation.
 * This block processes the form submission for generating the AI tweet.
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'generate_tweet') {
    $theme = isset($_POST['theme']) ? $_POST['theme'] : null;
    $adjective = isset($_POST['adjective']) ? $_POST['adjective'] : null;

    if ($theme && $adjective) {
        $script_path = "../../python/generate_tweet.py"; // Changed to relative path
        $command = escapeshellcmd("python \"$script_path\" \"$theme\" \"$adjective\"");
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
        } elseif ($result['status'] === 'success') {
            $generated_tweet = $result['message'];

            // Insert into generated_tweets table
            $stmt = $conn->prepare("INSERT INTO generated_tweets (prompt, value) VALUES (?, ?)");
            $prompt = "Theme: $theme, Adjective: $adjective";
            $stmt->bind_param("ss", $prompt, $generated_tweet);
            $stmt->execute();
            $stmt->close();
        }
    } else {
        $result = ["status" => "error", "message" => "Both theme and adjective are required."];
    }
}

/**
 * Handle final tweet posting.
 * This block processes the form submission for confirming and posting the tweet.
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'post_tweet') {
    $tweet_content = isset($_POST['tweet_content']) ? $_POST['tweet_content'] : null;

    if ($tweet_content) {
        $script_path = "../../python/post_tweet.py"; // Changed to relative path
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
        } elseif ($result['status'] === 'success') {
            // Insert into posted_tweets table
            $stmt = $conn->prepare("INSERT INTO posted_tweets (prompt, value) VALUES (?, ?)");
            $prompt = "Posted Tweet";
            $stmt->bind_param("ss", $prompt, $tweet_content);
            $stmt->execute();
            $stmt->close();
        }
    } else {
        $result = ["status" => "error", "message" => "Tweet content is required."];
    }
}

/**
 * Handle direct AI prompt.
 * This block processes the form submission for directly prompting the AI.
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'direct_prompt') {
    $ai_prompt = isset($_POST['ai_prompt']) ? $_POST['ai_prompt'] : null;

    if ($ai_prompt) {
        $script_path = "../../python/direct_tweet.py"; // Reuse the generate_tweet script
        $command = escapeshellcmd("python \"$script_path\" \"$ai_prompt\"");
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
        } elseif ($result['status'] === 'success') {
            $generated_tweet = $result['message'];

            // Insert into generated_tweets table
            $stmt = $conn->prepare("INSERT INTO generated_tweets (prompt, value) VALUES (?, ?)");
            $stmt->bind_param("ss", $ai_prompt, $generated_tweet);
            $stmt->execute();
            $stmt->close();
        }
    } else {
        $result = ["status" => "error", "message" => "AI prompt is required."];
    }
}

// Close the database connection
$conn->close();

/*

/**
 * Handle random tweet generation.
 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'generate_random_tweet') {
    $interval = isset($_POST['interval']) ? intval($_POST['interval']) : null;
    $tweet_count = isset($_POST['tweet_count']) ? intval($_POST['tweet_count']) : null;

    if ($interval && $tweet_count) {
        $result = startRandomTweetTask($interval, $tweet_count);
    } else {
        $result = ["status" => "error", "message" => "Both interval and tweet count are required."];
    }
}

/**
 * Handle stopping the random tweet generation task.

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'stop_random_tweet') {
    $result = stopRandomTweetTask();
}

// Debugging: Log the $result variable
if (isset($result)) {
    error_log("Result: " . print_r($result, true));
}

*/
?>
