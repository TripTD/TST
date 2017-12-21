<?php
session_start();
require("phpScr/common.php");

function renderLogin($username,$password,$error)
{
  if(isset($_SESSION["logged"])&&$_SESSION["logged"][0]==$username&&$_SESSION["logged"][1]==$password)
  {
    header("Location: Products.php");
  }
?>
  <!DOCTYPE HTML PUBLIC>
  <html>
  <head>
     <title>LogIn</title>
  </head>
  <body>
    <?php
    if ($error != "")
    {
      echo '<div style="padding:4px; border:1px solid red; color:red;">'.$error.'</div>';
    }
    ?>
    <form action="" method="post">
      <div>
        <strong>UserName: </strong> <input type="text" name="username" value="<?php echo $username; ?>"/><br/>
        <strong>Password: </strong> <input type="text" name="password" value="<?php echo $password; ?>"/><br/>
        <p>* Required</p>
        <input type="submit" name="submit" value="Submit">
      </div>
    </form>
    </body>
    </html>
  <?php
}
  if(isset($_POST["submit"]))
  {
    $username = $logcon->real_escape_string($_POST["username"]);
    $password = $logcon->real_escape_string($_POST["password"]);
    $username = stripslashes($username);
    $password = stripslashes($password);
    if(empty($_POST["username"])||empty($_POST["password"]))
    {
      $error= "Please fill all the fields!";
      renderLogin($username,$password,$error);
    }
    else
    {
      if($log_query=$logcon->prepare("SELECT COUNT(*) AS USR_ROW FROM login WHERE user=? AND password=?"))
      {
        $log_query->bind_param('ss',$username,$password);
        $log_query->execute();
        $result=$log_query->get_result();
        $rows=$result->num_rows;
        $result->close();
        if($rows == 1)
        {
          $_SESSION["logged"]=array();
          $_SESSION["logged"][0]=$username;
          $_SESSION["logged"][1]=$password;
          header("Location: Products.php");
        }
        else {
            $error= "Username or Password is invalid";
            renderLogin($username,$password,$error);
        }
        $log_query->close();
      }
    }
  }
  else {
    if(!isset($_POST["submit"]))
    {
     renderLogin("","","");
    }
  }
 ?>
