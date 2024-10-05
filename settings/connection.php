<?php

$servername = "localhost";
$username = "root";  // Default username for XAMPP
$password = "";      // Default password is usually empty for XAMPP
$databasename = "authenticationdb";

// Create connection
$conn = new mysqli($servername, $username, $password, $databasename);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
