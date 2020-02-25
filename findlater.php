<!doctype html>
<html lang="en-us">
   <head>
      <title>Evil CM</title>
      <?php include 'functions.php';?>
      <?php if(isMobile()){
    	  echo '<link rel="stylesheet" href="styles/findlater/mobile.css">';
      } else {
    	  echo '<link rel="stylesheet" href="styles/findlater/desktop.css">';
      }
    ?>
      <link rel="stylesheet" href="styles/style.css">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta charset="utf-8">
      <link rel="icon" type="image/png" href="/logo.png">
   </head>
   <body>
   <p>The cell site near</p>
      <?php
         $latitude = file_get_contents('dustbin\latitude.txt');
         $longitude = file_get_contents('dustbin\longitude.txt');
         $cmlink = "https://www.cellmapper.net/map?latitude=$latitude&longitude=$longitude&zoom=21";
         echo nl2br("$latitude \n $longitude");
         // echo '<a style="display:inline" href="'.$cmlink.'" target="_blank">Open in CellMapper</a>';
         $path = 'dustbin\data.txt';

         ?>
      <form action="findlaterdb.php" method="post">
	  <p>is a</p>
         <select class="fakeinput" autocomplete="on" name="field1">
            <option value="Macro tower">Macro tower</option>
            <option value="Monopine/palm">Monopine/palm</option>
            <option value="Pole">Pole</option>
            <option value="Power line">Power line small cell</option>
            <option value="Rooftop">Rooftop</option>
            <option value="Small cell">Small cell</option>
            <option value="Street Light">Street Light</option>
        </select>
		 <br>
		       <p>the carrier may be</p>
           <select class="fakeinput" autocomplete="on" name="field2">
           <?php
           if (file_exists('dustbin\carrier.txt')) {
               $carrier = file_get_contents('dustbin\carrier.txt');
           }
            if('T-Mobile' == '' . $carrier . '') { echo '<option selected="selected" value="T-Mobile">T-Mobile</option>';} else { echo '<option value="T-Mobile">T-Mobile</option>';}
            if('AT&T' == '' . $carrier . '') { echo '<option selected="selected" value="AT&T">AT&T</option>';} else { echo '<option value="AT&T">AT&T</option>';}
            if('Verizon' == '' . $carrier . '') { echo '<option selected="selected" value="Verizon">Verizon</option>';} else { echo '<option value="Verizon">Verizon</option>';}
            if('Sprint' == '' . $carrier . '') { echo '<option selected="selected" value="Sprint">Sprint</option>';} else { echo '<option value="Sprint">Sprint</option>';}
            ?>
          </select>
		 <p>eNB ID: </p>
		 <textarea class="fakeinput" style="resize: none;" rows="1" cols="25" maxlength="21" name="field3"></textarea>
		 <br>
		 <p>Bands: </P>
       <textarea class="fakeinput" style="resize: none;" rows="1" cols="25" maxlength="21" name="field5"></textarea>
    <p>First seen: </P>
      <input type="date" id="box2" class="date" data-date="" data-date-format="DD MMMM YYYY" value="2015-11-13" name="field4">
    <textarea  class="fakeinput" style="resize: none;" rows="1" cols="25" autocomplete="one-time-code" title="Accepts: 2015-12-31; Jan 13 2020; Jan 13, 2020" placeholder="or type Dec 13, 2019" name="field7"></textarea>
    <br>
    <p>More info: </p>
		<?php
if (isMobile()) {
        ?>
	<textarea rows="15" cols="35" id="bio" name="field6"></textarea>
<?php
    } else {
    ?>
   <textarea rows="7" cols="65" id="bio" name="field6"></textarea>
<?php
}
?>
      <br>
      <input type="submit" class="submitbutton" value="Submit">
      </form>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.3/moment.min.js"></script>
      <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
      <script src="js\date.js"></script>
   </body>
</html>
