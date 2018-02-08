<?php

    require("common.php");


    //checking for product id to remove it from the cart array
    if (isset($_GET["id"]) && intval($_GET["id"])) {
        $id_prod = intval($_GET["id"]);
        $cart_key = array_search($id_prod,$_SESSION["cart"]);
        if ($cart_key !== false) {
            unset($_SESSION["cart"][$cart_key]);
        }
        $_SESSION["cart"] = array_values($_SESSION["cart"]);
    }

    //if $_SESSION["cart"] is not empty get the data from the database
    if (count($_SESSION["cart"]) > 0) {

        //binding the parameters and selection part of the INDEX
        // type will be the data type for binding and params will be the reference array of the cart products
        if (!isset($product)) {
            $product = array();
        }
        
        $type = "";
        $params = array();
        
        foreach ($_SESSION["cart"]  as $key => $value) {
            $type .= "s";
            $params[] = & $value;
        }

        $sql = "SELECT * FROM products WHERE id IN (" . str_repeat('?,',count($_SESSION['cart'])-1) . '?' . ")";
        if ($stmt = $conn->prepare($sql)) {
            call_user_func_array(array($stmt,"bind_param"), array_merge(array($type),$params));
            $stmt->execute();
            $result = $stmt->get_result();
            $index = 0;
            while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                $product[$index]["id"] = $row["id"];
                $product[$index]["title"] = $row["title"];
                $product[$index]["description"] = $row["description"];
                $product[$index]["price"] = $row["price"];
                $product[$index]["img"] = $row["img"];
                $index++;
            }
            $result->close();
            $stmt->close();
        }
    }

    //sending the mail with the products to the SHOP email
    if (isset($_POST["submit"])) {
        $from = $_POST["email"];

        //validation for the email
        if(!boolval(filter_var($from, FILTER_VALIDATE_EMAIL))) {
            $form_message = t("Please add a valid e-mail adress!");
        } else {
            $name = $_POST["coustomer_name"];

            // setting the headers for html mail sending
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type: text/html;charset=iso-8859-1" . "\r\n";
            $headers .= "From: " . SHOP_EMAIL . "\r\n";
            $subject = t("Order list");
            
            $server_adr = strtok($_SERVER['SERVER_PROTOCOL'],'/') . '://' . $_SERVER['SERVER_NAME'] . '/' . strtok($_SERVER['PHP_SELF'],'/') . '/Images/';

            //composing the message with the products list
            $message = '
            <html>
            <head>
                <title>' . t("Ordered products") . '</title>
            </head>
            <body>
                <p>' . t("Products list desired by coustomer") . ':' . $name . '</p>
                <p>' . t("E-mail adress of the coustomer") . ':' . $from . '</p>
                <table>
                ';
            foreach ($product as $key => $value) {
                $message .= '
                <tr>
                       <td><img width="200" src="' . $server_adr . $value["img"] . '" alt=""></td>
                       <td>' . t("Product") . ': ' . $value["title"] . ' | </td>
                       <td>' . t("Description") . ': ' . $value["description"] . ' | </td>
                       <td>' . t("Price") . ': ' . $value["price"] . ' | </td>
                </tr>
                ';
            }
            $message .= '
                </table>
                <p>' . t("Additional information from the client") . ': ' . $_POST["comments"] . '</p>
            </body>
            </html>
            ';

            //sending the mail
            if (mail(SHOP_EMAIL,$subject,$message,$headers)) {

                //Releasing all the data from the cart as the mail was sent
                $_SESSION["cart"] = array();
            }
        }
    }

 ?>
 <!DOCTYPE html PUBLIC>
    <html>
    <head>
        <title><?= t("Cart") ?></title>
    </head>
    <body>
        <div id="container">
            <?= t('Language preference') ?> :
            <p><a href="cart.php?language=en"><?= t('English') ?></a></p>
            <p><a href="cart.php?language=fr"><?= t('Francais') ?></a></p>
            <?php if (count($_SESSION["cart"]) > 0): ?>
                <table>
                    <tr>
                        <th>     </th>
                        <th><?= t('Title') ?></th>
                        <th><?= t('Description') ?></th>
                        <th><?= t('Price') ?></th>
                        <th> </th>
                    </tr>
                    <?php foreach ($product as $key => $value): ?>
                           <tr>
                                  <td><img width="200" src="Images/<?= $value["img"] ?>" alt=""></td>
                                  <td><?= $value["title"] ?></td>
                                  <td><?= $value["description"] ?></td>
                                  <td><?= $value["price"] ?></td>
                                  <td><a href="cart.php?id=<?= $value['id'] ?>"><?= t('Remove Item') ?></a></td>
                           </tr>
                   <?php endforeach ?>
                </table>
            <?php else : ?>
                <?= t("You have not selected items yet!") ?>
            <?php endif ?>
            <p><a href="index.php"><?= t('INDEX') ?></a></p>
        </div>
        <div id="order">
            <?php if (isset($form_message) && $form_message != ""): ?>
                <p><?= $form_message ?>
            <?php endif ?>
            <form action="cart.php" method="POST">
                <?= t('Name') ?> <input type="text" name="coustomer_name" value=<?= isset($_POST["name"]) ? $_POST["name"] : "" ?>><br>
                <?= t('Contact details') ?> <input type="text" name="email" value=<?= isset($_POST["email"]) ? $_POST["email"] : "" ?>><br>
                <?= t('Comments') ?> <input type="text" name="comments" value=<?= isset($_POST["comments"]) ? $_POST["comments"] : "" ?>><br>
                <input type="submit" name="submit" value="<?= t('Check Out!') ?>">
            </form>
        </div>
    </body>
    </html>
