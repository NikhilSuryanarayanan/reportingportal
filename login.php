<?php
//include 'db_config.php';
session_start();

// Database connection parameters
$servername = "localhost";
$username = "reportuser";
$password = "XPW3Qh&vzLiP";
$dbname = "users";

// Create a database connection
$conn = new mysqli($servername, $username, $password, $dbname);

#if ($conn->connect_error) {
#    die("Connection failed: " . $conn->connect_error);
#}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Replace with the name of your user table and columns
    $sql = "SELECT * FROM user_list WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $stored_password = $row['password'];

        // Verify the password using password_verify (assuming you stored hashed passwords)
        if (password_verify($password, $stored_password)) {
            $_SESSION['username'] = $username;
            header("Location: submitreport.html"); // Redirect to the protected page
            exit();
        } else {
            $loginError = "Invalid password. Please try again.";
        }
    } else {
        $loginError = "Username not found. Please try again.";
    }
}

$conn->close();
?>
