<?php
session_start(); // Start the PHP session

$servername = "localhost";
$username = "reportuser";
$password = "XPW3Qh&vzLiP";

// Assuming you have a session variable named 'current_user' to store the current user's name
$sessionUsername = $_SESSION['username'];

// Create a database connection with a dynamic database name
$dbname = "user_data_$sessionUsername";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
