<?php
// Read DB credentials from environment when available (works for Docker, VPS, shared hosting)
$host = getenv('DB_HOST') ?: getenv('MYSQL_HOST') ?: 'localhost';
$user = getenv('DB_USER') ?: getenv('MYSQL_USER') ?: 'root';
$password = getenv('DB_PASSWORD') ?: getenv('MYSQL_PASSWORD') ?: '';
$database = getenv('DB_NAME') ?: getenv('MYSQL_DATABASE') ?: 'BookAuthorDB';

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    // In production you might want to log instead of die
    die("Connection failed: " . mysqli_connect_error());
}
?>
