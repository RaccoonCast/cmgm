<?php
echo '<div id="map" position="relative;">';

if(isMobile()){
  mapWithPin($latitude,$longitude,"20","380px","150px");
} else {
  mapWithPin($latitude,$longitude,"20","480px","300px");
}

echo '</div>';
?>
