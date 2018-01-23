<?php

    require("common.php");

    // in case we have an edit the information on the form will be the same as on the database to make it easier to editing
    //in case we have an insertion the information on the form will be blanks
    if (!isset($title) || !isset($description) || !isset($price)) {
        if (isset($_GET["action"]) && $_GET["action"] == "edit") {
            $id_prod = intval($_GET["id"]);
            $id_prod = stripslashes($id_prod);
            $id_prod--;

            $title = $_SESSION["products"][$id_prod]["title"];
            $description = $_SESSION["products"][$id_prod]["description"];
            $price = $_SESSION["products"][$id_prod]["price"];
            $imaj = $_SESSION["products"][$id_prod]["imeg"];
        } elseif (isset($_GET["action"]) && $_GET["action"] == "insert") {
            $title = "";
            $description = "";
            $price = "";
            $imaj = "";
        }
    }

    //editing and insertion operations after hitting the submit
    if (isset($_POST["submit"])) {

        //edit part
        if (isset($_GET["action"]) && $_GET["action"] == "edit") {
            //file upload
            if (isset($_FILES["img"])) {
                if (!empty($_FILES["img"]["name"])) {
                    $allowed = ["png","jpeg","jpg"];
                    $fl_name = $_FILES["img"]["name"];
                    $tmp = explode('.',$fl_name);
                    $fl_extn = end($tmp);
                    $fl_temp = $_FILES["img"]["tmp_name"];
                    if (in_array($fl_extn,$allowed)) {
                        $file_path = 'Images/'.$fl_extn;
                        move_uploaded_file($fl_temp,$file_path);
                    } else {
                        echo "The extension is not valid!";
                    }
                }
            }

            //taking data from the form
            $title_0 = $conn->real_escape_string(htmlspecialchars($_POST["Title"]));
            $description_0 = $conn->real_escape_string(htmlspecialchars($_POST["Description"]));
            $price_0 = $conn->real_escape_string(htmlspecialchars($_POST["Price"]));
            $id_prod = intval($_GET["id"]);
            $id_prod = stripslashes($id_prod);

            //checking if the data is different from the initial one, if it is the values to be updated are the ones from the form
            if ( $title_0 != $title && $title_0 != "") {
                $title = $title_0;
            }
            if ( $description_0 != $description && $description_0 != "") {
                $description = $description_0;
            }
            if ( $price_0 != $price && $price_0 != "") {
                $price = $price_0;
            }
            if ( $imaj != $fl_name) {
                $imaj = $fl_name;
            }

            //preparing the update and execute the query
            if ($stmt = $conn->prepare("UPDATE products SET title = ?, description = ?, price = ?, imeg = ? WHERE id = ?")) {
                $stmt ->bind_param('sssss',$title,$description,$price,$imaj,$id_prod);
                $stmt ->execute();
                $stmt ->close();
                header("Location: Products.php");
            }
        }

        //insert part
        if (isset($_GET["action"]) && $_GET["action"] == "insert") {

            //taking the data from the form
            $title = $conn->real_escape_string(htmlspecialchars($_POST["Title"]));
            $description = $conn->real_escape_string(htmlspecialchars($_POST["Description"]));
            $price = $conn->real_escape_string(htmlspecialchars($_POST["Price"]));

            //checking to see if all the fields are good
            if ($title == "" || $description == "" || $price == "") {
                echo t("Please fill all the fields");
            } else {

                //checking the image upload
                if (isset($_FILES["img"])) {
                    if (empty($_FILES["img"]["name"])) {
                        echo t("You need to insert an image");
                    } else {
                        $allowed = ["png","jpeg","jpg"];
                        $fl_name = $_FILES["img"]["name"];
                        $tmp = (explode('.',$fl_name));
                        $fl_extn = end($tmp);
                        $fl_temp = $_FILES["img"]["tmp_name"];
                        if (in_array($fl_extn,$allowed)) {
                            $file_path = 'Images/'.$fl_extn;
                            move_uploaded_file($fl_temp,$file_path);
                        } else {
                            echo t("The extension is not valid!");
                        }
                    }
                }

                //inserting the new item into the database
                $sql = "INSERT INTO products (title,description,price,imeg) VALUES (?,?,?,?)";
                if ($stmt = $conn->prepare($sql)) {
                    $stmt->bind_param("ssss",$title,$description,$price,$fl_name);
                    $stmt->execute();
                    $stmt->close();
                    header("Location: Products.php");
                }
            }
        }
    }
 ?>
 <!DOCTYPE HTML PUBLIC>
    <html>
    <head>
        <title><?= t("Product"); ?></title>
    </head>
    <body>
        <div id="sett">
            <form action="" method="post" enctype="multipart/form-data">
                <strong><?= t('Title'); ?> </strong> <input type = "text" name = "Title" value = "<?= $title; ?>"/><br/>
                <strong><?= t('Description'); ?> </strong> <input type = "text" name = "Description" value = "<?= $description; ?>"/><br/>
                <strong><?= t('Price'); ?> </strong> <input type = "number" name = "Price" value = "<?= $price; ?>"/><br/>
                <strong><?= t('Image'); ?> </strong> <input type = "file" name = "img">
                <input type = "submit" name = "submit" value = "submit">
            </form>
        </div>
    </body>
    </html>
