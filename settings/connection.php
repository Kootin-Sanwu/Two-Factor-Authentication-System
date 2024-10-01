<?php


$servername = "localhost";
$username = "";
$password = "";
$databasename = "authenticationDb";


$conn = new mysqli($servername, $username, $password,$databasename) ;
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


