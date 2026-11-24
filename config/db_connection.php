<?php
// Database configuration
$servername = getenv("DB_HOST") ?: "localhost"; 
$username = getenv("DB_USER"); 
$password = getenv("DB_PASSWORD"); 
$dbname = getenv("DB_NAME") ?: "urban_shoes"; 

// Create connection
if ($username === false || $password === false) {
    http_response_code(503);
    exit("Database configuration unavailable.");
}

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    http_response_code(503);
    exit("Database connection unavailable.");
} 
?>
