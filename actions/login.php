<?php
session_start();
include_once "../settings/connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

                    // Debugging: Print the retrieved row data
                    // echo "<pre>"; print_r($row); echo "</pre>"; exit();

                    // Verify the password
                    if (password_verify($password, $row['password'])) { // Ensure the column name matches your schema
                        // Set session variables
                        $_SESSION['id'] = $row['id'];
                        $_SESSION['fname'] = $row['first_name'];  // Ensure column names are consistent
                        $_SESSION['lname'] = $row['last_name'];

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
