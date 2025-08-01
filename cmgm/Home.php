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
        <?php
        $user_data = [
            'latitude'       => $latitude,
            'longitude'      => $longitude,
            'carrier'        => @$carrier,
            'address'        => @$address,
            'zip'            => @$zip,
            'city'           => @$city,
            'county'         => @$county,
            'state'          => @$state,
            'conv_type'       => NULL,
            'mapType'        => $cm_mapType,
            'cm_groupTowers'    => $cm_groupTowers,
            'cm_showLabels'     => $cm_showLabels,
            'cm_showLowAcc'     => $cm_showLowAcc,
            'cm_zoom'           => $cm_zoom,
            'cm_mapType'        => @$cm_mapType,
            'cm_netType'        => @$cm_netType,
        ];
        ?>
        <input type="text" name="data" title="You can search things like &#10;&#10;- Cell tower eNB -> Edit&#10;- 330 West 5th St -> Google Maps&#10;- Type location of site you're locating -> Form&#10;&#10;Location examples: addresses, lat,long, cellmapper links, google maps links, dms coordinates, etc."  placeholder="Search" value="" oninput="changeFormAction();" id="txtresult" class="textbox w-100">
        <!--
        <input type="hidden" name="latitude" value="<?= $latitude;?>">
        <input type="hidden" name="longitude" value="<?= $longitude;?>">
        <input type="hidden" name="carrier" value="<?= $carrier;?>">
        <input type="hidden" name="address" value="<?= $address;?>">
        <input type="hidden" name="zip" value="<?= $zip;?>">
        <input type="hidden" name="city" value="<?= $city;?>">
        <input type="hidden" name="state" value="<?= $state;?>">
        <input type="hidden" value="false">
        -->
        <input
        type="button" style="color:#FF0000" name="goto" class="cmgm-btn sb w-033 usr_btns" onclick="redir('<?= function_goto($user_data, "Form") ?>');"value="Form"><input
        type="button" style="color:#FF0000" name="goto" class="cmgm-btn sb w-033 usr_btns" onclick="redir('/database/Edit.php?q=<?= $latitude . "," . $longitude;?>');" value="Edit"><input
        type="button" style="color:#FF0000" name="goto" class="cmgm-btn sb w-033 usr_btns" onclick="redir('<?= function_goto($user_data, "Map") ?>');" value="Map"><input
        type="button" style="color:#6BE63E" name="goto" class="cmgm-btn sb w-100 usr_btns" onclick="redir('<?= function_goto($user_data, "CellMapper") ?>');" value="CellMapper"><input 
        type="button" style="color:#5695F6" name="goto" class="cmgm-btn sb w-050 usr_btns" onclick="redir('<?= function_goto($user_data, "Street View") ?>');" value="Street View"><input
        type="button" style="color:#5695F6" name="goto" class="cmgm-btn sb w-050 usr_btns" onclick="redir('<?= function_goto($user_data, "Google Maps") ?>');" value="Google Maps"><input
        type="button" style="color:#FFA500" name="goto" class="cmgm-btn sb w-050 usr_btns" onclick="redir('<?= function_goto($user_data, "Look Around") ?>');" value="Look Around"><input
        type="button" style="color:#FFA500" name="goto" class="cmgm-btn sb w-050 usr_btns" onclick="redir('<?= function_goto($user_data, "Bird's Eye") ?>');" value="Bird's Eye"><input
        type="button" style="color:#f731f7" name="goto" class="cmgm-btn sb w-033 usr_btns" onclick="redir('<?= function_goto($user_data, "Search") ?>');" value="Search"><input
        type="button" style="color:#f731f7" name="goto" class="cmgm-btn sb w-033 usr_btns" onclick="redir('<?= function_goto($user_data, "lart2150") ?>');" value="lart2150"><input
        type="button" style="color:#f731f7" name="goto" class="cmgm-btn sb w-033" onclick="redir('https://upload.cmgm.us');" name="Upload" value="Upload"><input
        type="button" style="color:#029F89" name="goto" class="cmgm-btn sb w-050 usr_btns" onclick="redir('<?= function_goto($user_data, "AntennaSearch") ?>');" value="AntennaSearch"><input
        type="button" style="color:#029F89" name="goto" class="cmgm-btn sb w-025 usr_btns" onclick="redir('<?= function_goto($user_data, "Maprad") ?>');" value="Maprad"><input
        type="button" style="color:#029F89" name="goto" class="cmgm-btn sb w-025 usr_btns" onclick="redir('<?= function_goto($user_data, "OpenCID") ?>');" value="OpenCID"><input
        type="button" style="color:#fcdf03" name="goto" class="cmgm-btn sb w-025" onclick="redir('../poly/');" value="Poly"><input
        type="button" style="color:#fcdf03" name="goto" class="cmgm-btn sb w-025 usr_btns" onclick="redir('<?= function_goto($user_data, "Poly Map") ?>');" value="Poly Map"><input
        type="button" style="color:#029F89" name="goto" class="cmgm-btn sb w-025" onclick="redir('../fun/?limit=15');" value="Stats"><input
        type="button" style="color:#029F89" name="goto" class="cmgm-btn sb w-025 usr_btns" onclick="redir('../faa/');" value="FAA"><input
        type="button" style="color:#101010" name="goto" class="cmgm-btn sb w-100" onclick="redir('settings/');" value="Settings">
        <!--
        <div id="tiauquote"><br><br><br>
         <span style="cursor: pointer;" onclick="redir('../fun/?limit=15')">“wayyy more user friendly than mymaps” <small>&#8209;thisisausername190.<small></span>
        </div>
        -->

     </form>
   </body>
</html>
