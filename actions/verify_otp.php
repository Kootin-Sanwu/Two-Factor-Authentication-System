<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userOTP = implode('', $_POST['OTP']); // Combine the array into a string
    $expectedOTP = $_SESSION['OTP'];
    $signingIn = $_SESSION['signingIn'];
    
    // echo $signingIn;
    // Retrieve the hidden message from the POST data
    $message_1 = $_POST['message'] ?? ''; // Simplified null check using null coalescing
    $message_1 = trim((string)$_POST['message']);

    // echo $message_1;

    $currentTime = time();

    // Ensure that the OTP stored in the session is treated as a string
    $userOTP = (string) $userOTP; // Cast to string, if necessary
    $expectedOTP = (string) $_SESSION['OTP']; // Cast to string, if necessary
    
    $OTP_time_created = $_SESSION['OTP_timestamp'];
    $overdueOTP = $currentTime - $OTP_time_created;

    if ($overdueOTP > 120) {
        header("Location: ../view/verify_otp.php?msg=OTP expired. Please try again.");
        exit();
    }

    // Debugging output
    // echo "Expected OTP: $expectedOTP<br>";
    // echo "User input OTP: $userOTP<br>";

    // Check if the user input OTP matches the expected OTP and the message is "Forgot Password"
    if ($userOTP === $expectedOTP && $message_1 === 'Forgot Password'&& $signingIn === 'signingIn') {
        echo $message_1; // This can be modified as needed
        header("Location: ../view/home.php?msg=" . urlencode($message_1)); // Use urlencode for safe URL
        exit();
    }

    else if ($userOTP === $expectedOTP && $message_1 === 'Forgot Password') {
        echo $message_1; // This can be modified as needed
        header("Location: ../view/reset_password.php?msg=" . urlencode($message_1)); // Use urlencode for safe URL
        exit();
    }

    // Check if the entered OTP matches the expected OTP (as a string)
    elseif ($userOTP === $expectedOTP) {
        unset($_SESSION['OTP']); // Clear OTP after successful verification
        echo $message;
        header("Location: ../view/home.php?msg=OTP verified successfully.");
        exit();
    }

    // If the OTP does not match
    header("Location: ../view/verify_otp.php?msg=Incorrect OTP. Please try again.");
    exit();
}
?>
