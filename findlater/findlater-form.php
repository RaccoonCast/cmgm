<!doctype html>
<html lang="en-us">
   <head>
      <?php include '../functions.php';?>
      <link rel="stylesheet" href="styles/style.css">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel="icon" type="image/png" href="/logo.png">
   </head>
   <body>
   <p>The cell site near</p>
      <?php
         $latitude = substr("$latitude", 0, 9);
         $longitude = substr("$longitude", 0, 10);
         $cmlink = "https://cellmapper.net/map?latitude=$latitude&longitude=$longitude&zoom=18&showTowerLabels=false";
         $gmlink = "https://maps.google.com/maps?f=q&source=s_q&hl=en&q=$latitude,$longitude";
         echo '<a href="'.$gmlink.'" target="_blank">' . $latitude . ','  . $longitude . '</a>';
         echo '<a class="footer_link" href="'.$cmlink.'" target="_blank">Open</a>';
         ?>
      <form action="findlater-submit.php" method="get">
      <input type="hidden" name="latitude" value="<?php echo $latitude;?>">
      <input type="hidden" name="longitude" value="<?php echo $longitude;?>">
	  <p>is a</p>
         <select class="fakeinput dropdown" autocomplete="on" name="type">
            <option value="Macro tower">Macro tower</option>
            <option value="Monopine">Monopine/palm</option>
            <option value="Pole">Pole</option>
            <option value="Power line">Power line small cell</option>
            <option value="Rooftop">Rooftop</option>
            <option value="Small cell">Small cell</option>
            <option value="Street Light">Street Light</option>
        </select>
		 <br>
		       <p>the carrier may be</p>
           <select class="fakeinput dropdown" autocomplete="on" name="carrier">
           <?php
            if('T-Mobile' == '' . $carrier . '') { echo '<option selected="selected" value="T-Mobile">T-Mobile</option>';} else { echo '<option value="T-Mobile">T-Mobile</option>';}
            if('ATT' == '' . $carrier . '') { echo '<option selected="selected" value="ATT">AT&T</option>';} else { echo '<option value="ATT">AT&T</option>';}
            if('Verizon' == '' . $carrier . '') { echo '<option selected="selected" value="Verizon">Verizon</option>';} else { echo '<option value="Verizon">Verizon</option>';}
            if('Sprint' == '' . $carrier . '') { echo '<option selected="selected" value="Sprint">Sprint</option>';} else { echo '<option value="Sprint">Sprint</option>';}
            ?>
            <option value="Unknown">Unknown</option>
          </select>
		 <p>eNB ID: </p>
		 <textarea class="fakeinput" style="resize: none;" rows="1" cols="30" maxlength="30"  placeholder="leave blank if unknown" name="id"></textarea>
		 <br>
		 <p>Bands: </p>
       <textarea class="fakeinput" style="resize: none;" rows="1" cols="30" maxlength="35"  placeholder="leave blank if not relevant" name="bands"></textarea>
     <p>First seen: </p>
    <textarea  class="fakeinput" style="resize: none;" rows="1" cols="25" maxlength="40" autocomplete="one-time-code" placeholder="leave blank if not relevant" name="date"></textarea>
    <br>
    <p>Bio: </p>
		<?php
if (isMobile()) {
        ?>
	<textarea style="height: 250px;" class="fakeinput" rows="15" cols="35" id="bio" name="bio"></textarea>
<?php
    } else {
    ?>
   <textarea rows="5" cols="90" id="bio" name="bio"></textarea>
<?php
}
?>
      <input type="submit" class="submitbutton" value="Submit">
    </form>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.3/moment.min.js"></script>
      <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
   </body>
</html>
