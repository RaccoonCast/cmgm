<!doctype html>
<html lang="en-us">
   <head>
      <?php include '../functions.php';?>
      <link rel="stylesheet" href="styles/style.css">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel="icon" type="image/png" href="/logo.png">
   </head>
   <body>
   <p>Searching for a</p>
      <?php
         ?>
      <form action="FindlaterDB.php" method="get">
        <input type="hidden" name="latitude" value="<?php echo $latitude;?>">
        <input type="hidden" name="longitude" value="<?php echo $longitude;?>">
         <select class="fakeinput dropdown" autocomplete="on" name="type">
            <option value=""> </option>
            <option value="Macro tower">Macro tower</option>
            <option value="Monopine">Monopine/palm</option>
            <option value="Pole">Pole</option>
            <option value="Power line">Power line small cell</option>
            <option value="Rooftop">Rooftop</option>
            <option value="Small cell">Small cell</option>
            <option value="Street Light">Street Light</option>
        </select>
		 <br>
		       <p>the carrier is</p>
           <select class="fakeinput dropdown" autocomplete="on" name="carrier">
             <option value=""> </option>
           <?php
            if('T-Mobile' == '' . $carrier . '') { echo '<option selected="selected" value="T-Mobile">T-Mobile</option>';} else { echo '<option value="T-Mobile">T-Mobile</option>';}
            if('ATT' == '' . $carrier . '') { echo '<option selected="selected" value="ATT">AT&T</option>';} else { echo '<option value="ATT">AT&T</option>';}
            if('Verizon' == '' . $carrier . '') { echo '<option selected="selected" value="Verizon">Verizon</option>';} else { echo '<option value="Verizon">Verizon</option>';}
            if('Sprint' == '' . $carrier . '') { echo '<option selected="selected" value="Sprint">Sprint</option>';} else { echo '<option value="Sprint">Sprint</option>';}
            ?>
          </select>
		 <p>the eNB id is</p>
		 <textarea class="fakeinput" style="resize: none;" rows="1" cols="30" maxlength="30"  placeholder="" name="id"></textarea>
		 <br>
      <input type="submit" class="submitbutton" value="Submit">
    </form>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.3/moment.min.js"></script>
      <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
      <script src="js/date.js"></script>
   </body>
</html>
