<!doctype html>
<html lang="en-us">
   <head>
     <script src="https://code.jquery.com/jquery-latest.min.js"></script>
     <script src="../js/pasteimages.js"></script>
     <?php include '../functions.php';?>
   </head>
   <body>
     <?php

     // Ctrl+v paste images
     include  "includes/database-form/upload-form.php";

     // Get footer
     include  "includes/database-form/footer.php";

      // The cell site at? ... Make sure latitude and longitude show up in get query
      include  "includes/database-form/cellsite-at.php";

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
      include "includes/database-form/bio.php"
      ?>

      <!-- Ladies and gentleman.. I would like to introduce you to... THE BUTTON?!?! -->

      <input type="submit" class="submitbutton" value="Submit">
    </form>
   </body>
</html>
