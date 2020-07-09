<!DOCTYPE html>
<html lang="en">
   <head>
	  <?php
    if (isset($_GET['carrier'])) $carrier = $_GET['carrier'];
    if (isset($_GET['zoom'])) $zoom = $_GET['zoom'];
    include "../functions.php";

  if (isset($_GET['zoom'])) {
    $textboxZoom = $zoom;
  } else {
    $textboxZoom = 13;
  }
  ?>
   </head>
   <body>
      <form action="index.php" method="get" autocomplete="off">
         <p>Carrier?</p>
         <select class="fakeinput dropdown" autocomplete="on" name="carrier">
         <?php
          if('T-Mobile' == '' . $carrier . '') { echo '<option selected="selected" value="T-Mobile">T-Mobile</option>';} else { echo '<option value="T-Mobile">T-Mobile</option>';}
          if('ATT' == '' . $carrier . '') { echo '<option selected="selected" value="ATT">AT&T</option>';} else { echo '<option value="ATT">AT&T</option>';}
          if('Verizon' == '' . $carrier . '') { echo '<option selected="selected" value="Verizon">Verizon</option>';} else { echo '<option value="Verizon">Verizon</option>';}
          if('Sprint' == '' . $carrier . '') { echo '<option selected="selected" value="Sprint">Sprint</option>';} else { echo '<option value="Sprint">Sprint</option>';}
          ?>
        </select>

        <p>Zoom?</p>
        <textarea class="fakeinput" style="resize: none;" rows="1" cols="30" maxlength="2" name="zoom" required><?php echo $textboxZoom;?></textarea>
       <input type="hidden" name="cookie-latitude" value="<?php echo $latitude;?>">
       <input type="hidden" name="cookie-longitude" value="<?php echo $longitude;?>">
   		 <br>
       <input type="submit" class="submitbutton" style="color: #00000;"  value="Submit">
      </form>
   </body>
</html>
<?php


if (isset($_GET['cookie-latitude'])) $latitude = $_GET['latitude'];
if (isset($_GET['cookie-longitude'])) $longitude = $_GET['longitude'];

if ("$carrier" == "T-Mobile") {
 $beginning = "?MCC=310&MNC=260&type=LTE&tilesEnabled=false&showVerifiedOnly=true";
} elseif ("$carrier" == "Sprint") {
 $beginning = "?MCC=310&MNC=120&type=LTE&tilesEnabled=false&showVerifiedOnly=true";
} elseif ("$carrier" == "ATT") {
 $beginning = "?MCC=310&MNC=410&type=LTE&tilesEnabled=false&showVerifiedOnly=true";
} elseif ("$carrier" == "Verizon") {
 $beginning = "?MCC=311&MNC=480&type=LTE&tilesEnabled=false&showVerifiedOnly=true";
}
if (isset($_GET['zoom'])) {
?>
<a href="https://www.cellmapper.net/map<?php echo $beginning?>&latitude=<?php echo $latitude?>&longitude=<?php echo $longitude?>&zoom=<?php echo $zoom?>&showTowerLabels=false&clusterEnabled=false">CellMapper</a>
<a href="https://cmgm.gq/database/DatabaseMap.php?latitude=<?php echo $latitude?>&longitude=<?php echo $longitude?>&carrier=<?php echo $carrier?>&zoom=<?php echo $zoom?>">Database Map</a>
<?php } ?>
