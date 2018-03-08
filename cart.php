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
    if (!isset($products)) {
        $products = array();
    }

    $params = array(str_repeat('s', count($_SESSION["cart"])));

    foreach ($_SESSION["cart"] as $key => $value) {
        $params[] = &$_SESSION["cart"][$key];
    }

    $sql = "SELECT * FROM products WHERE id IN (" . str_repeat('?,', count($_SESSION['cart'])-1) . '?' . ")";
    if ($stmt = $conn->prepare($sql)) {
        call_user_func_array(array($stmt, "bind_param"), $params);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $products[] = $row;
        }
        $result->close();
        $stmt->close();
    }
}

//sending the mail with the products to the SHOP email
if (isset($_POST["submit"])) {
    $from = $_POST["email"];

    //validation for the email
    if(!filter_var($from, FILTER_VALIDATE_EMAIL)) {
        $form_message = t("Please add a valid e-mail adress!");
    } else {
        $name = $_POST["coustomer_name"];

        // setting the headers for html mail sending
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type: text/html;charset=iso-8859-1" . "\r\n";
        $headers .= "From: " . SHOP_EMAIL . "\r\n";
        $subject = t("Order list");

        $server_adr = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] ? 'https' : 'http') . '://' . $_SERVER['SERVER_NAME'] . '/' . strtok($_SERVER['PHP_SELF'], '/') . '/Images/';

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
        foreach ($products as $key => $session_id) {
            $message .= '
                <tr>
                       <td><img width="200" src="' . $server_adr . $session_id["img"] . '" alt=""></td>
                       <td>' . t("Product") . ': ' . $session_id["title"] . ' | </td>
                       <td>' . t("Description") . ': ' . $session_id["description"] . ' | </td>
                       <td>' . t("Price") . ': ' . $session_id["price"] . ' | </td>
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
            $products = array();
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
    <?php if (isset($products) && count($products) > 0): ?>
        <table>
            <tr>
                <th>     </th>
                <th><?= t('Title') ?></th>
                <th><?= t('Description') ?></th>
                <th><?= t('Price') ?></th>
                <th> </th>
            </tr>
            <?php foreach ($products as $key => $session_id): ?>
                <tr>
                    <td><img width="200" src="Images/<?= $session_id["img"] ?>" alt=""></td>
                    <td><?= $session_id["title"] ?></td>
                    <td><?= $session_id["description"] ?></td>
                    <td><?= $session_id["price"] ?></td>
                    <td><a href="cart.php?id=<?= $session_id['id'] ?>"><?= t('Remove Item') ?></a></td>
                </tr>
            <?php endforeach ?>
        </table>
    <?php else : ?>
        <?= t("You have not selected items yet!") ?>
    <?php endif ?>
    <p><a href="index.php"><?= t('INDEX') ?></a></p>
</div>
<div id="order">
    <?php if (isset($form_message)): ?>
        <p><?= $form_message ?></p>
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
