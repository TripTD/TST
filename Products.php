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

    if ( isset($_GET["id"]) && $_GET["action"] == "remove") {
        $id_prod = intval($_GET["id"]);
        $id_prod = stripslashes($id_prod);
        if( $stmt = $conn->prepare("DELETE FROM products WHERE id=?")) {
            $stmt->bind_param("i",$id_prod);
            $stmt->execute();
            $stmt->close();
        }
    }

    $query = "SELECT id, title, description, price FROM products ORDER BY id";
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
                <div id="main">
                    <table>
                        <?php while ( $row = $result->fetch_array(MYSQLI_NUM)): ?>
                            <tr>
                                <td><?php echo $row[1]; ?></td>
                                <td><?php echo $row[2]; ?></td>
                                <td><?php echo $row[3]; ?></td>
                                <td><a href="Product.php?page=products&action=edit&id=<?php echo $row[0] ?>"> Edit item </a></td>
                                <td><a href="Products.php?page=products&action=remove&id=<?php echo $row[0] ?>"> Remove item </a></td>
                            </tr>
                        <?php endwhile; ?>
                    </table>
                    <br>
                </div>
                <p><a href="Product.php?page=products&action=insert"> Add item </a></p>
                <br>
                <p><a href="Products.php?page=products&action=logout"> Log out</a></p>
            </div>
        </body>
    </html>
