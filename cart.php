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
        if (!isset($type) && !isset($params)) {
            $type = "";
            $params = array();
        }

        for ($i = 0; $i < count($_SESSION["cart"]); $i++) {
            $type .= "s";
            $params[] = & $_SESSION["cart"][$i];
        }

        $sql = "SELECT * FROM products WHERE id IN (".str_repeat('?,',count($_SESSION['cart'])-1).'?'.")";
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
            echo t("Please add a valid e-mail adress!");
        } else {
            $name = $_POST["coustomer_name"];

            // setting the headers for html mail sending
            $headers = "MIME-Version: 1.0"."\r\n";
            $headers .= "Content-type: text/html;charset=iso-8859-1"."\r\n";
            $subject = "Order list";

            //composing the message with the products list
            $message = "
            <html>
            <head>
                <title> Ordered products </title>
            </head>
            <body>
                <p> Products list desired by coustomer:".$name."</p>
                <p> E-mail adress of the coustomer:".$from."</p>
                <table>
                ";
            foreach ($product as $key => $value) {
                $message .= "
                <tr>
                       <td><img width='200' src='http://localhost/TST1/Images/'".$product[$key]['img']." alt=''></td>
                       <td>Product: ".$product[$key]['title']." | </td>
                       <td>Description: ".$product[$key]['description']." | </td>
                       <td>Price: ".$product[$key]['price']." | </td>
                </tr>
                ";
            }
            $message .= "
                </table>
                <p> Additional information from the client :".$_POST['comments']."</p>
            </body>
            </html>
            ";

            //sending the mail
            if (mail(SHOP_EMAIL,$subject,$message,$headers)) {

                //Releasing all the data from the cart as the mail was sent
                foreach ($_SESSION["cart"] as $key => $value) {
                    if ($key !== false) {
                        unset($_SESSION["cart"][$key]);
                    }
                    $_SESSION["cart"] = array_values($_SESSION["cart"]);
                }
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
                    <?php foreach ($product as $key => $value): ?>
                           <tr>
                                  <td><img width="200" src="Images/<?= $product[$key]["img"]; ?>" alt=""></td>
                                  <td><?= $product[$key]["title"]; ?></td>
                                  <td><?= $product[$key]["description"]; ?></td>
                                  <td><?= $product[$key]["price"]; ?></td>
                                  <td><a href="cart.php?id=<?= $product[$key]['id'] ?>"><?= t('Remove Item'); ?></a></td>
                           </tr>
                   <?php endforeach; ?>
                </table>
            <?php endif; ?>
            <?php if (!count($_SESSION["cart"])): ?>
                <?= t("You have not selected items yet!"); ?>
            <?php endif; ?>
            <p><a href="index.php"> INDEX </a></p>
        </div>
        <div id="order">
            <form action="cart.php" method="POST">
                <?= t('Name'); ?> <input type="text" name="coustomer_name" value=<?= isset($_POST["name"]) ? $_POST["name"] : ""; ?>><br>
                <?= t('Contact details'); ?> <input type="text" name="email" value=<?= isset($_POST["email"]) ? $_POST["email"] : ""; ?>><br>
                <?= t('Comments'); ?> <input type="text" name="comments" value=<?= isset($_POST["comments"]) ? $_POST["comments"] : ""; ?>><br>
                <input type="submit" name="submit" value="Check Out!">
            </form>
        </div>
    </body>
    </html>
