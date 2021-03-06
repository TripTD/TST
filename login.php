<?php

require("common.php");

// $_SESSION["logged"] is used to see if the administrator is already logged in
// if it is then when accessing the login page he will be redirected to the products page
// also $_SESSION["logged"] is used to see if the person who tries to access products and product page is authenthicated
if (isset($_SESSION["logged"]) && $_SESSION["logged"]) {
    header("Location: Products.php");
    die;
}
//verifing if the username and password are good
if (isset($_POST["submit"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];
    if ($username == AP_USER && $password == AP_PASSWORD) {
        $_SESSION["logged"] = true;
        header("Location: products.php");
        die;
    } else {
        $form_message = t("WRONG CREDENTIALS! Please try again!");
    }
}

?>
<!DOCTYPE HTML PUBLIC>
<html>
<head>
    <title><?= t("Log in") ?></title>
</head>
<body>
<?= t('Language preference') ?> :
<p><a href="login.php?language=en"><?= t('English') ?></a></p>
<p><a href="login.php?language=fr"><?= t('Francais') ?></a></p>
<?php if (isset($form_message)): ?>
    <p><?= $form_message ?></p>
<?php endif ?>
<form action="" method="post">
    <div>
        <strong><?= t("Username") ?>: </strong> <input type="text" name="username" value =""/><br/>
        <strong><?= t("Password") ?>: </strong> <input type="password" name="password" value = ""/><br/>
        <input type="submit" name="submit" value="<?= t('Submit') ?>">
    </div>
</form>
<p><a href="index.php"><?= t("Go to Market") ?></a></p>
</body>
</html>
