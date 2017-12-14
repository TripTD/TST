<?php
  session_start();
  require("phpScr/common.php");
  $Message="You haven't selected items yet!";
  if(!isset($_SESSION["cart"]))
  {
    $_SESSION["cart"]=array();
  }
  if(!isset($_SESSION["counter"]))
  {
    $_SESSION["counter"]=0;
  }
  if(isset($_GET["action"]) && $_GET["action"]=="remove")
  {
    $id_prod=intval($_GET["id"]);
    $key=array_search($id_prod,$_SESSION["cart"]);
    if($key!==false)
    {
      unset($_SESSION["cart"][$key]);
    }
    $_SESSION["cart"]=array_values($_SESSION["cart"]);
    $_SESSION["counter"]--;
  }
  $parm_array=array();
  $max_lim=intval($_SESSION["counter"]);
  if($max_lim!=0)
  {
     for($i=0;$i<$max_lim;$i++)
     {
        $parm_array[$i]=$_SESSION["cart"][$i];
     }
  }
  $parm_array=array_values($parm_array);
?>
<!DOCTYPE html PUBLIC>
<html >
<head>


  <title>ZA Cart</title>


</head>

<body>

  <div id="container">
    <?php
    if($max_lim>0)
    {
      ?>
     <table>
         <tr>
             <th>Name</th>
             <th>Description</th>
             <th>Price</th>
             <th> </th>
         </tr>

         <?php

         $gett="SELECT id,title,description,price FROM MyItems
         WHERE id IN(".implode(',',$parm_array).")";
         $stm=$conn->prepare($gett);
         $stm->execute();
         $result=$stm->get_result();
          while($row=$result->fetch_array(MYSQLI_NUM))
          {
            ?>
                 <tr>
                   <td><?php echo $row[1]; ?></td>
                   <td><?php echo $row[2]; ?></td>
                   <td><?php echo $row[3]; ?></td>
                   <td><a href="cart.php?page=products&action=remove&id=<?php echo $row[0] ?>"> Remove item </a></td>
                 </tr>
            <?php
          }
         ?>

     </table>
   <?php }
   else {
     echo $Message;
   } ?>
     <p><a href="index.php">INDEX</a></p>

  </div><!--end container-->

</body>
</html>
