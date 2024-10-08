<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

// Include PHPMailer files
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

include_once "../settings/connection.php";
require '../vendor/autoload.php';

if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    $errors = [];

    // Generate OTP
    $OTP = rand(100000, 999999);
        
    // Store OTP and email in session for verification later
    $_SESSION['OTP'] = $OTP;
    $_SESSION['email'] = $email;
    $_SESSION['OTP_timestamp'] = time();

    // Send OTP email
    sendOTP($email, $OTP);             
    header("Location: ../view/verify_otp.php?msg=" . $message);
}
/* Adjust font size for smaller inputs */

// Function to send OTP via email using PHPMailer
function sendOTP($email, $OTP) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Specify SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'kobekootinsanwu@gmail.com'; // Outlook email address
        $mail->Password = 'jmvi iiki ugus zqnm'; // Outlook password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('kobekootinsanwu@gmail.com', 'Two Factor Authentication Website');
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Your OTP Code';
        $mail->Body = "Your One-Time Password (OTP) is <b>$OTP</b>. Please use this to complete your registration.";
        $mail->AltBody = "Your One-Time Password (OTP) is $OTP. Please use this to complete your registration.";

        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}