<?php

    require("common.php");

    // $_SESSION["logged"] is used to see if the administrator is already logged in
    // if it is then when accessing the login page he will be redirected to the products page
    // also $_SESSION["logged"] is used to see if the person who tries to access products and product page is authenthicated
    if (isset($_SESSION["logged"]) && $_SESSION["logged"][0] == AP_USER && $_SESSION["logged"][1] == AP_PASSWORD) {
        header("Location: Products.php");
    }

    //verifing if the username and password are good
    if (isset($_POST["submit"]))    {
        $username = $_POST["username"];
        $password = $_POST["password"];
        if ($username == AP_USER && $password == AP_PASSWORD) {
            $_SESSION["logged"] = array();
            $_SESSION["logged"][0] = $username;
            $_SESSION["logged"][1] = $password;
            header("Location: products.php");
        }
        else {
            echo t("WRONG CREDENTIALS! Please try again!");
        }
    }
?>
<!DOCTYPE HTML PUBLIC>
    <html>
        <head>
            <title><?= t("Log in"); ?></title>
        </head>
        <body>
            <?= t('Language preference') .":" ; ?>
            <p><a href="login.php?language=en"><?= t('English'); ?></a></p>
            <p><a href="login.php?language=fr"><?= t('Francais'); ?></a></p>
            <form action="" method="post">
                <div>
                    <strong><?= t("Username"); ?>: </strong> <input type="text" name="username" value =""/><br/>
                    <strong><?= t("Password"); ?>: </strong> <input type="password" name="password" value = ""/><br/>
                    <input type="submit" name="submit" value="Submit">
                </div>
            </form>
            <p><a href="index.php"><?= t("Go to Market"); ?></a></p>
        </body>
    </html>
