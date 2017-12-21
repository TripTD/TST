<?php
        session_start();
        require("phpScr/common.php");

        if ( isset($_GET["action"]) && $_GET["action"] == "logout") {
                unset($_SESSION["logged"]);
        }
        if ( !isset($_SESSION["cart"])) {
                $_SESSION["cart"]=array();
        }
        if ( !isset($_SESSION["counter"])) {
                $_SESSION["counter"]=0;
        }
        if ( isset($_GET["action"]) && $_GET["action"] == "add") {
                $id_prod = intval( $_GET["id"]);
                $valid = 1;
                for ( $i = 0; $i < intval($_SESSION["counter"]); $i++) {
                        if ( $_SESSION["cart"][$i] == $id_prod) {
                                $valid = 0;
                                break;
                        }
                }
                if ($valid) {
                        $next = $_SESSION["counter"];
                        $_SESSION["cart"][$next] = $id_prod;
                        $_SESSION["counter"] ++ ;
                }
        }
        if (!isset($ids)) {
                $ids =  array();
                $ids[0] = 0;
        }
        if (intval($_SESSION["counter"]) != 0) {
                for ($i = 0; $i < intval($_SESSION["counter"]); $i++) {
                        $ids[$i] = $_SESSION["cart"][$i];
                }
        }
        if ( intval($_SESSION["counter"]) >= 0) {
                $query = "SELECT id, title, description, price FROM MyItems WHERE id NOT IN (" . implode( ',', $ids) . ")";
                if ( $stmt = $conn -> prepare($query)) {
                        $stmt -> execute();
                        $result = $stmt -> get_result();
                }
        }
 ?>
 <!DOCTYPE html PUBLIC>
       <html >
             <head>
                 <title>Shopping Cart</title>
             </head>
       <body>
            <div id="container">
                 <div id="main">
                     <table>
                        <?php while ( $row = $result -> fetch_array(MYSQLI_NUM)) { ?>
                               <tr>
                                      <td><?php echo $row[1]; ?></td>
                                      <td><?php echo $row[2]; ?></td>
                                      <td><?php echo $row[3]; ?></td>
                                      <td><a href="index.php?page=products&action=add&id=<?php echo $row[0] ?>"> Add Item </a></td>
                               </tr>
                       <?php } ?>
                     </table>
                     <br>
                </div>
                <p><a href="Cart.php"> Go to Cart </a></p>
            </div>
            <p><a href="LogIn.php"> LogIn </a></p>
       </body>
       </html>
