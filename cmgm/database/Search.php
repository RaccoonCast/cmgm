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
        <input type="hidden" name="latitude" value="<?php echo $latitude;?>">
        <input type="hidden" name="longitude" value="<?php echo $longitude;?>">
        <p>Cell site type is</p>
        <select class="custominput cellsite-type-custom-width dropdown" name="cellsite_type">
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
        </select>
		       <p>the carrier is</p>
           <select class="custominput dropdown" autocomplete="on" name="carrier">
             <option <?php if(empty($carrier)) echo 'selected="selected"';?> value=""></option>
             <option <?php if($carrier == "T-Mobile") echo 'selected="selected"';?> value="T-Mobile">T-Mobile</option>
             <option <?php if($carrier == "ATT") echo 'selected="selected"';?> value="ATT">AT&T</option>
             <option <?php if($carrier == "Verizon") echo 'selected="selected"';?> value="Verizon">Verizon</option>
             <option <?php if($carrier == "Sprint") echo 'selected="selected"';?> value="Sprint">Sprint</option>
          </select>
		 <p>the LTE/NR id is</p>
		 <input class="custominput" maxlength="30" name="id">
		 <p>has file</p>
		 <input class="custominput" maxlength="45" name="fileSearch">
     <input type="submit" class="submitbutton" value="Submit">
    </form>
    <?php
    // Get footer
    include  "includes/footer.php";
    ?>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.3/moment.min.js"></script>
      <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
   </body>
</html>
