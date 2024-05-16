<?php
// MapWithPin is used only on edit.php->the_form.php as a thumbnail view of tower location on CMGM Map
include '../includes/functions/mapWithPin.php';

// latitude, longitude, width as %, height as px, pin limit

if(isMobile()){
  MapWithPin($latitude,$longitude,"17","100%","250","25");
} else {
  MapWithPin($latitude,$longitude,"18","44%","530","95");
}

?>
