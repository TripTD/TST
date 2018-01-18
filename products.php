<?php
    session_start();
    require("common.php");

    if ( isset($_GET["action"]) && $_GET["action"] == "logout") {
            unset($_SESSION["logged"]);
    }
    if ( !isset($_SESSION["logged"])) {
        header("Location: LogIn.php");
    }
    if ( $_SESSION["logged"][0] != AP_USER && $_SESSION["logged"][1] != AP_PASSWORD) {
        header("Location: LogIn.php");
    }

    if (isset($_GET["l"]) && $_GET["l"] == "fr") {
         $_SESSION["translate"] = 1;
     }elseif ( $_SESSION["translate"] != 1) {
         $_SESSION["translate"] = 0;
     }
     if ( !isset($_SESSION["translate"])) {
         $language = LANG_ENGLISH;
     }
     if ( $_SESSION["translate"] == 1) {
         $language = LANG_FRENCH;
     } elseif ( $_SESSION["translate"] == 0) {
         $language = LANG_ENGLISH;
     }

    if ( isset($_GET["id"]) && $_GET["action"] == "remove") {
        $id_prod = intval($_GET["id"]);
        $id_prod = stripslashes($id_prod);
        if( $stmt = $conn->prepare("DELETE FROM products WHERE id=?")) {
            $stmt->bind_param("i",$id_prod);
            $stmt->execute();
            $stmt->close();
        }
    }

    $query = "SELECT id, title, description, price, imeg FROM products ORDER BY id";
    if($stmt = $conn->prepare($query)) {
        $stmt->execute();
        $result = $stmt->get_result();
    }
?>
<!DOCTYPE HTML PUBLIC>
    <html>
        <head>
            <title>Product List</title>
        </head>
        <body>
            <div id="container">
                <?php echo t('Language preference') .":" ; ?>
                <p><a href = "products.php"><?php echo t('English'); ?></a></p>
                <p><a href = "products.php?l=fr"><?php echo t('Francais'); ?></a></p>
                <div id="main">
                    <table>
                        <?php while ( $row = $result->fetch_array(MYSQLI_NUM)): ?>
                            <tr>
                                <td><img width = "200" src = "Images/<?php echo $row[4]; ?>" alt = ""></td>
                                <td><?php echo $row[1]; ?></td>
                                <td><?php echo $row[2]; ?></td>
                                <td><?php echo $row[3]; ?></td>
                                <td><a href="Product.php?page=products&action=edit&id=<?php echo $row[0] ?>"><?php echo t('Edit Item'); ?></a></td>
                                <td><a href="Products.php?page=products&action=remove&id=<?php echo $row[0] ?>"><?php echo t('Remove Item'); ?></a></td>
                            </tr>
                        <?php endwhile; ?>
                    </table>
                    <br>
                </div>
                <p><a href="Product.php?page=products&action=insert"><?php echo t('Add Item'); ?></a></p>
                <br>
                <p><a href="Products.php?page=products&action=logout"><?php echo t('Log out'); ?></a></p>
            </div>
        </body>
    </html>
