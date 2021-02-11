<!doctype html>
<html lang="en-us">
   <head>
     <script src="https://code.jquery.com/jquery-latest.min.js"></script>
     <script src="../js/pasteimages.js"></script>
     <?php include '../functions.php';?>
   </head>
   <body>
     <div class="body">
     <?php

     // Ctrl+v paste images
     include  "includes/database-form/upload-form.php";

      // The cell site at? ... Make sure latitude and longitude show up in get query
      include  "includes/database-form/cellsite-at.php";

      // Map with
      include  "includes/database-form/mapWithPin.php";

      // The carrier is...? Multiple?
      include  "includes/database-form/carrier.php";

      // Evidence...?
      include  "includes/database-form/evidence.php";

      // Google Maps convert latitude and longitude to Physical Address
      include  "includes/database-form/google-maps.php";

      // eNB ID...?
      include  "includes/database-form/enb-id.php";

      // Get textbox values from database-form.php
      include  "includes/database-form/evidence-checkboxes.php";

      // Bio/extra notes?
      include "includes/database-form/bio.php";

      ?> <input type="submit" class="submitbutton" value="Submit"></div><br><br> <?php

      // Get footer
      include  "includes/database-form/footer.php";
      ?>

      <!-- Ladies and gentleman.. I would like to introduce you to... THE BUTTON?!?! -->


    </form>
   </body>
</html>
