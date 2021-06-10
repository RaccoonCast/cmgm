<!doctype html>
<html lang="en-us">
   <head>
     <script src="https://code.jquery.com/jquery-latest.min.js"></script>
     <script src="../js/pasteimages.js"></script>
     <script src="../js/database.js"></script>
     <?php include '../functions.php';
     if (!empty($_POST['data'])) {
       include SITE_ROOT . "/includes/home-functions/convert.php";
       [$latitude,$longitude,$carrier,$address,$zip,$city,$state,$goto,$conv_type] = convert($_POST['data'],"HomeSmart",$default_latitude,$default_longitude,$maps_api_key,$userIP,$default_carrier);
     }?>
   </head>
   <body>
     <div class="body">
     <?php
     // The cell site at? ... Make sure latitude and longitude show up in get query
     include  "includes/form/cellsite-at.php";

     // Ctrl+v paste images
     include  "includes/form/upload-form.php";

      // Map with
      include  "includes/form/mapWithPin.php";

      // The carrier is...? Multiple?
      include  "includes/form/dropdowns-carrier-info.php";

      // Evidence...?
      include  "includes/form/evidence.php";

      // Google Maps convert latitude and longitude to Physical Address
      include  "includes/form/box-address.php";

      // eNB ID...?
      include  "includes/form/enb-id.php";

      // Get textbox values from form.php
      include  "includes/form/evidence-stuff.php";

      // Bio/extra notes?
      include "includes/form/bio-n-etc.php";

      ?> <input type="submit" class="submitbutton" value="Submit"></div><br><br> <?php

      // Get footer
      include  "includes/footer.php";
      ?>

      <!-- Ladies and gentleman.. I would like to introduce you to... THE BUTTON?!?! -->


    </form>
   </body>
</html>
