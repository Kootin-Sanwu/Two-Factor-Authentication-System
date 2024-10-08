<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

include "../settings/connection.php";

// Include PHPMailer files
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require '../vendor/autoload.php';

if (isset($_GET['msg']) == "registering") {

    $firstName = $_SESSION['firstname'];
    $lastName = $_SESSION['lastname'];
    $email = $_SESSION['email'];
    $hashedPassword = $_SESSION['hashedPassword'];

    // Prepare SQL statement for insertion
    $stmt = $conn->prepare("INSERT INTO people (first_name, last_name, email, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $firstName, $lastName, $email, $hashedPassword);
    $result = $stmt->execute();

    if ($result) {
        $message = "Successfully registered";
        $encodedMessage = urlencode($message);
        header("Location: ../view/home.php?email=" . urlencode($email) . "&msg=" . $encodedMessage);
    } else {
        $message = "Failed to register";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $errors = [];

    // Retrieve form inputs
    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    $_SESSION['firstname'] = $firstName;
    $_SESSION['lastname'] = $lastName;
    $_SESSION['email'] = $email;
    $_SESSION['password'] = $password;
    $_SESSION['confirmPassword'] = $confirmPassword;
    $_SESSION['hashedPassword'] = $hashedPassword;

    // Validate required fields
    if (empty($firstName) || empty($lastName) || empty($email) || empty($password) || empty($confirmPassword)) {
        header("Location: ../view/login.php?msg=All fields must be filled");
        exit();
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../view/login.php?msg=Please enter a valid email address.");
        exit();
    }

    // Validate password strength
    $passwordRegex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';
    if (!preg_match($passwordRegex, $password)) {
        header("Location: ../view/login.php?msg=Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one digit, and one special character.");
        exit();
    }

    // Check if passwords match
    if ($password !== $confirmPassword) {
        header("Location: ../view/login.php?msg=Passwords do not match.");
        exit();
    }

    // Check for existing email in the database
    $stmt = $conn->prepare("SELECT email FROM people WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        header("Location: ../view/login.php?msg=Email already exists. Please use a different email.");
        echo $stmt->num_rows > 0;
        exit();
    }

    $stmt->close();

    // If no errors, proceed with user registration
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $_SESSION['hashedPassword'] = $hashedPassword;

    // Generate OTP
    $OTP = rand(100000, 999999);
    $registering = "registering";

    $_SESSION['OTP_timestamp'] = time();
    $_SESSION['registering'] = $registering;
    $_SESSION['signingIn'] = $signingIn;

    // Store OTP and email in session for verification later
    $_SESSION['OTP'] = $OTP;
    $_SESSION['email'] = $email;
    $_SESSION['registering'] = $registering;

    // Send OTP email
    sendOTP($email, $OTP);

    header("Location: ../view/verify_otp.php?msg=registering");
    exit();
}

// Function to send OTP via email using PHPMailer
function sendOTP($email, $OTP) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();                                        // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';                         // Specify main and backup SMTP servers
        $mail->SMTPAuth   = true;                               // Enable SMTP authentication
        $mail->Username   = 'kobekootinsanwu@gmail.com';        // Your Outlook email address
        $mail->Password   = 'jmvi iiki ugus zqnm';              // Your Outlook password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;     // Enable TLS encryption
        $mail->Port       = 587;                                // TCP port to connect to

        // Recipients
        $mail->setFrom('kobekootinsanwu@gmail.com', 'Two Factor Authentication Website'); // Your email address and name
        $mail->addAddress($email); // Add a recipient

        // Content
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = 'Your OTP Code';
        $mail->Body    = "Your One-Time Password (OTP) is <b>$OTP</b>. Please use this to complete your registration.";
        $mail->AltBody = "Your One-Time Password (OTP) is $OTP. Please use this to complete your registration.";

        $mail->send();
    } catch (Exception $e) {
        // Handle errors (optional)
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}