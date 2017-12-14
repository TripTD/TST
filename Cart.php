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
    $max_lim=intval($_SESSION["counter"]);
    for($i=0;$i<$max_lim;$i++)
    {
      if($_SESSION["cart"][$i]==$id_prod)
      {
        $ok=0;
        var_dump($_SESSION["cart"][$i]);
        unset($_SESSION["cart"][$i]);
        $_SESSION["cart"]=$_SESSION["cart"]--;
        $_SESSION["counter"]--;
        array_splice(array_filter($_SESSION["cart"]), 0, 0);
        break;
      }
    }
  }

  $parm_array=array();
  array_splice($parm_array, 1, 1);
  $max_lim=intval($_SESSION["counter"]);
  if($max_lim!=0)
  {
     for($i=0;$i<$max_lim;$i++)
     {
        $parm_array[$i]=$_SESSION["cart"][$i];
     }
  }


?>
<!DOCTYPE html PUBLIC>
<html >
<head>


  <title>ZA Cart</title>


</head>

<body>

  <div id="container">
    <?php
    if(intval($_SESSION["counter"])==0)
    echo "<h1>$Message</h1>";
    else {
      $max_lim=intval($_SESSION["counter"]);
      if($max_lim==0)
      {
        echo "NOTHING YET!";
      }

    }
     ?>
     <table>
         <tr>
             <th>Name</th>
             <th>Description</th>
             <th>Price</th>
             <th> </th>
         </tr>

         <?php


         if($max_lim>0)
         {
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
        }
           else
          {
            $gett = "SELECT id,title, description, price FROM MyItems ORDER BY id";
            $cath = $conn->query($gett);
            if($cath ->num_rows > 0)
            {
               while($row = $cath->fetch_assoc())
               {
            ?>
                 <tr>
                   <td><?php echo $row["title"]; ?></td>
                   <td><?php echo $row["description"]; ?></td>
                   <td><?php echo $row["price"]; ?></td>
                   <td><a href="cart.php?page=products&action=add&id=<?php echo $row["id"] ?>"> Add item </a></td>
                 </tr>
            <?php

                }
             }
           }
         ?>

     </table>
     <p><a href="index.php">INDEX</a></p>

  </div><!--end container-->

</body>
</html>
