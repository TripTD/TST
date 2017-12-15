<?php
    session_start();
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
    // if there are any errors, display them
    if ($error != '')
    {
      echo '<div style="padding:4px; border:1px solid red; color:red;">'.$error.'</div>';
    }
    ?>
    <form action="" method="post">
      <div>
        <strong>Title: *</strong> <input type="text" name="Title" value="<?php echo $title; ?>"/><br/>
        <strong>Description: *</strong> <input type="text" name="Description" value="<?php echo $description; ?>"/><br/>
        <strong>Price: *</strong> <input type="number" name="Price" value="<?php echo $price; ?>"/><br/>
        <p>* Required</p>
        <input type="submit" name="submit" value="Submit">
      </div>
    </form>
    </body>
    </html>
  <?php
  }
  if(!isset($_POST["Submit"]))
  {
    renderForm("","","","");
  }
  else
  {
    echo "GOT HERES!!!!";
    if(isset($_GET["action"])&&$_GET["action"]=="edit")
    {
      $title=mysql_real_escape_string(htmlspecialchars($_POST["Title"]));
      $description=mysql_real_escape_string(htmlspecialchars($_POST["Description"]));
      $price=mysql_real_escape_string(htmlspecialchars($_POST["Price"]));
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
          header("Location: Products.php");
        }
        else {
          echo "ERROR in prepare section UPDATE!";
        }
      }
    }
    if(isset($_GET["action"])&&$_GET["action"]=="insert")
    {
      echo "GOT through _GET insert and submit";
      $title=mysql_real_escape_string(htmlspecialchars($_POST["Title"]));
      $description=mysql_real_escape_string(htmlspecialchars($_POST["Description"]));
      $price=mysql_real_escape_string(htmlspecialchars($_POST["Price"]));
      if($title==""||$description==""||$price=="")
      {
        $error = "Please fill all the fields because they are required!";
        renderForm($title,$description,$price,$error);
      }
      else
      {
        echo "Got to the last!";
        $result = $conn->query("SELECT COUNT(*) AS TOTALFOUND FROM MyItems");
        $row_array=$result->fetch_array(MYSQLI_ASSOC);
        $id_prod=$row_array["TOTALFOUND"]+1;
        if($stmt=$conn->prepare("INSERT INTO MyItems (id,title,description,price) VALUES (?,?,?,?)"))
        {
          $stmt->bind_param("ssss",$id_prod,$title,$description,$price);
          $stmt->execute();
          $stmt->close();
          header("Location: Products.php");
        }
        else {
          echo "ERROR in prepare section INSERT!";
        }
      }
      }
    }
  ?>
