<!doctype html>
<html lang="en-us">
   <head>
      <?php include '../functions.php';
      if(empty($latitude)) $latitude = $default_latitude;
      if(empty($longitude)) $longitude = $default_longitude;
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
          <h3>Search</h3><!-- <label class="label">eNB / gNB</label>--><input
          class="custominput" placeholder="eNB / gNB" type="text" maxlength="30" name="id"><br><!--<label class="label">Cell Site Type</label>--><select
          class="w-20 custominput cellsite-type-custom-width dropdown" name="status">
          <option selected="selected" value="">Status: All</option>
          <option value="verified" >Verified</option>
          <option value="unverified">Unverified</option>
        </select><select class="w-25 dropdown custominput" autocomplete="on" name="concealed">
            <option selected="selected" value="">Conlcealed: n/a</option>
            <option value="true">Concealed</option>
            <option value="false">Unconcealed</option>
            </select><select
               class="custominput w-23 dropdown" autocomplete="on" name="carrier">
              <option selected="selected" value="">Carrier: Any</option>
              <option value="T-Mobile">T-Mobile</option>
              <option value="ATT">AT&T</option>
              <option value="Verizon">Verizon</option>
              <option value="Sprint">Sprint</option>
              <option value="Dish">Dish</option>
           </select><select
        class="custominput cellsite-type-custom-width dropdown w-32" name="cellsite_type">
        <option style="display: none" selected="selected" value="">Tower type: All</option>
        <?php 
        include "$SITE_ROOT/includes/functions/tower_types.php";
        foreach ($options as $category => $subcategories) {
          echo PHP_EOL . '<optgroup label="' . $category . '">' . PHP_EOL;
          foreach ($subcategories as $sub_key => $sub_val) {
              echo '    <option class="subtype_' . str_replace('_', '-', strtolower($category)) . '" ';
              if (@$cellsite_type == $sub_key) echo 'selected ';
              echo 'value="' . $sub_key . '">' . $sub_val . '</option>' . PHP_EOL;
          }
          echo '</optgroup>' . PHP_EOL;
      }
        ?>

      </select><!--<label class="label">Concealment</label> --><!-- <br>label class="label">Status</label>-->
      </select>
		       <!-- <label class="label">Carrier</label>-->
		 <br><!-- <br><label class="label">Creation Date</label><input
     class="custominput" type="text" maxlength="11" placeholder="1/20/2009" name="date">
		 <br> --><!--<label class="label">File</label>-->
		 <!--<br><label class="label">Address (of site)</label>--><input type="text" class="custominput" maxlength="50" placeholder="Address" autocomplete="chrome-off" name="address"><input
     class="custominput" type="text" maxlength="25" placeholder="City" autocomplete="chrome-off" name="city"><input
     class="custominput" type="text" maxlength="5" placeholder="Zip" autocomplete="chrome-off" name="zip"><input
     class="custominput" type="text" maxlength="20" placeholder="State" autocomplete="chrome-off" name="state">
		 <!--<br><label class="label">Sort results near</label>--><input type="text" class="latlong custominput" maxlength="45" value="<?php echo @$latitude;?>" placeholder="Latitude" name="latitude"><input
     type="text" class="latlong custominput" placeholder="Longitude" maxlength="45" value="<?php echo @$longitude?>" name="longitude">
     <!--<br><label class="label">Tag filter</label>--><input type="text" class="custominput" maxlength="45" placeholder="Tags filter" name="tags"><input
     type="text" class="custominput small-text" maxlength="55" placeholder="File search filter" name="fileSearch">
     <!-- <br><label class="label">Limits results to</label><input type="text" class="custominput" maxlength="45" value="500" name="limit"> -->
     <!--<label class="label height38">Has Street View:</label><div class="wrapper-73">
     <input type="radio" id="sv_1" name="has_street_view" value="true"><label for="sv_1">true</label>
     <input type="radio" id="sv_2" name="has_street_view" value="false"><label for="sv_2">false</label>
   </div> -->
     <!-- <br><label class="label height38">Map Pin Styles</label><div class="wrapper-73">
       <input type="radio" id="pin_1" name="pin_style" value="default" checked><label for="pin_1"> Default</label>
       <input type="radio" id="pin_2" name="pin_style" value="basic"><label for="pin_2"> Basic</label>
       <input type="radio" id="pin_3" name="pin_style" value="celltype"><label for="pin_3"> Tower Type</label>
       <input type="radio" id="pin_4" name="pin_style" value="carrier"><label for="pin_4"> Carrier</label>
     </div> -->
     <p>
       > = more than/after<br>
       < = less than/before
     </p>
     <input type="submit" formaction="DB.php" class="sb w-75 custominput cmgm-btn" value="Search"><input
     type="submit" formaction="Map.php" class="w-25 sb custominput cmgm-btn" value="Search on map">
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
