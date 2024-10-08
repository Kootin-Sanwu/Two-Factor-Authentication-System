<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
// session_start();

// // Include PHPMailer files
// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;
// use PHPMailer\PHPMailer\SMTP;

// include_once "../settings/connection.php"; // Make sure this connection is valid
// require '../vendor/autoload.php';

// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     // Retrieve email from session or POST request
//     $email = $_SESSION['email'] ?? trim($_POST['email']); // Use session if available
//     $errors = [];

//     // Retrieve the hidden message from the POST data
//     $message = $_POST['message'] ?? '';

//     // Check if the message indicates a password reset
//     if ($message === 'Forgot Password') {

//         // Generate OTP
//         $OTP = rand(100000, 999999);
        
//         // Store OTP and email in session for verification later
//         $_SESSION['OTP'] = $OTP;
//         $_SESSION['email'] = $email;
//         $_SESSION['OTP_timestamp'] = time();

//         // Send OTP email
//         sendOTP($email, $OTP);
                        
//         header("Location: ../view/verify_otp.php?msg=" . $message);
//     } elseif ($message === 'New Password') {
//         // Ensure the new password and confirmation are provided
//         if (!empty($_POST['password']) && !empty($_POST['confirm_password'])) {
//             $password = trim($_POST['password']);
//             $confirmPassword = trim($_POST['confirm_password']);

//             // Check password strength
//             $passwordRegex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';
//             if (!preg_match($passwordRegex, $password)) {
//                 header("Location: ../view/reset_password.php?msg=Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one digit, and one special character.");
//                 exit();
//             }

//             // Check if passwords match
//             if ($password !== $confirmPassword) {
//                 header("Location: ../view/reset_password.php?msg=Passwords do not match.");
//                 exit();
//             }

//             // Hash the new password
//             $hashedPassword = password_hash($confirmPassword, PASSWORD_DEFAULT);

//             // Debug: Check if email and hashed password are set correctly
//             echo "Email: " . $email . "<br>";
//             echo "Hashed Password: " . $hashedPassword . "<br>";

//             // Update the password in the database
//             if ($stmt = $conn->prepare("UPDATE people SET password = ? WHERE email = ?")) {
//                 $stmt->bind_param("ss", $hashedPassword, $email);
//                 if ($stmt->execute()) {
//                     // Success: Password updated
//                     echo "Password updated successfully!";
//                     header("Location: ../view/login.php?msg=Password updated successfully.");
//                     exit();
//                 } else {
//                     // Error executing statement
//                     echo "Error updating password: " . $stmt->error;
//                 }
//                 $stmt->close();
//             } else {
//                 // Error preparing statement
//                 echo "Error preparing statement: " . $conn->error;
//             }
//         } else {
//             echo "New password or confirmation not provided.";
//         }
//     } else {
//         echo "No password reset request received.";
//     }
// } else if (isset($_GET['message1']) && isset($_GET['message2']) && isset($_GET['email'])) {
    
//     $message1 = $_GET['message1'];
//     $message2 = $_GET['message2'];
//     echo "Message 1: " . htmlspecialchars($message1);
//     echo "";
//     echo "Message 2: " . htmlspecialchars($message2);
    
//     // Generate OTP
//     $OTP = rand(100000, 999999);
    
//     // Store OTP and email in session for verification later
//     $_SESSION['OTP'] = $OTP;    
//     $OTP = $_SESSION['OTP'];
//     $email = filter_var($_GET['email'], FILTER_SANITIZE_EMAIL);
//     $_SESSION['OTP_timestamp'] = time();

//     echo $email;
//     echo "Is there a problem?";
//     echo $OTP;

//     // Send OTP email
//     sendOTP($email, $OTP);
                    
//     header("Location: ../view/verify_otp.php?msg=Forgot Password&msg2=A code has been sent to your email address");
// }




















ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

// Include PHPMailer files
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

include_once "../settings/connection.php"; // Ensure this connection file is valid
require '../vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve email from session or POST request
    $email = $_SESSION['email'] ?? trim($_POST['email']); // Use session if available
    $errors = [];
    
    // Retrieve the hidden message from the POST data
    $message = $_POST['message'] ?? '';
    echo $message;
    echo $email;

    // Check if the message indicates a password reset
    if ($message === 'Forgot Password') {
        // Check if the email exists in the database
        $stmt = $conn->prepare("SELECT email FROM people WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Email exists, proceed to generate OTP

            // Generate OTP
            $OTP = rand(100000, 999999);

            // Store OTP and email in session for verification later
            $_SESSION['OTP'] = $OTP;
            $_SESSION['email'] = $email;
            $_SESSION['OTP_timestamp'] = time();

            // Send OTP email
            sendOTP($email, $OTP);
                            
            header("Location: ../view/verify_otp.php?msg=" . urlencode($message));
            exit();
        } else {
            // Email does not exist, return error
            header("Location: ../view/forgot_password.php?msg=Email not found.");
            // header("Location: ../view/forgot_password.php?msg=" . urlencode("Email not found."));
            exit();
        }

        $stmt->close();
    } elseif ($message === 'New Password') {
        // Ensure the new password and confirmation are provided
        if (!empty($_POST['password']) && !empty($_POST['confirm_password'])) {
            $password = trim($_POST['password']);
            $confirmPassword = trim($_POST['confirm_password']);

            // Check password strength
            $passwordRegex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';
            if (!preg_match($passwordRegex, $password)) {
                header("Location: ../view/reset_password.php?msg=Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one digit, and one special character.");
                exit();
            }

            // Check if passwords match
            if ($password !== $confirmPassword) {
                header("Location: ../view/reset_password.php?msg=Passwords do not match.");
                exit();
            }

            // Hash the new password
            $hashedPassword = password_hash($confirmPassword, PASSWORD_DEFAULT);

            // Update the password in the database
            if ($stmt = $conn->prepare("UPDATE people SET password = ? WHERE email = ?")) {
                $stmt->bind_param("ss", $hashedPassword, $email);
                if ($stmt->execute()) {
                    // Success: Password updated
                    header("Location: ../view/login.php?msg=Password updated successfully.");
                    exit();
                } else {
                    // Error executing statement
                    echo "Error updating password: " . $stmt->error;
                }
                $stmt->close();
            } else {
                // Error preparing statement
                echo "Error preparing statement: " . $conn->error;
            }
        } else {
            echo "New password or confirmation not provided.";
        }
    } else {
        echo "No password reset request received.";
    }
} else if (isset($_GET['message1']) && isset($_GET['message2']) && isset($_GET['email'])) {
    
    $message1 = $_GET['message1'];
    $message2 = $_GET['message2'];

    // Generate OTP
    $OTP = rand(100000, 999999);
    
    // Store OTP and email in session for verification later
    $_SESSION['OTP'] = $OTP;    
    $_SESSION['OTP_timestamp'] = time();
    $email = filter_var($_GET['email'], FILTER_SANITIZE_EMAIL);
    
    // Send OTP email
    sendOTP($email, $OTP);
                    
    header("Location: ../view/verify_otp.php?msg=Forgot Password&msg2=A code has been sent to your email address");
}

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
