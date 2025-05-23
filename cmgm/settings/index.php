<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>
<!DOCTYPE html>
<html lang="en">
   <head>
     <title>CMGM - Settings</title>
     <script src="https://code.jquery.com/jquery-latest.min.js"></script>
     <script src="../js/latlong-settings.js"></script>
     <script src="/js/copyToClipboard.js"></script>
     <script src="/js/setCookie.js"></script>
     <?php
     $titleOverride = "true";
     include "../functions.php";
     $list_of_vars = array(
      'debug_flag', 
      'username', 
      'accent_color', 
      'default_latitude', 
      'default_longitude', 
      'default_carrier', 
      'theme', 
      'cm_mapType', 
      'cm_groupTowers', 
      'cm_showLabels', 
      'cm_showLowAcc', 
      'cm_zoom', 
      'prefLocType', 
      'cmgm_edit_hide_edit_history', 
      'cmgm_edit_history_compact', 
      'cmgm_edit_pinspace_warn', 
      'cmgm_edit_override_panels_widths', 
      'cmgm_edit_panel1_width', 
      'map_map_pin_limit',
      'map_edit_pin_limit',
      'map_map_mobile_pin_limit',
      'map_map_edit_mobile_pin_limit'
      );

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
     $sql_edit = $sql_edit . " WHERE userID = '$userID'";

     mysqli_query($conn, $sql_edit);

     // if debug flag is high don't go back to home page.
     if ($debug_flag == 2) redir("/?q=$default_latitude,$default_longitude","2");
     if ($debug_flag == 1) redir("/?q=$default_latitude,$default_longitude","1");
     if ($debug_flag == 0) redir("/?q=$default_latitude,$default_longitude","0");
     }
     ?>
   </head>
   <body style="text-align: center;">
     <h1> Settings </h1>
     <form action="../settings/" method="post" autocomplete="off">
        <label class="label">Username: </label><input
        type="text" value="<?php echo $username; ?>" name="username" id="username" required>
        <label class="label">Accent Color (HEX): </label><input
        type="text" value="<?php echo $accent_color; ?>" name="accent_color" maxlength="6" id="accent_color" required>
        <label class="label">Latitude/Longitude: </label><input
        type="text" value="<?php echo $default_latitude; ?>" name="default_latitude" class="w-50" id="latitude" required><input
        type="text" value="<?php echo $default_longitude; ?>" name="default_longitude" class="w-50" id="longitude" required>
        <label class="label">Carrier: </label><select
        class="custominput dropdown" autocomplete="on" name="default_carrier">
          <option style="display:none" disabled <?php if(empty($default_carrier)) echo 'selected="selected" ';?>></option>
          <option <?php if($default_carrier == "") echo 'selected="selected" ';?>value="">None</option>
          <option <?php if($default_carrier == "T-Mobile") echo 'selected="selected" ';?>value="T-Mobile">T-Mobile</option>
          <option <?php if($default_carrier == "ATT") echo 'selected="selected" ';?>value="ATT">AT&T</option>
          <option <?php if($default_carrier == "Verizon") echo 'selected="selected" ';?>value="Verizon">Verizon</option>
          <option <?php if($default_carrier == "Sprint") echo 'selected="selected" ';?>value="Sprint">Sprint</option>
          <option <?php if($default_carrier == "Dish") echo 'selected="selected" ';?>value="Dish">Dish</option>
        </select>
        <label class="label">Theme: </label><select
        class="custominput dropdown" autocomplete="on" name="theme">
          <option <?php if($theme == "original") echo 'selected="selected" ';?>value="original">Basic</option>
          <option <?php if($theme == "pink") echo 'selected="selected" ';?>value="pink">Pink</option>
          <option <?php if($theme == "black") echo 'selected="selected" ';?>value="black">AMOLED Black</option>
        </select>
        <!-- label class="label">Debug Flag: </label><select
        class="custominput dropdown" autocomplete="on" name="debug_flag">
          <option <?php // if($debug_flag == "0") echo 'selected="selected" ';?>value="0">off</option>
          <option <?php // if($debug_flag == "1") echo 'selected="selected" ';?>value="1">low</option>
          <option <?php // if($debug_flag == "2") echo 'selected="selected" ';?>value="2">meduim</option>
          <option <?php // if($debug_flag == "3") echo 'selected="selected" ';?>value="3">high</option>
        </select> -->
        <label class="label">Preferred Location Source: </label><select
        class="custominput dropdown" autocomplete="on" name="prefLocType">
          <option <?php if($prefLocType == "settings") echo 'selected="selected" ';?>value="settings">Settings</option>
          <option <?php if($prefLocType == "gps") echo 'selected="selected" ';?>value="gps">GPS</option>
        </select>
        <label class="label">CellMapper Link Maptype: </label><select
        class="custominput dropdown" autocomplete="on" name="cm_mapType">
          <option <?php if($cm_mapType == "osm_street") echo 'selected="selected" ';?>value="osm_street">OpenStreetMaps</option>
          <option <?php if($cm_mapType == "esri_satellite") echo 'selected="selected" ';?>value="esri_satellite">ESRI Satellite</option>
          <option <?php if($cm_mapType == "esri_topo") echo 'selected="selected" ';?>value="esri_topo">ESRI Topographical</option>
          <option <?php if($cm_mapType == "usgs_satellite") echo 'selected="selected" ';?>value="usgs_satellite">USGS Satellite</option>
        </select><span style="display:none;" id="cmgm_edit_override_panels_widths">
        <label class="label">Panel 1/Panel 2 Widths: </label><input
        type="text" value="<?php echo $cmgm_edit_panel1_width; ?>" name="cmgm_edit_panel1_width" class="w-50" id="cmgm_edit_panel1_width"><input
        type="text" value="<?php echo $cmgm_edit_panel2_width; ?>" name="cmgm_edit_panel2_width" class="w-50" id="cmgm_edit_panel2_width"></span>
        <label class="label">CMGM UserID:</label><input type="text" class="userid-field" readonly value="<?php echo $userID; ?>"></input><input 
        type="button" class="sb cmgm-btn inline-btn copy-btn" style="font-size: 1em;" value="Copy" onclick="copyToClipboard('<?php echo $userID; ?>')">

        <br><br><h4>CMGM Edit Settings:</h4>

        <div style="text-align: center" class="cmcheckboxes ib">
          
        <label for="cmgm_edit_override_panels_widths">Custom width for panels<input
        type="hidden" name="cmgm_edit_override_panels_widths" value="false"><input
        type="checkbox" id="cmgm_edit_override_panels_widths_checkbox" name="cmgm_edit_override_panels_widths" value="true" <?php if($cmgm_edit_override_panels_widths == "true") echo 'checked';?>></label>

        <label for="cmgm_edit_pinspace_warn"><?php if(!isMobile()) echo " | " ?>Hide pin spacing warnings<input
        type="hidden" name="cmgm_edit_pinspace_warn" value="false"><input
        type="checkbox" id="cmgm_edit_pinspace_warn_checkbox" name="cmgm_edit_pinspace_warn" value="true" <?php if($cmgm_edit_pinspace_warn == "true") echo 'checked';?>></label>
        <br>
        <label for="cmgm_edit_history_compact"><?php if(!isMobile()) echo "" ?>Compact edit history<input
        type="hidden" name="cmgm_edit_history_compact" value="false"><input
        type="checkbox" id="cmgm_edit_history_compact" name="cmgm_edit_history_compact" value="true" <?php if($cmgm_edit_history_compact == "true") echo 'checked';?>></label>

        <label for="cmgm_edit_hide_edit_history"><?php if(!isMobile()) echo " | " ?>Hide edit history<input
        type="hidden" name="cmgm_edit_hide_edit_history" value="false"><input
        type="checkbox" id="cmgm_edit_hide_edit_history_checkbox" name="cmgm_edit_hide_edit_history" value="true" <?php if($cmgm_edit_hide_edit_history == "true") echo 'checked';?>></label>
      
        </div>

        <h4>CellMapper Link Settings:</h4>
        <script>
        $(() => {
          const $checkbox = $('#cmgm_edit_override_panels_widths_checkbox');
          const $elementToHide = $('#cmgm_edit_override_panels_widths');

          function toggleElementVisibility() { $elementToHide.toggle($checkbox.prop('checked'));}
          toggleElementVisibility(); // show/hide the element on page load

          $checkbox.on('change', toggleElementVisibility); // toggle element visibility when the checkbox changes
        });
        </script>

        </label><div class="cmcheckboxes ib"><label for="cm_groupTowers">Group pins<input
        type="hidden" name="cm_groupTowers" value="false"><input
        type="checkbox" id="cm_groupTowers" name="cm_groupTowers" value="true" <?php if($cm_groupTowers == "true") echo 'checked';?>></label>

        <label for="cm_showLabels"><?php if(!isMobile()) echo " | " ?><span>eNB labels</span><input
        type="hidden" name="cm_showLabels" value="false"><input
        type="checkbox" id="cm_showLabels" name="cm_showLabels" value="true" <?php if($cm_showLabels == "true") echo 'checked';?>></label>

        <label for="cm_showLowAcc"><?php if(!isMobile()) echo " | " ?><span>Low Accuracy</span><input
        type="hidden" name="cm_showLowAcc" value="false"><input
        type="checkbox" id="cm_showLowAcc" name="cm_showLowAcc" value="true"<?php if($cm_showLowAcc == "true") echo 'checked';?>></label>

        <?php if(!isMobile()) echo " | " ?>
          <input
          type="hidden" name="cm_zoom" value="false"><span>Zoom: </span></label><input
          type="range" min="10" max="20" value="<?php echo $cm_zoom;?>" name="cm_zoom" id="cm_zoom"  oninput="document.getElementById('cm_zoomVal;').innerHTML = this.value"><span id="cm_zoomVal"><?php echo $cm_zoom;?></span>
        </div>

        <h4>CMGM Map Pin Limit:</h4>
        <div class="slider-group">
          <?php if(!isMobile()) { ?>
          <div class="slider-container">
              <input type="hidden" name="map_map_pin_limit" value="false">
              <span class="slider-label">Map: </span>
              <input type="range" step="10" min="300" max="1000" value="<?php echo $map_map_pin_limit;?>" name="map_map_pin_limit" id="map_map_pin_limit" class="slider" oninput="document.getElementById('map_map_pin_limitVal').innerHTML = this.value">
              <span id="map_map_pin_limitVal" class="slider-value"><?php echo $map_map_pin_limit;?></span>
          </div>

          <div class="slider-container">
              <input type="hidden" name="map_edit_pin_limit" value="false">
              <span class="slider-label">Edit's Map: </span>
              <input type="range" step="5" min="50" max="200" value="<?php echo $map_edit_pin_limit;?>" name="map_edit_pin_limit" id="map_edit_pin_limit" class="slider" oninput="document.getElementById('map_edit_pin_limitVal').innerHTML = this.value">
              <span id="map_edit_pin_limitVal" class="slider-value"><?php echo $map_edit_pin_limit;?></span>
          </div>
          <?php } else { ?>

            <div class="slider-container">
              <input type="hidden" name="map_map_mobile_pin_limit" value="false">
              <span class="slider-label">Map: </span>
              <input type="range" step="10" min="200" max="500" value="<?php echo $map_map_mobile_pin_limit;?>" name="map_map_mobile_pin_limit" id="map_map_mobile_pin_limit" class="slider" oninput="document.getElementById('map_map_mobile_pin_limitVal').innerHTML = this.value">
              <span id="map_map_mobile_pin_limitVal" class="slider-value"><?php echo $map_map_mobile_pin_limit;?></span>
          </div>
          <div class="slider-container">
              <input type="hidden" name="map_edit_mobile_pin_limit" value="false">
              <span class="slider-label">Edit's Map: </span>
              <input type="range" step="5" min="10" max="100" value="<?php echo $map_edit_mobile_pin_limit;?>" name="map_edit_mobile_pin_limit" id="map_edit_mobile_pin_limit" class="slider" oninput="document.getElementById('map_edit_mobile_pin_limitVal').innerHTML = this.value">
              <span id="map_edit_mobile_pin_limitVal" class="slider-value"><?php echo $map_edit_mobile_pin_limit;?></span>
          </div>
          <?php } ?>
        </div>

        <br>
        <h3><a href="../fun/?limit=15&username=<?php echo $username ?>">Statistics</a> | <a href="../?signOut">Sign Out</a></h3>
        <!--
        <label class="label">CMGM Map Settings: </label><div class="cmcheckboxes ib"><label for="cm_groupTowers">Auto reload</label><input
        type="hidden" name="cmgm_auto_reload" value="false"><input
        type="checkbox" id="cmgm_auto_reload" name="cmgm_auto_reload" value="true" <?php // if($cmgm_auto_reload == "true") echo 'checked';?>>

        <label for="cmgm_map_allcarriers"> | All carriers</label><input
        type="hidden" name="cmgm_map_allcarriers" value="false"><input
        type="checkbox" id="cmgm_map_allcarriers" name="cmgm_map_allcarriers" value="true" <?php // if($cmgm_map_allcarriers == "true") echo 'checked';?>>

        <label for="cmgm_map_simple" title="No specialized pin colors, just red/green."> | Simple </label><input
        type="hidden" name="cmgm_map_simple" value="false"><input
        type="checkbox" id="cmgm_map_simple" name="cmgm_map_simple" value="true"<?php // if($cmgm_map_simple == "true") echo 'checked';?>>

        <label for="cmgm_map_pin_limit"> | Max Pins: </label><input
        type="range" step="25" min="50" max="1000" value="<?php // echo $cmgm_map_pin_limit;?>" name="cmgm_pin_limit" id="cmgm_map_pin_limit"><span id="cmgm_map_pin_limitVal"></span></div>

        <label>Google Maps Link: </label> -->

        <input type="button" class="cmgm-btn sb" onclick="myFunction();" value="Locate"><input
        type="submit" class="sb cmgm-btn" value="Submit">
     </form>
   </body>
</html>
