<?php
//if ($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] === 'upload.cmgm.us/') {
//  header("Location: https://upload.cmgm.us/", true, 301); die();
//}
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
header('Pragma: no-cache'); ?>
<!DOCTYPE html>
<html lang="en">
   <head>
     <script src="/js/copyToClipboard.js"></script>
      <?php
      $titleOverride = "true";
      $filename_for_css = "Home";
      if (isset($_GET['signOut'])) {
        echo '<script src="/js/setCookie.js"></script>';
        unset($_COOKIE['userID']);
        ?> <script>
          // Delete userID cookie
         setCookie("userID", "", "100", true); 
         // Remove '?signOut' from the URL
         history.replaceState(null, null, window.location.href.replace('?signOut', ''))
         </script> <?php
      }
      include "functions.php";
      if (isset($_GET['q'])) {
          // Redirect to CM for ATT Site IDs
          include "includes/functions/att-siteidconversion.php";
          // Redirect to LADBS for permits
          if (substr($_GET['q'], 5, 1) == "-" && substr($_GET['q'], 11, 1) == "-") {
            redir ("https://www.ladbsservices2.lacity.org/OnlineServices/PermitReport/PcisPermitDetail?id1=".substr($_GET['q'], 0, 5)."&id2=".substr($_GET['q'], 6, 5)."&id3=".substr($_GET['q'], 12, 5) ."","0");
            die();
        }
        // Redirect to CMGM-FAA for OE3A Caes
        if (isset($_GET['q']) && preg_match("/^(\d{2}|\d{4})-(\w+)-(\d+)-(OE|NRA)$/i", $_GET['q'], $faaMatches)) {
          redir("https://cmgm.us/faa?asn={$faaMatches[0]}", "0");
          die();
        }
      }


      include "js/index.js.php";
      include_once SITE_ROOT . "/includes/link-conversion-and-handling/function_goto.php";
      if (!isset($_GET['latitude']) OR !isset($_GET['longitude'])) include "includes/link-conversion-and-handling/page-handler.php";

      if (empty($address) OR $address == " ") echo "<title>Home</title>";
      if (!empty($address)) echo "<title>Home - ($address)</title>";
      ?>
   </head>
   <body class="flex">
     <?php include "includes/misc-functions/prettyInfoDisplay.php"; ?>
     <form action="/" method="post" autocomplete="off">
       <div class="buttons">
        <input type="text" name="data" title="You can search things like &#10;&#10;- Cell tower eNB -> Edit&#10;- 330 West 5th St -> Google Maps&#10;- Type location of site you're locating -> Form&#10;&#10;Location examples: addresses, lat,long, cellmapper links, google maps links, dms coordinates, etc."  placeholder="Search" value="" oninput="changeFormAction();" id="txtresult" class="textbox w-100">
        <input type="hidden" name="latitude" value="<?php echo $latitude;?>">
        <input type="hidden" name="longitude" value="<?php echo $longitude;?>">
        <input type="hidden" name="carrier" value="<?php echo $carrier;?>">
        <input type="hidden" name="address" value="<?php echo $address;?>">
        <input type="hidden" name="zip" value="<?php echo $zip;?>">
        <input type="hidden" name="city" value="<?php echo $city;?>">
        <input type="hidden" name="state" value="<?php echo $state;?>">
        <input type="hidden" value="false">
        <input
        type="button" class="cmgm-btn sb w-33" style="color:#FF0000" id="form" name="goto" onclick="redir('<?php echo function_goto($latitude,$longitude,@$carrier,@$address,@$zip,@$city,@$county,@$state,"Form",NULL,$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc,$cm_zoom,@$cm_netType) ?>');"value="Form"><input
        type="button" class="cmgm-btn sb w-33" style="color:#FF0000" id="edit" name="goto" onclick="redir('/database/Edit.php?q=<?php echo $latitude . "," . $longitude;?>');" value="Edit"><input
        type="button" class="cmgm-btn sb w-33" style="color:#FF0000" id="map" name="goto" onclick="redir('<?php echo function_goto($latitude,$longitude,@$carrier,@$address,@$zip,@$city,@$county,@$state,"Map",NULL,$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc,$cm_zoom,@$cm_netType) ?>');" value="Map"><input
        type="button" class="cmgm-btn sb w-100" style="color:#6BE63E" id="cellmapper" name="goto" onclick="redir('<?php echo function_goto($latitude,$longitude,@$carrier,@$address,@$zip,@$city,@$county,@$state,"CellMapper",NULL,$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc,$cm_zoom,@$cm_netType) ?>');" value="CellMapper"><input 
        type="button" class="cmgm-btn sb w-50" style="color:#5695F6" id="streetview" name="goto" onclick="redir('<?php echo function_goto($latitude,$longitude,@$carrier,@$address,@$zip,@$city,@$county,@$state,"Street View",NULL,$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc,$cm_zoom,@$cm_netType) ?>');" value="Street View"><input
        type="button" class="cmgm-btn sb w-50" style="color:#5695F6" id="maps" name="goto" onclick="redir('<?php echo function_goto($latitude,$longitude,@$carrier,@$address,@$zip,@$city,@$county,@$state,"Google Maps",NULL,$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc,$cm_zoom,@$cm_netType) ?>');" value="Google Maps"><input
        type="button" class="cmgm-btn sb w-50" style="color:#FFA500" id="lookaround" name="goto" onclick="redir('<?php echo function_goto($latitude,$longitude,@$carrier,@$address,@$zip,@$city,@$county,@$state,"Look Around",NULL,$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc,$cm_zoom,@$cm_netType) ?>');" value="Look Around"><input
        type="button" class="cmgm-btn sb w-50" style="color:#FFA500" id="birdseye" name="goto" onclick="redir('<?php echo function_goto($latitude,$longitude,@$carrier,@$address,@$zip,@$city,@$county,@$state,"Bird's Eye",NULL,$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc,$cm_zoom,@$cm_netType) ?>');" value="Bird's Eye"><input
        type="button" class="cmgm-btn sb w-33" style="color:#f731f7" id="search" name="goto" onclick="redir('<?php echo function_goto($latitude,$longitude,@$carrier,@$address,@$zip,@$city,@$county,@$state,"Search",NULL,$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc,$cm_zoom,@$cm_netType) ?>');" value="Search"><input
        type="button" class="cmgm-btn sb w-33" style="color:#f731f7" id="lart2150" name="goto" onclick="redir('<?php echo function_goto($latitude,$longitude,@$carrier,@$address,@$zip,@$city,@$county,@$state,"lart2150",NULL,$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc,$cm_zoom,@$cm_netType) ?>');" value="lart2150"><input
        type="button" class="cmgm-btn sb w-33" style="color:#f731f7" id="upload" name="goto" onclick="redir('https://upload.cmgm.us');" name="Upload" value="Upload"><input
        type="button" class="cmgm-btn sb w-50" style="color:#029F89" id="antennasearch" name="goto" onclick="redir('<?php echo function_goto($latitude,$longitude,@$carrier,@$address,@$zip,@$city,@$county,@$state,"AntennaSearch",NULL,$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc,$cm_zoom,@$cm_netType) ?>');" value="AntennaSearch"><input
        type="button" class="cmgm-btn sb w-25" style="color:#029F89" id="maprad" name="goto" onclick="redir('<?php echo function_goto($latitude,$longitude,@$carrier,@$address,@$zip,@$city,@$county,@$state,"Maprad",NULL,$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc,$cm_zoom,@$cm_netType) ?>');" value="Maprad"><input
        type="button" class="cmgm-btn sb w-25" style="color:#029F89" id="opencid" name="goto" onclick="redir('<?php echo function_goto($latitude,$longitude,@$carrier,@$address,@$zip,@$city,@$county,@$state,"OpenCID",NULL,$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc,$cm_zoom,@$cm_netType) ?>');" value="OpenCID"><input
        type="button" class="cmgm-btn sb w-100" style="color:#101010" id="settings" name="goto" onclick="redir('settings/');" value="Settings">
        <div id="tiauquote"><br><br><br>
         <span style="cursor: pointer;" onclick="redir('../fun/?limit=15')">“wayyy more user friendly than mymaps” <small>&#8209;thisisausername190.<small></span>
        </div>

     </form>
   </body>
</html>
