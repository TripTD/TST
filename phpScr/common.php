<?php

// Declaring
$servername = "localhost";
$username = "root";
$password = "local123";
$dbname = "myDB";
$logdb="logindb";

// Connection
$conn = new mysqli($servername, $username, $password,$dbname);
$logcon = new mysqli($servername,$username,$password,$logdb);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if($logcon->connect_error)
{
  die("Connection to login Database failed: " . $logcon->connect_error);
}

 ?>
