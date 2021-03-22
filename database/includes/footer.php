<?php
   $latitude = substr("$latitude", 0, 9);
   $longitude = substr("$longitude", 0, 10);
   $pmlink = "../Home.php?latitude=$latitude&longitude=$longitude&address=$address&zip=$zip&city=$city&state=$state&permit_redirect=true";
   $cmlink = "../goto.php?goto_page=CellMapper&latitude=$latitude&longitude=$longitude";
   $db_map_link = "../goto.php?goto_page=Map&latitude=$latitude&longitude=$longitude";
   $db_list_link = "../goto.php?goto_page=DB&latitude=$latitude&longitude=$longitude";
   // $gm2link = "../goto.php?goto_page=LA Permit Map&latitude=$latitude&longitude=$longitude";
   $gmlink = "../goto.php?goto_page=Google Maps&latitude=$latitude&longitude=$longitude";
   ?>

   <footer>
     <a class="footerlink" onclick="copyToClipboard('<?php echo $address;?>')" href="<?php echo $pmlink?>">Permits</a>
     <a class="footerlink" target="_blank" href="<?php echo $cmlink?>">CellMapper</a>
     <a class="footerlink" target="_blank" href="<?php echo $db_list_link?>">DB-List</a>
     <a class="footerlink" target="_blank" href="<?php echo $db_map_link?>">DB-Maps</a>
     <a class="footerlink" target="_blank" href="<?php echo $gmlink?>">Google Maps</a>
     <!-- <a class="footerlink" target="_blank" href="<?php // echo $gm2link?>">Permits Map</a> -->
  </footer>
