<?php

session_start();

$sessionUsername = $_SESSION['username'];

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

// Create a new PHPMailer instance
$mail = new PHPMailer();

try {
    // Enable verbose debugging output
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;

    // Set up SMTP
    $mail->isSMTP();
    $mail->Host = 'smtp.zoho.in'; // Replace with your SMTP server
    $mail->SMTPAuth = true;
    $mail->Username = 'nikhilsuryanarayanan@zohomail.in'; // Replace with your SMTP username
    $mail->Password = 'nikhilnewpass1234'; // Replace with your SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Use STARTTLS (secure)
    $mail->Port = 587; // Your SMTP port, can be 587 or 465

    // Sender and recipient
    $mail->setFrom('nikhilsuryanarayanan@zohomail.in', 'Nikhil');
    //$mail->addAddress('nikhil.suryanarayanan@hostingraja.in', 'wordoo');
    $mail->addAddress('saravana@hostingraja.in', 'saravana');
    $mail->addAddress('n4nikhilkana@gmail.com', 'wordoo');
    // Email subject and body
    $mail->Subject = 'EOD report';
    $mail->Body = 'EOD report';

    // Attach the PDF file from a specific directory
  //  $pdfFilePath = '/home/reportingportalb/public_html/portalstage1/reports/bala_01_10_2023.pdf'; // Replace with the actual file path
$filename = $sessionUsername . '_' . date('d_m_Y') . '.pdf';
$pdfFilePath = '/home/reportingportaln/public_html/reports/' . $filename;

    $mail->addAttachment($pdfFilePath);

    // Send the email
    $mail->send();
    echo 'Email with PDF attachment sent successfully';
} catch (Exception $e) {
    echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>
