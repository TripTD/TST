<?php
    session_start();
    require("common.php");

    if ( !isset($mailerror)) {
        $mailerror = "";
    }
    if ( isset($_POST["submit"])) {
        foreach ( $_SESSION["cart"] as $key => $value) {
            $_SESSION["prod_list"] .= $_SESSION["cart"][$key]." ";
        }

        $to = "dmn_caesar@yahoo.com";
        $from = $_POST["email"];
        if ( !filter_var($from, FILTER_VALIDATE_EMAIL)) {
            $mailerror = "Please add a valid email adress!";
            header("Location: cart.php");
        }

        $name = $_POST["coustomer_name"];
        $subject = "Order list";
        $message = "Coustomer ".$name." has the following order list: ".$_SESSION["prod_list"]."\n\n Additional information: ".$_POST["comments"];
        $headers = "From: ". $from;

        mail( $to,$subject,$message,$headers);

        foreach ( $_SESSION["cart"] as $key => $value) {
            if ( $key !== false) {
                unset($_SESSION["cart"][$key]);
                unset($_SESSION["prod_list"]);
            }
            $_SESSION["prod_list"] = "";
            $_SESSION["cart"] = array_values($_SESSION["cart"]);
            $_SESSION["counter"]--;
        }
    }

    if ( !isset($_SESSION["translate"])) {
        $language = LANG_ENGLISH;
        $_SESSION["translate"] = 0;
    } elseif ( $_SESSION["translate"] == 1) {
        $language = LANG_FRENCH;
    }

    $Message = "You haven't selected items yet!";

    if ( !isset($_SESSION["cart"])) {
        $_SESSION["cart"] = array();
    }
    if ( !isset($_SESSION["prod_list"])) {
        $_SESSION["prod_list"] = "";
    }
    if ( !isset($_SESSION["counter"])) {
        $_SESSION["counter"] = 0;
    }

    if ( isset($_GET["action"]) && $_GET["action"] == "remove") {
        $id_prod = intval($_GET["id"]);
        $key = array_search($id_prod,$_SESSION["cart"]);
        if ( $key !== false) {
            unset($_SESSION["cart"][$key]);
        }
        $_SESSION["cart"] = array_values($_SESSION["cart"]);
        $_SESSION["counter"]--;
    }

    $parm_array = array();
    $max_lim = intval($_SESSION["counter"]);

    if ($max_lim != 0) {
        for ( $i = 0; $i < $max_lim; $i++) {
            $parm_array[$i] = $_SESSION["cart"][$i];
        }
    }

    $parm_array = array_values($parm_array);

    if ( $max_lim > 0) {
        $gett = "SELECT id,title,description,price,imeg FROM products
               WHERE id IN(".implode(',',$parm_array).")";
        $stm = $conn->prepare($gett);
        $stm->execute();
        $result = $stm->get_result();
    }
?>
<!DOCTYPE html PUBLIC>
    <html >
    <head>
        <title> Cart</title>
    </head>
    <body>
        <div id="container">
        <?php if ( $mailerror != ""): ?>
        <?php echo $mailerror; ?>
    <?php endif; ?>
        <?php if ( $max_lim > 0): ?>
			<table>
                <tr>
                     <th>     </th>
                     <th><?php echo t('Title'); ?></th>
                     <th><?php echo t('Description'); ?></th>
                     <th><?php echo t('Price'); ?></th>
                     <th> </th>
                 </tr>
                 <?php while ( $row = $result->fetch_array(MYSQLI_NUM)): ?>
                     <tr>
                       <td><img width = "200" src = "Images/<?php echo $row[4]; ?>" alt = ""></td>
                       <td><?php echo $row[1]; ?></td>
                       <td><?php echo $row[2]; ?></td>
                       <td><?php echo $row[3]; ?></td>
                       <td><a href = "cart.php?page=products&action=remove&id=<?php echo $row[0] ?>"><?php echo t('Remove Item'); ?></a></td>
                     </tr>
                 <?php endwhile; $stm->close(); $result->close(); ?>
            </table>
       <?php endif; ?>
       <?php if ( $max_lim <= 0): ?>
           <?php echo t("You have not selected items yet!"); endif; ?>
       <p><a href = "index.php">INDEX</a></p>
        </div><!--end container-->
        <div id="order" >
            <form action = "cart.php" method = "post">
                <?php echo t('Name'); ?> <input type = "text" name = "coustomer_name"><br>
                <?php echo t('Contact details'); ?> <input type = "text" name = "email"><br>
                <?php echo t('Comments'); ?> <input type = "text" name = "comments"><br>
                <input type = "submit" name= "submit" value = "Submit">
            </form>
        </div>
    </body>
    </html>
