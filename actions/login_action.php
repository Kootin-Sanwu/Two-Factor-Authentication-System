<?php
session_start();
include_once "../settings/connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errors = [];

    // Check if email is provided and valid
    if (empty($_POST['email'])) {
        $errors[] = "Email is required.";
    } else {
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format.";
        }
    }

    // Check if password is provided
    if (empty($_POST['password'])) {
        $errors[] = "Password is required.";
    }

    // If there are no errors, proceed to validate user credentials
    if (empty($errors)) {
        $password = $_POST['password'];

        // Query to check user credentials
        $stmt = $conn->prepare("SELECT user_id, fname, lname, password_hash FROM users WHERE email=?");
        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();

            if (!$stmt) {
                $errors[] = "Database query error: " . $conn->error;
            } else {
                $result = $stmt->get_result();

                // If the user is found
                if ($result->num_rows == 1) {
                    $row = $result->fetch_assoc();

                    // Verify the password
                    if (password_verify($password, $row['password_hash'])) {
                        // Set session variables
                        $_SESSION['user_id'] = $row['user_id'];
                        $_SESSION['fname'] = $row['fname'];
                        $_SESSION['lname'] = $row['lname'];

                        // Redirect to otp.php for OTP verification
                        header("Location: ../otp/otp.php");
                        exit();
                    } else {
                        $errors[] = "Invalid email or password.";
                    }
                } else {
                    $errors[] = "Invalid email or password.";
                }
                $stmt->close();
            }
        } else {
            $errors[] = "Database query preparation error.";
        }
    }

    // Handle errors and redirect to the login page with error messages
    if (!empty($errors)) {
        $_SESSION['message'] = [
            'type' => 'error',
            'title' => 'Error!',
            'text' => implode("<br>", $errors)
        ];
        header("Location: ../login/login.php");
        exit();
    }
} else {
    // Invalid request method error handling
    $_SESSION['message'] = [
        'type' => 'error',
        'title' => 'Error!',
        'text' => 'Invalid request method.'
    ];
    header("Location: ../login/login.php");
    exit();
}
