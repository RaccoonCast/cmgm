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
     include  "includes/form/upload-form.php";

      // The cell site at? ... Make sure latitude and longitude show up in get query
      include  "includes/form/cellsite-at.php";

      // Map with
      include  "includes/form/mapWithPin.php";

      // The carrier is...? Multiple?
      include  "includes/form/carrier.php";

      // Evidence...?
      include  "includes/form/evidence.php";

      // Google Maps convert latitude and longitude to Physical Address
      include  "includes/form/google-maps.php";

      // eNB ID...?
      include  "includes/form/enb-id.php";

      // Get textbox values from form.php
      include  "includes/form/evidence-checkboxes.php";

      // Bio/extra notes?
      include "includes/form/bio.php";

      ?> <input type="submit" class="submitbutton" value="Submit"></div><br><br> <?php

      // Get footer
      include  "includes/footer.php";
      ?>

      <!-- Ladies and gentleman.. I would like to introduce you to... THE BUTTON?!?! -->


    </form>
   </body>
</html>
