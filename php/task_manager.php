<?php
use parallel\Runtime;

function startRandomTweetTask($interval, $tweet_count) {
    error_log("startRandomTweetTask called with interval: $interval, tweet_count: $tweet_count"); // Debug log

    $script_path = "../../python/schedule_tweets.py";
    $command = "python \"$script_path\"";

    $runtimes = [];
    for ($i = 0; $i < $tweet_count; $i++) {
        $runtime = new Runtime();
        $runtimes[] = $runtime;

        $runtime->run(function ($command, $interval, $i) {
            error_log("Executing command for tweet $i: $command");
            $output = shell_exec($command);
            error_log("Output for tweet $i: " . $output);

            // Wait for the specified interval
            if ($i < $interval - 1) {
                sleep($interval * 60);
            }
        }, [$command, $interval, $i]);
    }

    // Wait for all runtimes to complete
    foreach ($runtimes as $runtime) {
        $runtime->close();
    }

    return ["status" => "success", "message" => "Random tweet generation completed."];
}

function stopRandomTweetTask() {
    $command = "pkill -f schedule_tweets.py"; // Kill the Python script by name
    shell_exec($command);

    // Log the stop command for debugging
    error_log("Command: $command");
    return ["status" => "success", "message" => "Random tweet generation stopped."];
}
?>

