<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "avgflask";

try {
    // Create connection
    $conn = new mysqli($servername, $username, $password);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Create database if it doesn't exist
    $sql = "CREATE DATABASE IF NOT EXISTS $dbname";
    if ($conn->query($sql) === TRUE) {
        echo "Database created successfully or already exists.<br>";
    } else {
        die("Error creating database: " . $conn->error);
    }

    // Select the database
    $conn->select_db($dbname);

    // Create 'generated_tweets' table if it doesn't exist
    $sql = "CREATE TABLE IF NOT EXISTS generated_tweets (
        id INT AUTO_INCREMENT PRIMARY KEY,
        prompt TEXT NOT NULL,
        value TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    if ($conn->query($sql) === TRUE) {
        echo "Table 'generated_tweets' created successfully or already exists.<br>";
    } else {
        die("Error creating table 'generated_tweets': " . $conn->error);
    }

    // Create 'posted_tweets' table if it doesn't exist
    $sql = "CREATE TABLE IF NOT EXISTS posted_tweets (
        id INT AUTO_INCREMENT PRIMARY KEY,
        prompt TEXT NOT NULL,
        value TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    if ($conn->query($sql) === TRUE) {
        echo "Table 'posted_tweets' created successfully or already exists.<br>";
    } else {
        die("Error creating table 'posted_tweets': " . $conn->error);
    }

    // Close connection
    $conn->close();
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>
