<?php

require("common.php");

//if the user who tries to reach this page is not authenthicated then return him to login
if (!isset($_SESSION["logged"])) {
    header("Location: login.php");
    die;
}
//clearing the $_SESSION["logged"] from held data in case of logout
if (isset($_GET["action"]) && $_GET["action"] == "logout") {
    unset($_SESSION["logged"]);
    header("Location: index.php");
    die;
}

//checking for the action remove and id to delete an item from the database
if (isset($_GET["id"])) {
    $id_prod = intval($_GET["id"]);
    if( $stmt = $conn->prepare("DELETE FROM products WHERE id=?")) {
        $stmt->bind_param("i",$id_prod);
        $stmt->execute();
        $stmt->close();
    }
}

// displaying all the data from the database
$sql = "SELECT * FROM products";
if ($stmt = $conn->prepare($sql)) {
    $stmt->execute();
    $result = $stmt->get_result();
}

?>
<!DOCTYPE HTML PUBLIC>
<html>
<head>
    <title><?= t("Products") ?></title>
</head>
<body>
<div id="container">
    <?= t('Language preference') ?> :
    <p><a href="products.php?language=en"><?= t('English') ?></a></p>
    <p><a href="products.php?language=fr"><?= t('Francais') ?></a></p>
    <table>
        <?php while ($row = $result->fetch_array(MYSQLI_ASSOC)): ?>
            <tr>
                <td><img width="200" src="Images/<?= $row["img"] ?>" alt=""></td>
                <td><?= $row["title"] ?></td>
                <td><?= $row["description"] ?></td>
                <td><?= $row["price"] ?></td>
                <td><a href="product.php?id=<?= $row['id'] ?>"><?= t("Edit Item") ?></a></td>
                <td><a href="products.php?id=<?= $row['id'] ?>"><?= t("Remove Item") ?></a></td>
            </tr>
        <?php endwhile ?>
    </table>
</div>
<div id="opt">
    <p><a href="product.php"><?= t('Add Item') ?></a></p>
    <br>
    <p><a href="products.php?action=logout"><?= t('Log out') ?></a></p>
</div>
</body>
</html>
