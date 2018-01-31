<?php

    session_start();
    require_once("config.php");

    //connecting to the database
    $conn = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);

    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    //Translate function
    function t($string, $langcode = NULL) {
        require("translations.php");

        //checking for language. if not setted  then make it default the english
        $langcode = isset($langcode) ? $langcode : isset($_GET["language"]) ? $_GET["language"] : "en" ;
        if ( isset($translation[$langcode][$string]) ) {
                $string = $translation[$langcode][$string];
        }
        return $string;
    }

?>
