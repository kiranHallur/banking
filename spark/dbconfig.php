<?php
$servername = "localhost";
$username = "root"; // replace with your own MySQL username
$password = ""; // replace with your own MySQL password
$dbname = "banking";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
