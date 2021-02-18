<?php
echo '<div id="map" position="relative;">';

if(isMobile()){
  mapWithPin($latitude,$longitude,"21","380px","150px");
} else {
  mapWithPin($latitude,$longitude,"21","480px","300px");
}

echo '</div>';
?>
