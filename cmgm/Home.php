<?php
header('Expires: Sun, 01 Jan 2014 00:00:00 GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
header('Pragma: no-cache'); ?>
<!DOCTYPE html>
<html lang="en">
   <head>
     <script src="/js/copyToClipboard.js"></script>
      <?php
      $titleOverride = "true";
      include "functions.php";
      if (substr(@$_GET['q'], 5, 1) == "-" && substr(@$_GET['q'], 11, 1) == "-") {
        redir ("https://www.ladbsservices2.lacity.org/OnlineServices/PermitReport/PcisPermitDetail?id1=".substr($_GET['q'], 0, 5)."&id2=".substr($_GET['q'], 6, 5)."&id3=".substr($_GET['q'], 12, 5) ."","0");
        // echo "https://www.ladbsservices2.lacity.org/OnlineServices/PermitReport/PcisPermitDetail?id1=".substr($_GET['q'], 0, 5)."&id2=".substr($_GET['q'], 6, 5)."&id3=".substr($_GET['q'], 12, 5) ."";
        die();
      }
      include "js/index.js.php";
      if (!isset($_GET['latitude']) OR !isset($_GET['longitude'])) include "includes/link-conversion-and-handling/page-handler.php";
      include_once SITE_ROOT . "/includes/link-conversion-and-handling/function_goto.php";

      if (empty($address) OR $address == " ") echo "<title>Home</title>";
      if (!empty($address)) echo "<title>Home - ($address)</title>";
      ?>
   </head>
   <body class="flex">
     <?php include "includes/misc-functions/prettyInfoDisplay.php"; ?>
     <form id="form" action="/" method="post" autocomplete="off">
       <div class="buttons">
        <input type="text" name="data" value="" oninput="changeFormAction();" id="txtresult" class="textbox w-100">
        <input type="hidden" name="latitude" value="<?php echo $latitude;?>">
        <input type="hidden" name="longitude" value="<?php echo $longitude;?>">
        <input type="hidden" name="carrier" value="<?php echo $carrier;?>">
        <input type="hidden" name="address" value="<?php echo $address;?>">
        <input type="hidden" name="zip" value="<?php echo $zip;?>">
        <input type="hidden" name="city" value="<?php echo $city;?>">
        <input type="hidden" name="state" value="<?php echo $state;?>">
        <input type="hidden" value="false"><input
        type="button" class="cmgm-btn sb w-33" style="color:#FF0000" id="link01" name="goto" onclick="redir('<?php echo function_goto($latitude,$longitude,@$carrier,@$address,@$zip,@$city,@$state,"Form",NULL,$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc,$cm_zoom,@$cm_netType) ?>');"value="Form"><input
        type="button" class="cmgm-btn sb w-33" style="color:#FF0000" id="link02" name="goto" onclick="redir('/database/Edit.php?id=<?php echo $latitude . "," . $longitude;?>');" value="Edit"><input
        type="button" class="cmgm-btn sb w-33" style="color:#FF0000" id="link03" name="goto" onclick="redir('<?php echo function_goto($latitude,$longitude,@$carrier,@$address,@$zip,@$city,@$state,"Map",NULL,$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc,$cm_zoom,@$cm_netType) ?>');" value="Map"><input
        type="button" class="cmgm-btn sb w-50" style="color:#6BE63E" id="link04" name="goto" onclick="redir('<?php echo function_goto($latitude,$longitude,@$carrier,@$address,@$zip,@$city,@$state,"CellMapper",NULL,$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc,$cm_zoom,@$cm_netType) ?>');" value="CellMapper"><input
        type="button" class="cmgm-btn sb w-50" style="color:#6BE63E" id="link05" name="goto" onclick="redir('<?php echo function_goto($latitude,$longitude,@$carrier,@$address,@$zip,@$city,@$state,"Beta",NULL,$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc,$cm_zoom,@$cm_netType) ?>');" value="Beta"><input
        type="button" class="cmgm-btn sb w-37-5" style="color:#5695F6" id="link07" name="goto" onclick="redir('<?php echo function_goto($latitude,$longitude,@$carrier,@$address,@$zip,@$city,@$state,"Street View",NULL,$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc,$cm_zoom,@$cm_netType) ?>');" value="Street View"><input
        type="button" class="cmgm-btn sb w-25-l" style="color:#5695F6" id="link06" name="goto" onclick="redir('<?php echo function_goto($latitude,$longitude,@$carrier,@$address,@$zip,@$city,@$state,"Maps",NULL,$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc,$cm_zoom,@$cm_netType) ?>');" value="Maps"><input
        type="button" class="cmgm-btn sb w-37-5" style="color:#5695F6" id="link14" name="goto" onclick="redir('<?php echo function_goto($latitude,$longitude,@$carrier,@$address,@$zip,@$city,@$state,"Bird's Eye",NULL,$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc,$cm_zoom,@$cm_netType) ?>');" value="Bird's Eye"><input
        type="button" class="cmgm-btn sb w-50" style="color:#f731f7" id="link08" name="goto" onclick="redir('<?php echo function_goto($latitude,$longitude,@$carrier,@$address,@$zip,@$city,@$state,"Search",NULL,$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc,$cm_zoom,@$cm_netType) ?>');" value="Search"><input
        type="button" class="cmgm-btn sb w-50" style="color:#f731f7" id="link09" name="goto" onclick="redir('database/Upload.php');" name="Upload" value="Upload"><input
        type="button" class="cmgm-btn sb w-50" style="color:#029F89" id="link10" name="goto" onclick="redir('<?php echo function_goto($latitude,$longitude,@$carrier,@$address,@$zip,@$city,@$state,"AntennaSearch",NULL,$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc,$cm_zoom,@$cm_netType) ?>');" value="AntennaSearch"><input
        type="button" class="cmgm-btn sb w-25" style="color:#029F89" id="link11" name="goto" onclick="redir('<?php echo function_goto($latitude,$longitude,@$carrier,@$address,@$zip,@$city,@$state,"Maprad",NULL,$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc,$cm_zoom,@$cm_netType) ?>');" value="Maprad"><input
        type="button" class="cmgm-btn sb w-25" style="color:#029F89" id="link12" name="goto" onclick="redir('<?php echo function_goto($latitude,$longitude,@$carrier,@$address,@$zip,@$city,@$state,"Permits",NULL,$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc,$cm_zoom,@$cm_netType) ?>');" value="Permits"><input
        type="button" class="cmgm-btn sb w-100" style="color:#101010" id="link13" name="goto" onclick="redir('settings/');" value="Settings">
     </form>
   </body>
</html>
