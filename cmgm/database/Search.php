<!doctype html>
<html lang="en-us">
   <head>
      <?php include '../functions.php';
      if(empty($carrier)) $carrier = null;
      ?>
      <link rel="stylesheet" href="styles/style.css">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel="icon" type="image/png" href="/logo.png">
   </head>
   <body>
      <?php
         ?>
      <form action="DB.php" method="get">
        <div class="buttons">
          <h3>Search</h3><label>Cell Site Type</label><select
        class="custominput cellsite-type-custom-width dropdown" name="cellsite_type">
        <option style="display:none" disabled selected="selected"></option>
        <option value="tower">Tower</option>
        <option value="rooftop">Rooftop</option>
        <option value="tank">Tank</option>
        <option value="utility_small">Utility Pole</option>
        <option value="utility_big">Utility Tower</option>
        <option value="monopalm">Monopalm</option>
        <option value="monopine">Monopine</option>
        <option value="misc-tree">Misc tree</option>
        <option value="pole">Pole</option>
        <option value="disguised">Disguised structure</option>
        <option value="other">Other/Uknown</option>
        </select><br><label>Concealment</label><select class="custominput" autocomplete="on" name="concealed" required>
        <option style="display:none" disabled selected="selected"></option>
        <option value="true">Concealed</option>
        <option value="false">Unconcealed</option>
        </select><br><label>Status</label><select
        class="custominput cellsite-type-custom-width dropdown" name="status">
        <option value="">All</option>
        <option value="verified" selected="selected">Verified</option>
        <option value="unverified">Unverified</option>
        <option value="unmapped">Unmapped</option>
        <option value="special">Special</option>
        <option value="weird">Weird</option>
        </select>
        </select>
		       <br><label>Carrier</label><select
              class="custominput dropdown" autocomplete="on" name="carrier">
             <option <?php if(empty($carrier)) echo 'selected="selected"';?> value=""></option>
             <option <?php if($carrier == "T-Mobile") echo 'selected="selected"';?> value="T-Mobile">T-Mobile</option>
             <option <?php if($carrier == "ATT") echo 'selected="selected"';?> value="ATT">AT&T</option>
             <option <?php if($carrier == "Verizon") echo 'selected="selected"';?> value="Verizon">Verizon</option>
             <option <?php if($carrier == "Sprint") echo 'selected="selected"';?> value="Sprint">Sprint</option>
          </select>
		 <br><label>LTE/NR id</label><input
     class="custominput" maxlength="30" name="id">
		 <br><label>File</label><input class="custominput small-text" maxlength="55" name="fileSearch">
		 <br><label>City</label><input class="custominput" maxlength="45" name="city">
		 <br><label>Zip</label><input class="custominput" maxlength="45" name="zip">
		 <br><label>State</label><input class="custominput" maxlength="45" name="state">
		 <br><label>Near lat/long</label><input class="latlong custominput" maxlength="45" value="<?php echo $latitude;?>" name="latitude"><input
     class="latlong custominput" maxlength="45" value="<?php echo $longitude?>" name="longitude">
     <br><label>Tag Search</label><input class="custominput" maxlength="45" name="tags">
     <br><label></label><input type="submit" class="submitbutton custominput" value="Submit">
   </div>
    </form>
    <?php
    // Get footer
    include  "includes/footer.php";
    ?>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.3/moment.min.js"></script>
      <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
   </body>
</html>
