<!DOCTYPE html>
<html lang="en">
   <head>
     <?php
     include "../../functions.php";

     $list_of_vars = array('username', 'default_latitude', 'default_longitude', 'default_carrier', 'theme');

     if (isset($_POST['default_latitude'])) {
     /// Database column names

     // Prefix for the Build-A-Query
     $sql_edit = "UPDATE userID SET ";

     // Infix for the Build-A-Query
     foreach ($list_of_vars as $value) {
           ${$value} = $_POST[$value];
           $sql_edit = $sql_edit . "$value = '".mysqli_real_escape_string($conn, ${$value})."', ";
         }
     // Remove last comma for the Build-A-Query
     $sql_edit = rtrim($sql_edit,', ');

     // Add suffix for the Build-A-Query
     $sql_edit = $sql_edit . " WHERE userIP = '$userIP'";

     mysqli_query($conn, $sql_edit);

     redir("/Home.php","0");
     }
     ?>
   </head>
   <body>
     <form action="Edit.php" method="post" autocomplete="off">
        <p>Username: </p>
        <input type="text" value="<?php echo $username; ?>" name="username" id="username" required>
        <p>Latitude: </p>
        <input type="text" value="<?php echo $default_latitude; ?>" name="default_latitude" id="default_latitude" required>
        <p>Longitude: </p>
        <input type="text" value="<?php echo $default_longitude; ?>" name="default_longitude" id="default_longitude" required>
        <p>Carrier: </p>
        <select class="custominput dropdown" autocomplete="on" name="default_carrier">
          <option <?php if($default_carrier == "T-Mobile") echo 'selected="selected" ';?>value="T-Mobile">T-Mobile</option>
          <option <?php if($default_carrier == "ATT") echo 'selected="selected" ';?>value="ATT">AT&T</option>
          <option <?php if($default_carrier == "Verizon") echo 'selected="selected" ';?>value="Verizon">Verizon</option>
          <option <?php if($default_carrier == "Sprint") echo 'selected="selected" ';?>value="Sprint">Sprint</option>
        </select>
        <select class="custominput dropdown" autocomplete="on" name="theme">
          <option <?php if($theme == "black") echo 'selected="selected" ';?>value="black">AMOLED Black</option>
          <option <?php if($theme == "dark") echo 'selected="selected" ';?>value="dark">Dark</option>
          <option <?php if($theme == "original") echo 'selected="selected" ';?>value="original">Original</option>
        </select>
        <input type="button" class="submitbutton" onclick="myFunction();" style="color: #00000;"  value="Locate">
        <input type="submit" class="submitbutton" style="color: #00000;"  value="Submit">
     </form>
   </body>
</html>
