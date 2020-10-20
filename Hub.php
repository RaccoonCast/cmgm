<!DOCTYPE html>
<html lang="en">
   <head>
      <?php
      include "functions.php";
      include "permits.php";
      ?>
   </head>
   <body class="flex">
     <?php
     if (!isset($_GET['permit_redirect'])) {
     echo ' Latitude: ';
     echo $latitude;
     echo '<br> Longitude: ';
     echo $longitude;
     echo '<br> Address: ';
     echo  $address . ', ' . $city . ', ' . $state . ' ' . $zip;
     if(isset($debug)) {
       if (isset($data)) echo "<br> Search Query: " . $data;
       echo "<br> Convert Algo: " . $conv_type;
       if(isset($gjson_url_1)) {
         echo '<br> URL 1: ';
         ?> <a href="<?php echo $gjson_url_1; echo "&key=" . $api_key ?>"><?php echo $gjson_url_1 ?></a>
         <?php
       }
       if(isset($gjson_url_2)) {
         if(isset($gjson_url_1)) {
           echo '<br> URL 2: ';
         } else {
           echo '<br> URL 1: ';
         }
         ?> <a href="<?php echo $gjson_url_2; echo "&key=" . $api_key ?>"><?php echo $gjson_url_2 ?></a>
         <?php
       }
     }
      hubLatLong("HubFindlater.php","#F80000","Findlater","_self");
      hubLatLong("HubDatabase.php","#00ccff","Database","_self");
      hubLatLong("cm.php","#5DC904","CellMapper","_blank");
      hubLatLong("gmaps.php","#4185FA","Google Maps","_blank");
      hubLatLong("gmaps-special.php","#B17DC9","LA Permit Map","_blank");
      ?>
      <form target="_blank" action="Hub.php" method="get">
        <input type="hidden" name="latitude" value="<?php echo $latitude;?>">
        <input type="hidden" name="longitude" value="<?php echo $longitude;?>">
        <input type="hidden" name="zip" value="<?php echo $zip;?>">
        <input type="hidden" name="city" value="<?php echo $city;?>">
        <input type="hidden" name="state" value="<?php echo $state;?>">
        <input type="hidden" name="permit_redirect" value="true">
        <input type="submit" onclick="copyToClipboard('<?php echo $address;?>')" class="submitbutton" style="color: #00e3e0;" value="Permits">
      </form>
      <form>
         <input type="hidden" name="latitude" value="<?php echo $latitude;?>">
         <input type="hidden" name="longitude" value="<?php echo $longitude;?>">
         <input onclick="myFunction2()" style="color: #ffb700" type="submit" class="submitbutton" value="Copy">
      </form>
      <?php hubLatLong("\Home.php","#00000","Back","_self");
    }?>


<script src="js/copy.js"></script>
   </body>
</html>
