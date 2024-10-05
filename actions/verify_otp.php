<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userOTP = implode('', $_POST['OTP']); // Combine the array into a string
    $expectedOTP = $_SESSION['OTP'];

    // // Ensure that the OTP stored in the session is treated as a string
    $userOTP = (string) $userOTP; // Cast to string, if necessary
    $expectedOTP = (string) $_SESSION['OTP']; // Cast to string, if necessary
    
    echo "Expected OTP $expectedOTP";
    echo "User input OTP $userOTP";

    // Check if the entered OTP matches the expected OTP (as a string)
    if ($userOTP === $expectedOTP) { // Compare as strings
        unset($_SESSION['OTP']); // Clear OTP after successful verification
        header("Location: ../view/home.php?msg=OTP verified successfully.");
        exit();
    } else {
        header("Location: ../view/verify_otp.php?msg=Incorrect OTP. Please try again.");
        exit();
    }
}
?>