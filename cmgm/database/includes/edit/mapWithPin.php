<?php
function MapWithPin($lat, $long, $zoom, $width, $height, $limit, $carrier, $userID, $eNB, $region_lte) {
    // Include polygons for embedding map
    include "../includes/functions/iframeAddPolygon.js.php";

	// Set variables for Map
	$url_variables = '?latitude=' . $lat . '&longitude=' . $long . '&marker_latitude=' . $lat . '&marker_longitude=' . $long . '&zoom=' . $zoom . '&limit=' . $limit . '&pin_style=carrier&hideui=true';
	
    // Make map
    echo '<iframe 
			src="/database/Map.php' . $url_variables . '"
			width="' . $width . '" 
			height="' . $height . '" 
			style="float: right; border: none; display: inline-block;" 
			onload="changeUrl(this);">
		</iframe>';
}

// Set parameters for mobile and desktop view
if (isMobile()) {
    MapWithPin($latitude, $longitude, "17", "100%", "250", $map_edit_mobile_pin_limit, $carrier, $userID, $LTE_1, $region_lte);
} else {
    MapWithPin($latitude, $longitude, "18", "44%", "530", $map_edit_pin_limit, $carrier, $userID, $LTE_1, $region_lte);
}
?>
