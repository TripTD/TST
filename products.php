<?php

    require("common.php");

    //if the user who tries to reach this page is not authenthicated then return him to login
    if (!isset($_SESSION["logged"]) && $_SESSION["logged"][0] != AP_USER && $_SESSION["logged"][1] != AP_PASSWORD) {
        header("Location: login.php");
    }
    //clearing the $_SESSION["logged"] from held data in case of logout
    if (isset($_GET["action"]) && $_GET["action"] == "logout") {
            unset($_SESSION["logged"]);
            header("Location: index.php");
    }
    //getting all the data into an array which will be used to display the database content
    if (!isset($_SESSION["products"])) {
        $_SESSION["products"] = array();
        $sql = "SELECT * FROM products";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->execute();
            $result = $stmt->get_result();
            $index_prod = 0;
            while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                $_SESSION["products"][$index_prod]["id"] = $row["id"];
                $_SESSION["products"][$index_prod]["title"] = $row["title"];
                $_SESSION["products"][$index_prod]["description"] = $row["description"];
                $_SESSION["products"][$index_prod]["price"] = $row["price"];
                $_SESSION["products"][$index_prod]["img"] = $row["img"];
                $index_prod++;
            }
            $result->close();
            $stmt->close();
        }
    }

    //checking for the action remove and id to delete an item from the database
    if (isset($_GET["id"]) && $_GET["action"] == "remove") {
        $id_prod = intval($_GET["id"]);
        $id_prod = stripslashes($id_prod);
        if( $stmt = $conn->prepare("DELETE FROM products WHERE id=?")) {
            $stmt->bind_param("i",$id_prod);
            $stmt->execute();
            $stmt->close();
        }
    }
?>
<!DOCTYPE HTML PUBLIC>
    <html>
    <head>
        <title><?= t("Products"); ?></title>
    </head>
    <body>
        <div id="container">
            <?= t('Language preference') .":" ; ?>
            <p><a href="products.php?language=en"><?= t('English'); ?></a></p>
            <p><a href="products.php?language=fr"><?= t('Francais'); ?></a></p>
            <table>
                <?php foreach ($_SESSION["products"] as $key => $value): ?>
                    <tr>
                        <td><img width="200" src="Images/<?= $_SESSION["products"][$key]["img"]; ?>" alt=""></td>
                        <td><?= $_SESSION["products"][$key]["title"]; ?></td>
                        <td><?= $_SESSION["products"][$key]["description"]; ?></td>
                        <td><?= $_SESSION["products"][$key]["price"]; ?></td>
                        <td><a href="product.php?action=edit&id=<?php echo $_SESSION['products'][$key]['id']; ?>"><?= t("Edit Item"); ?></a></td>
                        <td><a href="products.php?action=remove&id=<?php echo $_SESSION['products'][$key]['id']; ?>"><?= t("Remove Item"); ?></a></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <div id="opt">
            <p><a href="product.php?action=insert"><?= t('Add Item'); ?></a></p>
            <br>
            <p><a href="products.php?action=logout"><?= t('Log out'); ?></a></p>
        </div>
    </body>
    </html>
