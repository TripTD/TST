<?php
// Start the session
session_start();
?>
<!DOCTYPE html>
<html>

<head>
<title>Product</title>
</head>

<body>

<form action="update.php" method="post">
Title <input type="text" name="title"><br>
Description <input type="text" name="description"><br>
Price <input type="number" name="price"><br>
Image <input type="file" name="image"><br>
<button type="submit" onclick="">Save</button>
</form>

<button type="button" onclick="location.href='Products.php';">Products</button>

</body>

</html>
