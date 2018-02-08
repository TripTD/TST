<?php

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
        
    //Translate function
    function t($string, $langcode = NULL) {
        require("translations.php");

        //checking for language. if not setted  then make it default the english
        if (isset($_SESSION["langcode"])) {
            if (isset($_GET["language"])) {
                $_SESSION["langcode"] = $_GET["language"];
            }
        } else {
            $_SESSION["langcode"] = "en";
        }
        if ( isset($translation[$_SESSION["langcode"]][$string]) ) {
                $string = $translation[$_SESSION["langcode"]][$string];
        }
        return $string;
    }
