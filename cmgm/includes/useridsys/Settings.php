<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>
<!DOCTYPE html>
<html lang="en">
   <head>
     <script src="../../js/latlong-settings.js"></script>
     <?php
     include "../../functions.php";

     $list_of_vars = array('debug_flag', 'username', 'default_latitude', 'default_longitude', 'default_carrier', 'theme', 'cm_mapType', 'cm_groupTowers', 'cm_showLabels', 'cm_showLowAcc', 'cm_zoom', 'prefLocType');

     if (isset($_POST['default_latitude'])) {
     /// Database column names

     // Prefix for the Build-A-Query
     $sql_edit = "UPDATE userID SET ";

     // Infix for the Build-A-Query
     foreach ($list_of_vars as $value) {
       if (isset($_POST[$value])) {

           ${$value} = $_POST[$value];
           $sql_edit = $sql_edit . "$value = '".mysqli_real_escape_string($conn, ${$value})."', ";

           if ($debug_flag > 1) {
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
     if ($debug_flag == 2) redir("/?q=$default_latitude,$default_longitude","2");
     if ($debug_flag == 1) redir("/?q=$default_latitude,$default_longitude","1");
     if ($debug_flag == 0) redir("/?q=$default_latitude,$default_longitude","0");
     }
     ?>
   </head>
   <body style="text-align: center;">
     <form action="Settings.php" method="post" autocomplete="off">
        <p>Username: </p>
        <input type="text" value="<?php echo $username; ?>" name="username" id="username" required>
        <p>Latitude/Longitude: </p>
        <input type="text" value="<?php echo $default_latitude; ?>" name="default_latitude" class="w-50" id="latitude" required><input
        type="text" value="<?php echo $default_longitude; ?>" name="default_longitude" class="w-50" id="longitude" required>
        <p>Carrier: </p>
        <select class="custominput dropdown" autocomplete="on" name="default_carrier">
          <option style="display:none" disabled <?php if(empty($default_carrier)) echo 'selected="selected" ';?>></option>
          <option <?php if($default_carrier == "T-Mobile") echo 'selected="selected" ';?>value="T-Mobile">T-Mobile</option>
          <option <?php if($default_carrier == "ATT") echo 'selected="selected" ';?>value="ATT">AT&T</option>
          <option <?php if($default_carrier == "Verizon") echo 'selected="selected" ';?>value="Verizon">Verizon</option>
          <option <?php if($default_carrier == "Sprint") echo 'selected="selected" ';?>value="Sprint">Sprint</option>
          <option <?php if($default_carrier == "Sprint_keep") echo 'selected="selected" ';?>value="Sprint_keep">Sprint Keep</option>
        </select>
        <p>Theme: </p>
        <select class="custominput dropdown" autocomplete="on" name="theme">
          <option <?php if($theme == "original") echo 'selected="selected" ';?>value="original">Original</option>
          <option <?php if($theme == "dark") echo 'selected="selected" ';?>value="dark">Dark</option>
          <option <?php if($theme == "black") echo 'selected="selected" ';?>value="black">AMOLED Black</option>
        </select>
        <p>Debug Flag: </p>
        <select class="custominput dropdown" autocomplete="on" name="debug_flag">
          <option <?php if($debug_flag == "0") echo 'selected="selected" ';?>value="0">off</option>
          <option <?php if($debug_flag == "1") echo 'selected="selected" ';?>value="1">low</option>
          <option <?php if($debug_flag == "2") echo 'selected="selected" ';?>value="2">meduim</option>
          <option <?php if($debug_flag == "3") echo 'selected="selected" ';?>value="3">high</option>
        </select>
        <p>Preferred Location Type: </p>
        <select class="custominput dropdown" autocomplete="on" name="prefLocType">
          <option <?php if($prefLocType == "settings") echo 'selected="selected" ';?>value="settings">Settings</option>
          <option <?php if($prefLocType == "gps") echo 'selected="selected" ';?>value="gps">GPS</option>
        </select>
        <p>CellMapper Link: </p>
        <select class="custominput dropdown" autocomplete="on" name="cm_mapType">
          <option <?php if($cm_mapType == "osm_street") echo 'selected="selected" ';?>value="osm_street">OpenStreetMaps</option>
          <option <?php if($cm_mapType == "esri_satellite") echo 'selected="selected" ';?>value="esri_satellite">ESRI Satellite</option>
          <option <?php if($cm_mapType == "esri_topo") echo 'selected="selected" ';?>value="esri_topo">ESRI Topographical</option>
          <option <?php if($cm_mapType == "usgs_satellite") echo 'selected="selected" ';?>value="usgs_satellite">USGS Satellite</option>
        </select><br>
        <!-- GROUP TOWERS -->
        <input type="hidden" name="cm_groupTowers" value="false">
        <input type="checkbox" name="cm_groupTowers" value="true" <?php if($cm_groupTowers == "true") echo 'checked';?>>
        <label for="cm_groupTowers"> Group Towers</label>

        <!-- SHOW LABELS -->
        <input type="hidden" name="cm_showLabels" value="false">
        <input type="checkbox" name="cm_showLabels" value="true" <?php if($cm_showLabels == "true") echo 'checked';?>>
        <label for="cm_showLabels"> Show Labels</label>

        <!-- SHOW LOW ACCURACY -->
        <input type="hidden" name="cm_showLowAcc" value="false">
        <input type="checkbox" name="cm_showLowAcc" value="true"<?php if($cm_showLowAcc == "true") echo 'checked';?>>
        <label for="cm_showLowAcc"> Show Low Accuracy</label>

        <!-- CM ZOOM -->
        <br><label for="cm_zoom">Zoom: </label><input type="range" min="10" max="20" value="<?php echo $cm_zoom;?>" name="cm_zoom" id="cm_zoom"><span id="cm_zoomVal"></span>

        <p>Google Maps Link: </p>
        <br>
        <br>
        <input type="button" class="w-50 sb" onclick="myFunction();" style="color: #00000;"  value="Locate"><input
        type="submit" class="w-50 sb" style="color: #00000;"  value="Submit">
     </form>
   </body>
   <script>
   var slider = document.getElementById("cm_zoom");
   var output = document.getElementById("cm_zoomVal");
   output.innerHTML = slider.value;
   slider.oninput = function() {output.innerHTML = this.value;}
   </script>
</html>
