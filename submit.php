<?php
//include 'db_config.php';
// Start a session (if not already started)
session_start();

// Check if the user is logged in, redirect if not
if (!isset($_SESSION['username'])) {
    header("Location: index.html"); // Redirect to the login page
    exit();
}
include 'db_config.php';
// Assuming you've defined database connection parameters
//$servername = "localhost";
//$username = "root";
//$password = "root";

// Create a connection to the database server
$conn = new mysqli($servername, $username, $password);

// Check the connection to the server
if ($conn->connect_error) {
    die("Connection to database server failed: " . $conn->connect_error);
}
// Get the username from the session
$sessionUsername = $_SESSION['username'];

// Define the database name using the session username as a prefix
$dbname = "user_data_$sessionUsername";

// Create a connection to the MySQL server
$conn = new mysqli($servername, $username, $password);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create the database if it doesn't exist
$sqlCreateDB = "CREATE DATABASE IF NOT EXISTS $dbname";
//if ($conn->query($sqlCreateDB) === TRUE) {
 //   echo "Database created or already exists successfully";
//} else {
 //   echo "Error creating database: " . $conn->error;
//}

if ($conn->query($sqlCreateDB) === TRUE) {
    // Do nothing or handle the success case in some other way
} else {
    echo "Error creating database: " . $conn->error;
}

// Switch to the specific user's database
$conn->select_db($dbname);

// Now, $conn is connected to the user's database

// Check the connection to the user's database
if ($conn->connect_error) {
    die("Connection to user's database failed: " . $conn->connect_error);
}

$dataSubmitted = false; // Initialize a variable to track whether data is submitted

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from the form
    $ticketNumber = $_POST['ticket_number'];
    $ipAddress = $_POST['ip_address'];
    $subject = $_POST['subject'];
    $status = $_POST['status'];
    $panelVersion = $_POST['panel_version'];
    $solution = $_POST['solution'];

    // Get the current date for table creation
    $currentDate = date("d_m_Y");

    // Define the table name with the date stamp
    $tableName = "report_$currentDate";

    // Check if the table already exists, and create it if not
    $createTableSQL = "CREATE TABLE IF NOT EXISTS $tableName (
        id INT AUTO_INCREMENT PRIMARY KEY,
        ticket_number VARCHAR(255) NOT NULL,
        ip_address VARCHAR(255) NOT NULL,
        subject VARCHAR(255) NOT NULL,
        status VARCHAR(255) NOT NULL,
        panel_version VARCHAR(255) NOT NULL,
        solution VARCHAR(255) NOT NULL
    )";

    if ($conn->query($createTableSQL) === TRUE) {
        // Insert data into the dynamically created table
        $insertDataSQL = "INSERT INTO $tableName (ticket_number, ip_address, subject, status, panel_version, solution) 
                VALUES ('$ticketNumber', '$ipAddress', '$subject', '$status', '$panelVersion', '$solution')";

        if ($conn->query($insertDataSQL) === TRUE) {
            $dataSubmitted = true; // Set the flag to true when data is successfully submitted
        } else {
            echo "Error inserting data: " . $conn->error;
        }
    } else {
        echo "Error creating table: " . $conn->error;
    }
}

// Close the connection to the user's database
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="sbmitresponce.css">
</head>
<body>
    <?php if ($dataSubmitted): ?>
        <div class="success-message">
            Data submitted successfully!
        </div>
        <script>
            setTimeout(function() {
                window.location.href = "submitreport.html";
            }, 500); // Redirect after 3 seconds (adjust the time as needed)
        </script>
    <?php endif; ?>
    <!-- Your form here -->
</body>
</html>
