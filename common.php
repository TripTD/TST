<?php

    session_start();
    require_once("config.php");

    //connecting to the database
    $conn = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);

    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    //Making default language English
    if (!isset($_SESSION["translate"])) {
        $_SESSION["translate"] = LANG_ENGLISH;
    }

    //used when someone tries to change the language on some page
    if (isset($_GET["l"]) && $_GET["l"] == "fr") {
        $_SESSION["translate"] = LANG_FRENCH;
    } elseif (isset($_GET["l"]) && $_GET["l"] == "en") {
        $_SESSION["translate"] = LANG_ENGLISH;
    }

    //Translate function
    function t($string, $args = array(), $langcode = NULL) {
        require("translations.php");
        $langcode = isset($langcode) ? $langcode : $_SESSION["translate"];
        if ( isset($translation[$langcode][$string]) ) {
                $string = $translation[$langcode][$string];
        }
        return $string;
    }

?>
