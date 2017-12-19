<?php
  session_start();
  require("phpScr/common.php");
  if(!isset($_SESSION["cart"]))
  {
    $_SESSION["cart"]=array();
  }
  if(isset($_GET["action"])&&$_GET["action"]=="logout")
  {
    unset($_SESSION["logged"]);
  }
  if(!isset($_SESSION["counter"]))
  {
    $_SESSION["counter"]=0;
  }
  if(isset($_GET["action"]) && $_GET["action"]=="add")
  {
    $id_prod=intval($_GET["id"]);
    $max_lim=intval($_SESSION["counter"]);
    $ok=1;
       for($i=0;$i<$max_lim;$i++)
       {
          if($_SESSION["cart"][$i]==$id_prod)
          {
            $ok=0;
            break;
          }
       }
    if($ok==1)
    {
    $_SESSION["cart"][$_SESSION["counter"]]=$id_prod;
    $_SESSION["counter"]++;
    }
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
            WHERE id NOT IN(".implode(',',$parm_array).")";
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
                      <td><a href="index.php?page=products&action=add&id=<?php echo $row[0] ?>"> Add item </a></td>
                    </tr>
               <?php
             }
             $stmt->close();
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
                      <td><a href="index.php?page=products&action=add&id=<?php echo $row["id"] ?>"> Add item </a></td>
                    </tr>
               <?php

                   }
                }
                $cath->close();
              }
            ?>

        </table>
        <?php
        $max_lim=intval($_SESSION["counter"]);
        $parm_array=array();
        if($max_lim==0)
        {
          if(count($parm_array))
          {
             echo "NOTHING YET!";
           }
        }
        else {
           for($i=0;$i<$max_lim;$i++)
           {
              $parm_array[$i]=$_SESSION["cart"][$i];
           }
       }
       ?>
      </div><!--end of main-->

   <br><br>



   <p><a href="cart.php">CART</a></p>
   <p><a href="LogIn.php">Log In</a></p>
  </div><!--end container-->

</body>
</html>
