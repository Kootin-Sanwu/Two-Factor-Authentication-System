<?php
session_start();
include_once "../settings/connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errors = [];

    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $phoneNumber = $_POST['phoneNumber'];
    $dateOfBirth = $_POST['dateOfBirth'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $gender = isset($_POST['gender']) ? $_POST['gender'] : '';

    if (empty($firstName) || empty($lastName) || empty($email) || empty($phoneNumber) || empty($dateOfBirth) || empty($password) || empty($confirmPassword) || empty($gender)) {
        $errors[] = "Please fill in all the required fields.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    }

    $passwordRegex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';
    if (!preg_match($passwordRegex, $password)) {
        $errors[] = "Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one digit, and one special character.";
    }

    if ($password !== $confirmPassword) {
        $errors[] = "Passwords do not match.";
    }

    $ghanaPhoneRegex = '/^(?:(?:\+?233|233|0)(\d{9})|(?:233)(\d{9}))$/';
    if (!preg_match($ghanaPhoneRegex, $phoneNumber)) {
        $errors[] = "Please enter a valid Ghanaian phone number.";
    }

    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $role = 1;  // Set the role to 1

        $stmt = $conn->prepare("INSERT INTO users (fname, lname, email, phone_number, DOB, password_hash, gender, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssi", $firstName, $lastName, $email, $phoneNumber, $dateOfBirth, $hashedPassword, $gender, $role);
        $result = $stmt->execute();

        if ($result) {
            $_SESSION['message'] = [
                'type' => 'success',
                'title' => 'Success!',
                'text' => 'Registration successful. You can now login.'
            ];
            header("Location: ../login/login.php");
            exit();
        } else {
            $errors[] = "Registration failed. Please try again later.";
        }
    }

    $_SESSION['message'] = [
        'type' => 'error',
        'title' => 'Error!',
        'text' => implode("<br>", $errors)
    ];
    header("Location: ../register/register.php");
    exit();
} else {
    $_SESSION['message'] = [
        'type' => 'error',
        'title' => 'Error!',
        'text' => 'Invalid request method.'
    ];
    header("Location: ../register/register.php");
    exit();
}
?>
