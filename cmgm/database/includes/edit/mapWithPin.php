<?php
include '../includes/functions/MapWithPin.php';

if(isMobile()){
  MapWithPin($latitude,$longitude,"17","100%","250","25");
} else {
  MapWithPin($latitude,$longitude,"18","50%","530","95");
}

?>
