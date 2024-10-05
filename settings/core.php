<?php
session_start();

function checkLogin() {
    if (!isset($_SESSION['id'])) {
        header("Location: ../login/login.php");
        die();
    }

    
}

checkLogin();
?>
