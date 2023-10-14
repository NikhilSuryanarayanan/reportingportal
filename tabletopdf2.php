<?php
include 'db_config.php'; // Add single quotes around the filename

session_start();

require_once 'vendor/autoload.php'; // Include Composer autoloader
// Enable error reporting for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection settings
//$dbHost = 'localhost';
//$dbUser = 'root';
//$dbPass = 'root';

// Get the current session user (you need to implement this part)
$sessionUsername = $_SESSION['username'];

// Generate database name based on session user
//$dbname = "user_data_$sessionUsername";
// Generate table name based on current date (format: report_DD_MM_YYYY)
$currentDate = date('d_m_Y');
$tableName = "report_$currentDate";

// Create a new PDF instance
$pdf = new TCPDF();

// Add a page
$pdf->AddPage();

// Set font
$pdf->SetFont('helvetica', '', 12);

// Connect to the database
try {
    $db = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable PDO error mode
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Fetch data from the database table
$query = "SELECT id, ticket_number, ip_address, subject, status, panel_version, solution FROM $tableName";
try {
    $stmt = $db->prepare($query);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database query failed: " . $e->getMessage());
}

// Create a custom layout with aligned text and extended columns
foreach ($data as $row) {
    // ID (wider column)
    $pdf->Cell(30, 10, 'ID:', 1, 0, 'R');
    $pdf->Cell(0, 10, $row['id'], 1, 1, 'L');
    
    // Ticket Number (wider column)
    $pdf->Cell(30, 10, 'Ticket Number:', 1, 0, 'R');
    $pdf->Cell(0, 10, $row['ticket_number'], 1, 1, 'L');

    // IP Address (wider column)
    $pdf->Cell(40, 10, 'IP Address:', 1, 0, 'R'); // Adjust the width to make it wider
    $pdf->Cell(0, 10, $row['ip_address'], 1, 1, 'L');

    // Subject
    $pdf->Cell(20, 10, 'Subject:', 1, 0, 'R');
    $pdf->Cell(0, 10, $row['subject'], 1, 1, 'L');

    // Status
    $pdf->Cell(20, 10, 'Status:', 1, 0, 'R');
    $pdf->Cell(0, 10, $row['status'], 1, 1, 'L');

    // Panel Version (wider column)
    $pdf->Cell(30, 10, 'Panel Version:', 1, 0, 'R');
    $pdf->Cell(0, 10, $row['panel_version'], 1, 1, 'L');

    // Solution (extended to 8 lines)
    $pdf->Cell(20, 10, 'Solution:', 1, 0, 'R');
    $pdf->MultiCell(0, 10, $row['solution'], 1, 'L'); // Use MultiCell for multiple lines

    // Add dynamic spacing between records
    $pdf->Ln(); // This adds default spacing between rows
}

// Output the PDF to the browser or save it to a file
//$pdf->Output('table.pdf', 'I' , 'F'); // 'I' for inline display, 'F' to save to a file

// Generate the filename
$filename = $sessionUsername . '_' . date('d_m_Y') . '.pdf';

$pdf->Output('I');

// Output the PDF to the browser or save it to a file with the generated filename
//$pdf->Output($filename, 'I'); // 'I' for inline display
$pdf->Output('/home/reportingportaln/public_html/reports/' . $filename, 'F' , 'I');
// Close the database connection
$db = null;
?>
