<?php
function MapWithPin($lat,$long,$zoom,$width,$height,$limit, $carrier, $userID, $eNB, $region_lte) { 
   // Add polygons to embed map
   include "../includes/functions/iframeAddPolygon.js.php";
   ?>

  <iframe src="/database/Map.php?latitude=<?php echo $lat?>&longitude=<?php echo $long?>&marker_latitude=<?php echo $lat?>&marker_longitude=<?php echo $long?>&zoom=<?php echo $zoom?>&limit=<?php echo $limit?>&pin_style=carrier&hideui=true"
  width="<?php echo $width?>" height="<?php echo $height?>" style="float: right; border:none; display: inline-block;" onload="changeUrl(this);"></iframe>
  <?php
}

// latitude, longitude, width as %, height as px, pin limit
if(isMobile()){
  MapWithPin($latitude,$longitude,"17","100%","250",$map_edit_mobile_pin_limit, $carrier, $userID, $LTE_1, $region_lte);
} else {
  MapWithPin($latitude,$longitude,"18","44%","530",$map_edit_pin_limit, $carrier, $userID, $LTE_1, $region_lte);
}
?>


