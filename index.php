<?php
        require("common.php");

        //Initializing the cart array
        if (!isset($_SESSION["cart"])) {
                $_SESSION["cart"] = array();
        }

        //checking for product id to store it in the cart array
        if (isset($_GET["id"]) && intval($_GET["id"])) {
                $id_prod = intval( $_GET["id"]);
                if (!in_array($id_prod, $_SESSION["cart"])) {
                    $_SESSION["cart"][] = $id_prod;
                }
        }

        //preparing the sql statement and the parameters for binding
        if (!count($_SESSION["cart"])) {

            $sql = "SELECT * FROM products";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->execute();
                $result = $stmt->get_result();
            }
        } else {

            //binding the parameters and selection part of the INDEX
            // type will be the data type for binding and params will be the reference array of the cart products
            if (!isset($type) && !isset($params)) {
                $type = "";
                $params = array();
            }

            for ($i = 0; $i < count($_SESSION["cart"]); $i++) {
                $type .= "s";
                $params[] = & $_SESSION["cart"][$i];
            }
            
            $sql = "SELECT * FROM products WHERE id NOT IN (".str_repeat('?,',count($_SESSION['cart'])-1).'?'.")";
            if ($stmt = $conn->prepare($sql)) {
                call_user_func_array(array($stmt,"bind_param"), array_merge(array($type),$params));
                $stmt->execute();
                $result = $stmt->get_result();
            }
        }
 ?>
 <!DOCTYPE html PUBLIC>
       <html >
             <head>
                 <title><?= t("Shopping"); ?></title>
             </head>
       <body>
            <div id="container">
                <?= t('Language preference') .":" ; ?>
                <p><a href="index.php?language=en"><?= t('English'); ?></a></p>
                <p><a href="index.php?language=fr"><?= t('Francais'); ?></a></p>
                 <div id="main">
                     <table>
                        <?php while ($row = $result->fetch_array(MYSQLI_ASSOC)): ?>
                               <tr>
                                      <td><img width="200" src="Images/<?= $row["img"]; ?>" alt=""></td>
                                      <td><?= $row["title"]; ?></td>
                                      <td><?= $row["description"]; ?></td>
                                      <td><?= $row["price"]; ?></td>
                                      <td><a href="index.php?id=<?= $row['id'] ?>"><?= t('Add Item'); ?></a></td>
                               </tr>
                       <?php endwhile; ?>
                     </table>
                     <br>
                </div>
                <p><a href="cart.php"><?= t('Go to Cart'); ?></a></p>
            </div>
            <?php if (!isset($_SESSION["logged"])): ?>
                <p><a href="login.php"><?= t('Log in'); ?></a></p>
            <?php endif; ?>
       </body>
       </html>
