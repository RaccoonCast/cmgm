<!doctype html>
<html lang="en-us">
   <head>
     <script src="https://code.jquery.com/jquery-latest.min.js"></script>
     <script src="../js/pasteimages.js"></script>
     <script src="https://code.jquery.com/jquery-latest.min.js"></script>
     <script src="script.js"></script>
     <?php include '../functions.php';?>
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="icon" type="image/png" href="/logo.png">
   </head>
   <body>
     <?php
     // Get textbox values from database-form.php
     include  "includes/database-form/upload-form.php";
     ?>
   <p>The cell site at</p>
      <?php
         $latitude = substr("$latitude", 0, 9);
         $longitude = substr("$longitude", 0, 10);
         $pmlink = "../HubPermits.php?latitude=$latitude&longitude=$longitude";
         $cmlink = "https://www.cellmapper.net/map?latitude=$latitude&longitude=$longitude&zoom=18&showTowerLabels=false";
         echo '<a href="'.$cmlink.'" target="_blank">' . $latitude . ','  . $longitude . '</a>';
         echo '<a class="footer_link" href="'.$pmlink.'" target="_blank">Permits</a>';
         ?>
      <form action="database-submit.php" method="get">
      <input type="hidden" name="latitude" value="<?php echo $latitude;?>">
      <input type="hidden" name="longitude" value="<?php echo $longitude;?>">
		       <p>the carrier is</p>
           <select class="fakeinput dropdown" autocomplete="on" name="carrier">
           <?php
            if('T-Mobile' == '' . $carrier . '') { echo '<option selected="selected" value="T-Mobile">T-Mobile</option>';} else { echo '<option value="T-Mobile">T-Mobile</option>';}
            if('ATT' == '' . $carrier . '') { echo '<option selected="selected" value="ATT">AT&T</option>';} else { echo '<option value="ATT">AT&T</option>';}
            if('Verizon' == '' . $carrier . '') { echo '<option selected="selected" value="Verizon">Verizon</option>';} else { echo '<option value="Verizon">Verizon</option>';}
            if('Sprint' == '' . $carrier . '') { echo '<option selected="selected" value="Sprint">Sprint</option>';} else { echo '<option value="Sprint">Sprint</option>';}
            ?>
          </select>
          <div style="padding: 8px ">
          <input type="checkbox" id="multiple_carriers" name="carrier_multiple" value="true">
          <label for="multiple_carriers">Multiple carriers</label><br>
        </div>
        <p>Evidence Link: </p>
        <textarea class="fakeinput" style="resize: none;" rows="5" cols="30" maxlength="500" placeholder="" name="evidence_text"><?php if (isset($link)) echo $link?></textarea><br>
        <?php if (isset($link)) {?> <a href="uploads/<?php echo $link;?>">Evidence</a><br>
        <?php
      }
      ?>
     <p>eNB ID: </p>
		 <textarea class="fakeinput" style="resize: none;" rows="1" cols="30" maxlength="70"  placeholder="" name="id" required></textarea>
		 <br>
        <input type="checkbox" id="ev1" name="permit_cellsite" value="true">
        <label for="ev1"> Permit says cellsite (+1)</label><br>
        <input type="checkbox" id="ev2" name="permit_suspected_carrier" value="true">
        <label for="ev2"> Permit matches suspected carrier (+20)</label><br>
        <input type="checkbox" id="ev3" name="trails_match" value="true">
        <label for="ev3"> Trails match suspected address with the suspected carrier (+5)</label><br>
        <input type="checkbox" id="ev4" name="other_carriers_dont" value="true">
        <label for="ev4"> Trails rule-out others (+3)</label><br>
        <input type="checkbox" id="ev5" name="antennas_match_carrier" value="true">
        <label for="ev5"> Antennas look like suspected carrier (+1)</label><br>
        <input type="checkbox" id="ev6" name="cellmapper_triangulation" value="true">
        <label for="ev6"> CellMapper triangulates very close to the suspected location (+2)</label><br>
        <input type="checkbox" id="ev7" name="image_evidence" value="true">
        <label for="ev7"> On-site image evidence of a site identifier matching the suspected carrier (+10)</label><br>
        <input type="checkbox" id="ev8" name="verified_by_visit" value="true">
        <label for="ev8"> On-site verification (+5)</label><br>
    <p>Extra notes: </p>
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
   </body>
</html>
