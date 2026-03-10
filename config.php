<?php
// Read DB credentials from environment when available (Docker, VPS, shared hosting)
// Support multiple env names for portability and provide safe defaults for local Docker Compose
$host = getenv('DB_HOST') ?: getenv('MYSQL_HOST') ?: 'localhost';
$user = getenv('DB_USER') ?: getenv('MYSQL_USER') ?: 'root';
$password = getenv('DB_PASSWORD') ?: getenv('MYSQL_PASSWORD') ?: '';
$database = getenv('DB_NAME') ?: getenv('MYSQL_DATABASE') ?: 'BookAuthorDB';

// Optional base URL for generating absolute links (not required if using relative links)
$BASE_URL = getenv('BASE_URL') ?: '';

// Establish connection
$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    // In production you might want to log instead of die
    die("Connection failed: " . mysqli_connect_error());
}
?>
