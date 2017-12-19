<?php
    session_start();
    require("phpScr/common.php");
    function renderForm( $title, $description, $price, $error)
    {
?>
  <!DOCTYPE HTML PUBLIC>
  <html>
  <head>
     <title>Product</title>
  </head>
  <body>
    <?php
    if ($error != '')
    {
      echo '<div style="padding:4px; border:1px solid red; color:red;">'.$error.'</div>';
    }
    ?>
    <form action="" method="post">
      <div>
        <strong>Title: </strong> <input type="text" name="Title" value="<?php echo $title; ?>"/><br/>
        <strong>Description: </strong> <input type="text" name="Description" value="<?php echo $description; ?>"/><br/>
        <strong>Price: </strong> <input type="number" name="Price" value="<?php echo $price; ?>"/><br/>
        <p>* Required</p>
        <input type="submit" name="submit" value="Submit">
      </div>
    </form>
    </body>
    </html>
  <?php
  }
  if(isset($_POST["submit"])){
    if(isset($_GET["action"])&&$_GET["action"]=="edit")
    {
      $title=$conn->real_escape_string(htmlspecialchars($_POST["Title"]));
      $description=$conn->real_escape_string(htmlspecialchars($_POST["Description"]));
      $price=$conn->real_escape_string(htmlspecialchars($_POST["Price"]));
      if($title==""||$description==""||$price=="")
      {
        $error = "Please fill all the fields because they are required!";
        renderForm($title,$description,$price,$error);
      }
      else
     {
        $id_prod=intval($_GET["id"]);
        if($stmt=$conn->prepare("UPDATE MyItems SET title= ?,description= ?,price= ? WHERE id=?"))
        {
          $stmt->bind_param('ssss',$title,$description,$price,$id_prod);
          $stmt->execute();
          $stmt->close();
          header(" Location: Products.php");
        }
        else {
          echo "ERROR in prepare section UPDATE!";
        }
      }
    }
    if(isset($_GET["action"])&&$_GET["action"]=="insert")
    {
      $title=$conn->real_escape_string($_POST["Title"]);
      $description=$conn->real_escape_string(htmlspecialchars($_POST["Description"]));
      $price=$conn->real_escape_string(htmlspecialchars($_POST["Price"]));
      if($title==""||$description==""||$price=="")
      {
        $error = "Please fill all the fields because they are required!";
        renderForm($title,$description,$price,$error);
      }
      else
      {
        $result = $conn->query("SELECT COUNT(*) AS TOTALFOUND FROM MyItems");
        $row_array=$result->fetch_array(MYSQLI_ASSOC);
        $id_prod=$row_array["TOTALFOUND"]+1;
        $result->close();
        if($stmt=$conn->prepare("INSERT INTO MyItems (id,title,description,price) VALUES (?,?,?,?)"))
        {
          $stmt->bind_param("ssss",$id_prod,$title,$description,$price);
          $stmt->execute();
          $stmt->close();
          header(" Location: Products.php");
        }
        else {
          echo "ERROR in prepare section INSERT!";
        }
      }
      }
    }
    else
    {
    if(!isset($_POST["submit"]))
    {
      renderForm("","","","");
    }
  }
  ?>
