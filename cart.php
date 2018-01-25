<?php

    require("common.php");

    //function for validation
    function valid_email($email) {
        return !!filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    //checking for product id to remove it from the cart array
    if (isset($_GET["id"]) && intval($_GET["id"])) {
        $id_prod = intval($_GET["id"]);
        $key = array_search($id_prod,$_SESSION["cart"]);
        if ($key !== false) {
            unset($_SESSION["cart"][$key]);
        }
        $_SESSION["cart"] = array_values($_SESSION["cart"]);
    }

    //getting all the data into an array which will be used to display the cart and to send the products through the mail
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

    //sending the mail with the products to the email Shop
    if (isset($_POST["submit"])) {

        $from = $_POST["email"];

        //validation for the email
        if (!valid_email($from)) {
            echo "Please add a valid e-mail address!";
        } else {
            $name = $_POST["coustomer_name"];
            $subject = "Order list";
            $message = "Coustomer ".$name." has the following order list: ";

            //composing the message with the products list
            foreach ($_SESSION["cart"] as $key => $value) {
                $message .= $_SESSION["products"][$_SESSION["cart"][$key]-1]["title"].", ";
            }

            $message .= "\n\n Additional information: "
            .$_POST["comments"];
            $headers = "From: ". $from;

            if (mail(SHOP_EMAIL,$subject,$message,$headers)) {

                //Releasing all the data from the cart as the mail was sent
                foreach ($_SESSION["cart"] as $key => $value) {
                    if ($key !== false) {
                        unset($_SESSION["cart"][$key]);
                    }
                    $_SESSION["cart"] = array_values($_SESSION["cart"]);
                }
                unset($_SESSION["cart"][0]);
                $_SESSION["cart"] = array_values($_SESSION["cart"]);
            }
        }
    }
 ?>
 <!DOCTYPE html PUBLIC>
    <html>
    <head>
        <title><?= t("Cart"); ?></title>
    </head>
    <body>
        <div id="container">
            <?= t('Language preference') .":" ; ?>
            <p><a href="cart.php?language=en"><?= t('English'); ?></a></p>
            <p><a href="cart.php?language=fr"><?= t('Francais'); ?></a></p>
            <?php if (count($_SESSION["cart"]) > 0): ?>
                <table>
                    <tr>
                        <th>     </th>
                        <th><?= t('Title'); ?></th>
                        <th><?= t('Description'); ?></th>
                        <th><?= t('Price'); ?></th>
                        <th> </th>
                    </tr>
                    <?php foreach ($_SESSION["cart"] as $key => $value): ?>
                        <tr>
                            <td><img width = "200" src = "Images/<?= $_SESSION["products"][$_SESSION["cart"][$key]-1]["img"]; ?>" alt = ""></td>
                            <td><?= $_SESSION["products"][$_SESSION["cart"][$key]-1]["title"]; ?></td>
                            <td><?= $_SESSION["products"][$_SESSION["cart"][$key]-1]["description"]; ?></td>
                            <td><?= $_SESSION["products"][$_SESSION["cart"][$key]-1]["price"]; ?></td>
                            <td><a href="cart.php?id=<?= $_SESSION["products"][$_SESSION["cart"][$key]-1]['id'] ?>"><?= t('Remove Item'); ?></a></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
            <?php if (count($_SESSION["cart"]) <= 0): ?>
                <?= t("You have not selected items yet!"); ?>
            <?php endif; ?>
            <p><a href="index.php"> INDEX </a></p>
        </div>
        <div id="order">
            <form action="cart.php" method="POST">
                <?= t('Name'); ?> <input type = "text" name = "coustomer_name" value=<?= isset($_POST["name"]) ? $_POST["name"] : ""; ?>><br>
                <?= t('Contact details'); ?> <input type = "text" name = "email" value=<?= isset($_POST["email"]) ? $_POST["email"] : ""; ?>><br>
                <?= t('Comments'); ?> <input type = "text" name = "comments" value=<?= isset($_POST["comments"]) ? $_POST["comments"] : ""; ?>><br>
                <input type = "submit" name= "submit" value = "Submit">
            </form>
        </div>
    </body>
    </html>
