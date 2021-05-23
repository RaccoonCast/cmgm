<!DOCTYPE html>
<html lang="en">
   <head>
     <script src="../../js/latlong-settings.js"></script>
     <?php
     include "../../functions.php";

     $list_of_vars = array('debug_flag', 'username', 'default_latitude', 'default_longitude', 'default_carrier', 'theme');

     if (isset($_POST['default_latitude'])) {
     /// Database column names

     // Prefix for the Build-A-Query
     $sql_edit = "UPDATE userID SET ";

     // Infix for the Build-A-Query
     foreach ($list_of_vars as $value) {
       if (!empty($_POST[$value])) {

           ${$value} = $_POST[$value];
           $sql_edit = $sql_edit . "$value = '".mysqli_real_escape_string($conn, ${$value})."', ";

           if ($debug_flag == "high" OR $debug_flag == "meduim") {
             echo "<br>" . basename(__FILE__) . ": " . "Setting " . $value . " to have value '" . $_POST[$value] . "' in SQL DB for $userID";
           }

           }
         }
     // Remove last comma for the Build-A-Query
     $sql_edit = rtrim($sql_edit,', ');

     // Add suffix for the Build-A-Query
     $sql_edit = $sql_edit . " WHERE userIP = '$userIP'";

     mysqli_query($conn, $sql_edit);

     // if debug flag is high don't go back to home page.
     if ($debug_flag == "meduim") {
       redir("/convert.php?data=$default_latitude,$default_longitude","2");
     } elseif ($debug_flag == "low") {
       redir("/convert.php?data=$default_latitude,$default_longitude","1");
     } elseif ($debug_flag == "off") {
       redir("/convert.php?data=$default_latitude,$default_longitude","0");
     }
     }
     ?>
   </head>
   <body style="text-align: center;">
     <form action="Settings.php" method="post" autocomplete="off">
        <p>Username: </p>
        <input type="text" value="<?php echo $username; ?>" name="username" id="username" required>
        <p>Latitude: </p>
        <input type="text" value="<?php echo $default_latitude; ?>" name="default_latitude" id="latitude" required>
        <p>Longitude: </p>
        <input type="text" value="<?php echo $default_longitude; ?>" name="default_longitude" id="longitude" required>
        <p>Carrier: </p>
        <select class="custominput dropdown" autocomplete="on" name="default_carrier">
          <option style="display:none" disabled <?php if(empty($default_carrier)) echo 'selected="selected" ';?>></option>
          <option <?php if($default_carrier == "T-Mobile") echo 'selected="selected" ';?>value="T-Mobile">T-Mobile</option>
          <option <?php if($default_carrier == "ATT") echo 'selected="selected" ';?>value="ATT">AT&T</option>
          <option <?php if($default_carrier == "Verizon") echo 'selected="selected" ';?>value="Verizon">Verizon</option>
          <option <?php if($default_carrier == "Sprint") echo 'selected="selected" ';?>value="Sprint">Sprint</option>
        </select>
        <p>Theme: </p>
        <select class="custominput dropdown" autocomplete="on" name="theme">
          <option <?php if($theme == "original") echo 'selected="selected" ';?>value="original">Original</option>
          <option <?php if($theme == "dark") echo 'selected="selected" ';?>value="dark">Dark</option>
          <option <?php if($theme == "black") echo 'selected="selected" ';?>value="black">AMOLED Black</option>
        </select>
        <p>Debug Flag: </p>
        <select class="custominput dropdown" autocomplete="on" name="debug_flag">
          <option <?php if($debug_flag == "off") echo 'selected="selected" ';?>value="off">off</option>
          <option <?php if($debug_flag == "low") echo 'selected="selected" ';?>value="low">low</option>
          <option <?php if($debug_flag == "meduim") echo 'selected="selected" ';?>value="meduim">meduim</option>
          <option <?php if($debug_flag == "high") echo 'selected="selected" ';?>value="high">high</option>
        </select>
        <br>
        <br>
        <input type="button" class="width-50 submitbutton" onclick="myFunction();" style="color: #00000;"  value="Locate"><input
        type="submit" class="width-50 submitbutton" style="color: #00000;"  value="Submit">
     </form>
   </body>
</html>
