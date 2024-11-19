<?php
// MapWithPin is used only on edit.php->the_form.php as a thumbnail view of tower location on CMGM Map
include '../includes/functions/mapWithPin.php';

// latitude, longitude, width as %, height as px, pin limit

if(isMobile()){
  MapWithPin($latitude,$longitude,"17","100%","250",$map_edit_mobile_pin_limit);
} else {
  MapWithPin($latitude,$longitude,"18","44%","530",$map_edit_pin_limit);
}

?>
