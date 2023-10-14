<?php

include 'db_config.php'; // Add single quotes around the filename
session_start();

// Database connection parameters
//$servername = "localhost";
//$username = "root";
//$password = "root";

// Dynamically create the database name based on the current session user
// Get the username from the session
$sessionUsername = $_SESSION['username'];

// Define the database name using the session username as a prefix
$dbname = "user_data_$sessionUsername";

// Create a database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get a list of tables in the database
$tables = array();
$result = $conn->query("SHOW TABLES");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_row()) {
        $tables[] = $row[0];
    }
}

// Handle table selection
if (isset($_POST['selected_table'])) {
    $selectedTable = $_POST['selected_table'];

    // Query to select data from the dynamically created database and selected table
    $sql = "SELECT id, ticket_number, ip_address, panel_version, subject, status, solution FROM $selectedTable";

    $result = $conn->query($sql);
}

// Export functionality
if (isset($_POST['export_data'])) {
    if (isset($selectedTable) && $result->num_rows > 0) {
        // Create a CSV file for export
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="exported_data.csv"');

        $output = fopen('php://output', 'w');

        // Add headers to the CSV
        fputcsv($output, array('ID', 'Ticket ID', 'IP Address', 'Panel Version', 'Subject', 'Status', 'Solution'));

        // Fetch and export data to the CSV
        while ($row = $result->fetch_assoc()) {
            fputcsv($output, $row);
        }

        fclose($output);
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
        /* Inline CSS for demonstration purposes; it's better to keep this in an external CSS file */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f2f2f2;
        }

        header {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 20px;
        }

        main {
            max-width: 800px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #333;
            color: #fff;
        }

        select, input[type="submit"] {
            padding: 10px;
            margin-right: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .export-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }

        .export-button:hover {
            background-color: #45a049;
        }
  /* CSS styling for the button */
        #myButton {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <header>
        <h1>Database Viewer</h1>
    </header>
    <main>
        <form method="POST">
            <label for="selected_table">Select a Table:</label>
            <select id="selected_table" name="selected_table">
                <?php
                foreach ($tables as $table) {
                    echo "<option value='$table'>$table</option>";
                }
                ?>
            </select>
            <input type="submit" value="View Data">
        </form>

        <?php
        if (isset($selectedTable) && $result->num_rows > 0) {
            echo "<h2>Data from Table: $selectedTable</h2>";
            echo "<table>";
            echo "<tr><th>ID</th><th>Ticket ID</th><th>IP Address</th><th>Panel Version</th><th>Subject</th><th>Status</th><th>Solution</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . $row["ticket_number"] . "</td>";
                echo "<td>" . $row["ip_address"] . "</td>";
                echo "<td>" . $row["panel_version"] . "</td>";
                echo "<td>" . $row["subject"] . "</td>";
                echo "<td>" . $row["status"] . "</td>";
                echo "<td>" . $row["solution"] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } elseif (isset($selectedTable)) {
            echo "No data found in the selected table.";
        }
        ?>

 <?php
#if (isset($selectedTable) && $result->num_rows > 0) {
 #   // Add an Export button
 #   echo "<form method='GET' action='tabletopdf2.php'>";
 #   echo "<input type='hidden' name='selected_table' value='$selectedTable'>";
   # echo "<button class='export-button' type='submit'>Export</button>";
# echo "</form>";
if (isset($selectedTable) && $result->num_rows > 0) {
    // Add an Export button
    echo "<form method='GET' action='tabletopdf2.php' target='_blank'>";
    echo "<input type='hidden' name='selected_table' value='$selectedTable'>";
    echo "<button class='export-button' type='submit'>Export</button>";
    echo "</form>";
}

?>
    </main>

<button id="myButton">Send Mail</button>

<script>
    // JavaScript to trigger the PHP file when the button is clicked
    document.getElementById("myButton").addEventListener("click", function() {
        // Make an AJAX request to your PHP file
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "filesmtpmail.php", true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                // You can handle the response from the PHP file here
                alert("PHP file executed successfully!");
            }
        };
        xhr.send();
    });
</script>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
