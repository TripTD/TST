<?php
    session_start();
    require("phpScr/common.php");
    if(!isset($_SESSION["err"])) {
        $_SESSION["err"]="";
    }
    if(!isset($_SESSION["testarea"])) {
        $_SESSION["testarea"]=array();
    }
    if(!isset($title)) {
        $title="";
    }
    if(!isset($description)) {
        $description="";
    }
    if(!isset($price)) {
        $price="";
    }
    if (isset($_POST["submit"])) {
        if(isset($_GET["action"])&&$_GET["action"]=="edit") {
            if (isset($_FILES["img"])) {
                if(empty($_FILES["img"]["name"])) {
                    echo " You need to insert an image";
                } else {
                    $allowed = []"png","jpeg","jpg"];
                    $fl_name = $_FILES["img"]["name"];
                    $fl_extn = strtolower(end(explode('.',$fl_name)));
                    $fl_temp = $_FILES["img"]["tmp_name"];
                    if(in_array($fl_extn,$allowed)) {
                        img($fl_extn,$fl_temp);
                    } else {
                        echo "The extension is not valid!";
                    }
                }
            }
            $title = $conn -> real_escape_string(htmlspecialchars($_POST["Title"]));
            $description = $conn -> real_escape_string(htmlspecialchars($_POST["Description"]));
            $price = $conn -> real_escape_string(htmlspecialchars($_POST["Price"]));
            $id_prod = intval($_GET["id"]);
            $id_prod = stripslashes($id_prod);
            $ok=0;
            $query="SELECT title, description, price FROM MyItmes WHERE id=?";
            if($stmt=$conn->prepare($query)) {
                $stmt->bind_param('i',$id_prod);
                if($result = $conn->query($stmt)) {
                    while( $row= $result->fetch_row()) {
                        $x=$row[0];
                        $y=$row[1];
                        $z=$row[2];
                    }
                    $result->close();
                    $stmt->close();
                }
            }
            if($title="") {
                $title=$x;
            }
            if($description="") {
                $description=$y;
            }
            if($price="") {
                $price=$z;
            }
            if($stmt=$conn->prepare("UPDATE MyItems SET title= ?, description= ?, price= ? WHERE id= ?")) {
                $stmt ->bind_param('ssss',$title,$description,$price,$id_prod);
                $stmt ->execute();
                $stmt ->close();
                header("Location: Products.php");
            }
        }
        if(isset($_GET["action"])&&$_GET["action"]=="insert") {
            if (isset($_FILES["img"])) {
                if(empty($_FILES["img"]["name"])) {
                    echo " You need to insert an image";
                } else {
                    $allowed = []"png","jpeg","jpg"];
                    $fl_name = $_FILES["img"]["name"];
                    $fl_extn = strtolower(end(explode('.',$fl_name)));
                    $fl_temp = $_FILES["img"]["tmp_name"];
                    if(in_array($fl_extn,$allowed)) {
                        img($fl_extn,$fl_temp);
                    } else {
                        echo "The extension is not valid!";
                    }
                }
            }
            $title=$conn->real_escape_string($_POST["Title"]);
            $description=$conn->real_escape_string(htmlspecialchars($_POST["Description"]));
            $price=$conn->real_escape_string(htmlspecialchars($_POST["Price"]));
            if($title==""||$description==""||$price=="") {
                $_SESSION["err"]="Please fill all the fields";
                header("Location: Product.php");
            }
            else {
                $result = $conn->query("SELECT COUNT(*) AS TOTALFOUND FROM MyItems");
                $row_array=$result->fetch_array(MYSQLI_ASSOC);
                $id_next=$row_array["TOTALFOUND"]+1;
                $result->close();
                $ok=0;
                for( $a=0; $a < $id_next; $a++) {
                    if($a+1!=$_SESSION["evidence"][$a]) {
                        $ok =1;
                        $id_prod=$a+1;
                        break;
                    }
                }
                if( $ok==0) {
                    $id_prod=$id_next;
                }
                }
                if($stmt=$conn->prepare("INSERT INTO MyItems (id,title,description,price) VALUES (?,?,?,?)")) {
                    $stmt->bind_param("ssss",$id_prod,$title,$description,$price);
                    $stmt->execute();
                    $stmt->close();
                    header("Location: Products.php");
                }
            }
        }
?>
<!DOCTYPE HTML PUBLIC>
    <html>
        <head>
            <title> Product </title>
        </head>
        <body>
            <div id="sett">
                <?php if($_SESSION["err"]!="") { ?>
                    <p><?php echo $_SESSION["err"] ?></p>
                <?php }?>
                <?php if($_GET["action"]=="edit") { ?>
                    <p><?php echo "Product editing"?></p>
                <?php }?>
                <?php if($_GET["action"]=="insert") { ?>
                    <p><?php echo "Add a product"?></p>
                <?php }?>
                <form action="" method="post" enctype="multipart/form-data">
                    <div>
                        <strong>Title: </strong> <input type="text" name="Title" value="<?php echo $title; ?>"/><br/>
                        <strong>Description: </strong> <input type="text" name="Description" value="<?php echo $description; ?>"/><br/>
                        <strong>Price: </strong> <input type="number" name="Price" value="<?php echo $price; ?>"/><br/>
                        <strong>Image: </strong> <input type="file" name="img">
                        <input type="submit" name="submit" value="submit">
                    </div>
                </form>
            </div>
        </body>
    </html>
