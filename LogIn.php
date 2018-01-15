<?php
    session_start();
    require("phpScr/common.php");
    if(!isset($AP_USER)&&!isset($AP_PASSWORD)) {
        $AP_USER=AP_USER;
        $AP_PASSWORD=AP_PASSWORD;
    }
    if(!isset($_SESSION["error"])) {
        $_SESSION["error"]=0;
    }
    if(!isset($username)&&!isset($password)) {
        $username="";
        $password="";
    }
    if(isset($_SESSION["error"]) && $_SESSION["error"]>0) {
        echo '<p style="font-size:16px; color:red;"> WRONG CREDENTIALS! </p><br>';
        echo '<p style="font-size:16px; color:red;"> Try again! </p>';
    }
    if(isset($_SESSION["logged"])&&$_SESSION["logged"][0]==$username&&$_SESSION["logged"][1]==$password) {
        header("Location: Products.php");
    }
    if(isset($_POST["submit"]))    {
        $username = $_POST["username"];
        $password = $_POST["password"];
        if($username == $AP_USER && $password == $AP_PASSWORD) {
            $_SESSION["error"]=0;
            $_SESSION["logged"]=array();
            $_SESSION["logged"][0]=$username;
            $_SESSION["logged"][1]=$password;
            header("Location: Products.php");
        }
        else {
            $_SESSION["error"]++;
            header("Location: LogIn.php");
        }
    }
?>
<!DOCTYPE HTML PUBLIC>
    <html>
        <head>
            <title>LogIn</title>
        </head>
        <body>
            <?php echo translate("Goede dag dames en heren", "nl", "en"); ?>
            <form action="" method="post">
                <div>
                    <strong>UserName: </strong> <input type="text" name="username" value="<?php echo $username; ?>"/><br/>
                    <strong>Password: </strong> <input type="password" name="password" value="<?php echo $password; ?>"/><br/>
                    <input type="submit" name="submit" value="Submit">
                </div>
            </form>
            <p><a href="index.php"> Go to Market </a></p>
        </body>
    </html>
