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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $errors = [];

    // Validate email
    if (empty($_POST['email'])) {
        $errors[] = "Email is required.";
    } else {
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format.";
        }
    }

    // Validate password
    if (empty($_POST['password'])) {
        $errors[] = "Password is required.";
    }

    if (empty($errors)) {
        $password = $_POST['password'];

        // Prepare the SQL query
        $stmt = $conn->prepare("SELECT id, first_name, last_name, password FROM people WHERE email=?");
        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();

            // Check for execution errors
            if (!$stmt) {
                $errors[] = "Database query error: " . $conn->error;
            } else {
                $result = $stmt->get_result();

                // If the user is found
                if ($result->num_rows == 1) {
                    $row = $result->fetch_assoc();

                    // Verify the password
                    if (password_verify($password, $row['password'])) {
                        // Set session variables
                        $_SESSION['id'] = $row['id'];
                        $_SESSION['fname'] = $row['first_name'];
                        $_SESSION['lname'] = $row['last_name'];

                        // Generate OTP
                        $OTP = rand(100000, 999999);
                        $signingIn = "signingIn";
                        echo $signingIn;
                
                        // Store OTP and email in session for verification later
                        $_SESSION['OTP'] = $OTP;
                        $_SESSION['email'] = $email;
                        $_SESSION['signingIn'] = $signingIn;
                        $_SESSION['OTP_timestamp'] = time();


                        // Send OTP email
                        sendOTP($email, $OTP);

                        // After sending the OTP
                        $message = "Successfully signed in. Kindly check your email for the OTP.";
                        $encodedMessage = urlencode($message);
                        header("Location: ../view/verify_otp.php?email=" . urlencode($email) . "&msg=" . $encodedMessage);
                        exit();
                
                        // Redirect to OTP verification page
                        header("Location: ../view/verify_otp.php");
                        exit();
                    } else {
                        header("Location: ../view/login.php?msg=Invalid email or password.");
                        exit();
                    }
                } else {
                    header("Location: ../view/login.php?msg=Invalid email or password.");
                    exit();
                }
                $stmt->close();
            }
        } else {
            header("Location: ../view/login.php?msg=Database query preparation error.");
            exit();
        }
    } else {
        // If there are validation errors, concatenate them and pass via URL
        header("Location: ../view/login.php?msg=" . urlencode(implode(" ", $errors)));
        exit();
    }
} else {
    // Handle invalid request method
    header("Location: ../view/login.php?msg=Invalid request method.");
    exit();
}

// Function to send OTP via email using PHPMailer
function sendOTP($email, $OTP) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();                                            
        $mail->Host = 'smtp.gmail.com';                         
        $mail->SMTPAuth   = true;                                 
        $mail->Username   = 'kobekootinsanwu@gmail.com';             
        $mail->Password   = 'jmvi iiki ugus zqnm';              
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;      
        $mail->Port       = 587;                                  

        // Recipients
        $mail->setFrom('kobekootinsanwu@gmail.com', 'Two Factor Authentication Website');
        $mail->addAddress($email);                                

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Your OTP Code';
        $mail->Body    = "Your One-Time Password (OTP) is <b>$OTP</b>. Please use this to complete your registration.";
        $mail->AltBody = "Your One-Time Password (OTP) is $OTP. Please use this to complete your registration.";

        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}