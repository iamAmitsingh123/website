<?php
$server = "localhost";      // MySQL server (usually "localhost" for local development)
$username = "root";         // MySQL username
$password = "";             // MySQL password (leave empty for local development)
$database = "website";      // Database name

// Create a connection to the MySQL database
$conn = mysqli_connect($server, $username, $password, $database);

// Check the connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
