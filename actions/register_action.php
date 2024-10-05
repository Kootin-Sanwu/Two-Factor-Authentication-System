<?php
session_start();
include_once "../settings/connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errors = [];

    // Retrieve form inputs
    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Validate required fields
    if (empty($firstName) || empty($lastName) || empty($email) || empty($password) || empty($confirmPassword)) {
        header("Location: ../view/login.php?msg=All fields must be filled");
        exit();
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../view/login.php?msgPlease enter a valid email address.");
        exit();
    }

    // Validate password strength
    $passwordRegex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';
    if (!preg_match($passwordRegex, $password)) {
        header("Location: ../view/login.php?msg=Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one digit, and one special character.");
        exit();  // Stop further execution
    }

    // Check if passwords match
    if ($password !== $confirmPassword) {
        header("Location: ../view/login.php?msg=Passwords do not match.");
        exit();  // Stop further execution
    }

    // Check for existing email in the database
    $stmt = $conn->prepare("SELECT email FROM people WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        header("Location: ../view/login.php?msg=Email already exists. Please use a different email.");
        exit();  // Stop further execution
    }

    $stmt->close();

    // If no errors, proceed with user registration
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $role = 1;  // Default role is user (role = 1)

    // Prepare SQL statement for insertion
    $stmt = $conn->prepare("INSERT INTO people (first_name, last_name, email, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $firstName, $lastName, $email, $hashedPassword);
    $result = $stmt->execute();

    // Check if the query was successful
    if ($result) {
        header("Location: ../view/login.php?msg=Registration successful. You can now login.");
        exit();
    } else {
        header("Location: ../view/register.php?msg=Registration failed. Please try again later.");
        exit();
    }
}
