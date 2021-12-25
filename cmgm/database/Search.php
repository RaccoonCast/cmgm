<!doctype html>
<html lang="en-us">
   <head>
      <?php include '../functions.php';
      if(empty($carrier)) $carrier = $default_carrier;
      ?>
      <link rel="stylesheet" href="styles/style.css">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel="icon" type="image/png" href="/logo.png">
   </head>
   <body>
      <?php
         ?>
      <form autocomplete="off"  method="get">
        <div class="buttons">
          <h3>Search</h3><label class="label">Cell Site Type</label><select
        class="custominput cellsite-type-custom-width dropdown" name="cellsite_type">
        <option selected="selected" value="">All</option>
        <option value="tower">Tower</option>
        <option value="rooftop">Rooftop</option>
        <option value="tank">Tank</option>
        <option value="utility_small">Utility Pole</option>
        <option value="utility_big">Utility Tower</option>
        <option value="monopalm">Monopalm</option>
        <option value="monopine">Monopine</option>
        <option value="misc-tree">Misc tree</option>
        <option value="pole">Pole</option>
        <option value="structure">Disguised structure</option>
        <option value="other">Other/Uknown</option>
        </select><br><label class="label">Concealment</label><select class="custominput" autocomplete="on" name="concealed">
        <option selected="selected" value="">Unconcealed/Concealed</option>
        <option value="true">Concealed</option>
        <option value="false">Unconcealed</option>
        </select><br><label class="label">Status</label><select
        class="custominput cellsite-type-custom-width dropdown" name="status">
        <option selected="selected" value="">All</option>
        <option value="verified" selected="selected">Verified</option>
        <option value="unverified">Unverified</option>
        </select>
        </select>
		       <br><label class="label">Carrier</label><select
              class="custominput dropdown" autocomplete="on" name="carrier">
             <option <?php if(empty($carrier)) echo 'selected="selected"';?> value="">All</option>
             <option <?php if($carrier == "T-Mobile") echo 'selected="selected"';?> value="T-Mobile">T-Mobile</option>
             <option <?php if($carrier == "ATT") echo 'selected="selected"';?> value="ATT">AT&T</option>
             <option <?php if($carrier == "Verizon") echo 'selected="selected"';?> value="Verizon">Verizon</option>
             <option <?php if($carrier == "Sprint") echo 'selected="selected"';?> value="Sprint">Sprint</option>
          </select>
		 <br><label class="label">LTE/NR id</label><input
     class="custominput" type="text" maxlength="30" name="id"><br><label class="label">Creation Date</label><input
     class="custominput" type="text" maxlength="11" placeholder="1/20/2009" name="date">
		 <br><label class="label">File</label><input type="text" class="custominput small-text" maxlength="55" name="fileSearch">
		 <br><label class="label">Address (of site)</label><input type="text" class="custominput" maxlength="50" placeholder="Address" autocomplete="chrome-off" name="address"><input
     class="custominput" type="text" maxlength="25" placeholder="City" autocomplete="chrome-off" name="city"><input
     class="custominput" type="text" maxlength="5" placeholder="Zip" autocomplete="chrome-off" name="zip"><input
     class="custominput" type="text" maxlength="20" placeholder="State" autocomplete="chrome-off" name="state">
		 <br><label class="label">Near lat/long</label><input type="text" class="latlong custominput" maxlength="45" value="<?php echo $latitude;?>" name="latitude"><input
     type="text" class="latlong custominput" maxlength="45" value="<?php echo $longitude?>" name="longitude">
     <br><label class="label">Tag Search</label><input type="text" class="custominput" maxlength="45" name="tags">
     <br><label class="label">Limits results to</label><input type="text" class="custominput" maxlength="45" value="500" name="limit">
     <label class="label">Has Street View:</label><div class="wrapper-73">
     <input type="radio" id="sv_true" name="has_street_view" value="true"><label for="street_view_true">true</label>
     <input type="radio" id="sv_false" name="has_street_view" value="false"><label for="street_view_false">false</label>
     </div>
     <br><label class="label" for="basic">Basic Map Pins</label><div class="wrapper-73"><input type="checkbox" name="basic" value="true"></div>
     <p>
       > = more than/after<br>
       < = less than/before
     </p>
     <label></label><input type="submit" formaction="Map.php" class="sb custominput" value="Map View"><input
     type="submit" formaction="DB.php" class="sb custominput" value="List View">
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
