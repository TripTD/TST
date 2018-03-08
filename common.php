<?php

//test p2
session_start();
require_once("config.php");

//connecting to the database
$conn = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//Initializing the cart array
if (!isset($_SESSION["cart"])) {
    $_SESSION["cart"] = array();
}

if (!isset($_SESSION["language"])) {
    $_SESSION["language"] = "en";
}
if (isset($_GET["language"])) {
    $_SESSION["language"] = $_GET["language"];
}

require_once("translations.php");

//Translate function
function t($string, $langcode = NULL) {
    global $translations;

    //checking for language. if not set  then make it default the english
    if ( isset($translations[$_SESSION["language"]][$string]) ) {
        $string = $translations[$_SESSION["language"]][$string];
    }

    return $string;
}
