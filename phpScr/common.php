<?php

// Declaring
$servername = "localhost";
$username = "root";
$password = "local123";
$dbname = "myDB";

// Connection
$conn = new mysqli($servername, $username, $password,$dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

 ?>
