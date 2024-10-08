<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userOTP = implode('', $_POST['OTP']); // Combine the array into a string
    $expectedOTP = $_SESSION['OTP'];
    $signingIn = $_SESSION['signingIn'];
    $registering = $_SESSION['registering'];

    // Retrieve the hidden message from the POST data
    $message_1 = $_POST['message'] ?? '';
    $message_1 = trim((string)$_POST['message']);
    $registering = trim((string)$_SESSION['registering']);
    
    $currentTime = time();

    // Ensure that the OTP stored in the session is treated as a string
    $userOTP = (string) $userOTP;
    $expectedOTP = (string) $_SESSION['OTP'];
    
    $OTP_time_created = $_SESSION['OTP_timestamp'];
    $overdueOTP = $currentTime - $OTP_time_created;

    if ($overdueOTP > 120) {
        header("Location: ../view/verify_otp.php?msg=OTP expired. Please try again.");
        exit();
    }

    // Check if the user input OTP matches the expected OTP and the message is "Forgot Password"
    if ($userOTP === $expectedOTP && $message_1 === 'Forgot Password'&& $signingIn === 'signingIn') {
        unset($_SESSION['OTP']);
        header("Location: ../view/home.php?msg=" . urlencode($message_1)); // Use urlencode for safe URL
        exit();
    }

    else if ($userOTP === $expectedOTP && $message_1 === 'Forgot Password'&& $registering === 'registering'){
        unset($_SESSION['OTP']);
        header("Location: ../view/home.php?msg=" . urlencode($message_1));
        exit();
    }

    else if ($userOTP === $expectedOTP && $message_1 === 'Forgot Password') {
        unset($_SESSION['OTP']);
        header("Location: ../view/reset_password.php?msg=" . urlencode($message_1));
        exit();
    }

    else if ($userOTP === $expectedOTP && $message_1 === $registering) {
        unset($_SESSION['OTP']);
        header("Location: ../actions/register.php?msg=" . urlencode($message_1));
        exit();
    }

    // Check if the entered OTP matches the expected OTP (as a string)
    else if ($userOTP === $expectedOTP) {
        unset($_SESSION['OTP']);
        header("Location: ../view/home.php?msg=OTP verified successfully.");
        exit();
    }

    // If the OTP does not match
    header("Location: ../view/verify_otp.php?msg=Incorrect OTP. Please try again.");
    exit();
}
?>
