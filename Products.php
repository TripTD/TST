<?php
  session_start();
  require("phpScr/common.php");


 ?>
 <DOCTYPE html!>
 <html>

 <head>
 <title>Market</title>
 </head>
 <body>
   <h1>Product List</h1>
   <br>
   <table>
       <tr>
           <th>Name</th>
           <th>Description</th>
           <th>Price</th>
           <th> </th>
       </tr>

       <?php

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
              <td><a href="products.php?page=products&action=edit&id=<?php echo $row["id"] ?>"> Edit item </a></td>
              <td><a href="products.php?page=products&action=remove&id=<?php echo $row["id"] ?>"> Remove item </a></td>
            </tr>
       <?php

           }
        }
       ?>
   </table>
   <td><a href="products.php?page=products&action=insert&id=<?php echo $row["id"] ?>"> Add item </a></td>
</body>
</html>
