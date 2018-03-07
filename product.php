<?php

require("common.php");

if (!isset($_SESSION["logged"])){
    header("Location: login.php");
    die;
}

// in case we have an edit the information on the form will be the same as on the database to make it easier to editing
//in case we have an insertion the information on the form will be blanks
if (isset($_GET["id"])) {
    $id_prod = intval($_GET["id"]);

    $sql = "SELECT * FROM products WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id_prod);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $title = $row["title"];
            $description = $row["description"];
            $price = $row["price"];
            $image = $row["img"];
        }
        $stmt->close();
        $result->close();
    }
} else {
    $title = "";
    $description = "";
    $price = "";
    $image = "";
}

$allowed = ["image/png", "image/jpeg"];
$prefix = time() . '_';

//editing and insertion operations after hitting the submit
if (isset($_POST["submit"])) {

    //edit part
    if (isset($_GET["id"])) {
        //file upload
        if (isset($_FILES["img"])) {
            if (!empty($_FILES["img"]["name"]) && !$_FILES["img"]['error']) {
                if (in_array($_FILES["img"]['type'], $allowed)) {
                    $image = $prefix . $_FILES["img"]["name"];
                    $file_path = 'Images/' . $image;
                    move_uploaded_file($_FILES["img"]["tmp_name"], $file_path);
                } else {
                    $form_message_image =  t("The extension is not valid!");
                }
            }
        }

        //taking data from the form
        $title = htmlspecialchars($_POST["Title"]);
        $description = htmlspecialchars($_POST["Description"]);
        $price = htmlspecialchars($_POST["Price"]);
        $id_prod = intval($_GET["id"]);

        //checking if the data is different from the initial one, if it is the values to be updated are the ones from the form
        //preparing the update and execute the query
        if ($stmt = $conn->prepare("UPDATE products SET title = ?, description = ?, price = ?, img = ? WHERE id = ?")) {
            $stmt ->bind_param('sssss', $title, $description, $price, $image, $id_prod);
            $stmt ->execute();
            $stmt ->close();
            header("Location: products.php");
            die;
        }
    } else {
        //insert part

        //taking the data from the form
        $title = htmlspecialchars($_POST["Title"]);
        $description = htmlspecialchars($_POST["Description"]);
        $price = htmlspecialchars($_POST["Price"]);

        //checking to see if all the fields are good
        if ($title == "" || $description == "" || $price == "") {
            $form_message = t("Please fill all the fields");
        } else {

            //checking the image upload
            if (isset($_FILES["img"])) {
                if (empty($_FILES["img"]["name"])) {
                    $form_message = t("You need to insert an image");
                } else {
                    if (in_array($_FILES["img"]['type'], $allowed)) {
                        $image = $prefix . $_FILES["img"]["name"];
                        $file_path = 'Images/' . $image;
                        move_uploaded_file($_FILES["img"]["tmp_name"], $file_path);
                    } else {
                        $form_message_image =  t("The extension is not valid!");
                    }
                }
            }

            //inserting the new item into the database
            $sql = "INSERT INTO products (title, description, price, img) VALUES (?, ?, ?, ?)";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("ssss", $title, $description, $price, $image);
                $stmt->execute();
                $stmt->close();
                header("Location: products.php");
                die;
            }
        }
    }
}


?>
<!DOCTYPE HTML PUBLIC>
<html>
<head>
    <title><?= t("Product") ?></title>
</head>
<body>
<div id="sett">
    <?php if (isset($form_message)): ?>
        <p><?= $form_message ?></p>
    <?php endif ?>
    <?php if (isset($form_message_image)): ?>
        <p><?= $form_message_image ?></p>
    <?php endif ?>
    <form method="post" enctype="multipart/form-data">
        <strong><?= t('Title') ?> </strong> <input type="text" name="Title" value="<?= $title ?>"/><br/>
        <strong><?= t('Description') ?> </strong> <input type="text" name="Description" value="<?= $description ?>"/><br/>
        <strong><?= t('Price') ?> </strong> <input type="number" name="Price" value="<?= $price ?>"/><br/>
        <strong><?= t('Image') ?> </strong> <input type="file" name="img">
        <input type="submit" name="submit" value="<?= t('Submit') ?>">
    </form>
</div>
</body>
</html>
