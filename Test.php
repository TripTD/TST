<?php
   session_start();
   require("phpScr/common.php");
   if(isset($_GET['page']))
   {
        $pages=array("products","cart");
        if(in_array($_GET['page'],$pages))
        {
          $_page=$_GET['page'];
        }else{

          $_page="products";
        }

   }
   else {
       $_page="Products";
   }
 ?>

<DOCTYPE html!>
<html>

<head>
<title>Test</title>
</head>
<body>

     <div id="market">
        <?php require($_page.".php"); ?>
     </div>
     
</body>
</html>
