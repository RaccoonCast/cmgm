<?php
function mapWithPin($gt_latitude,$gt_longitude,$gt_zoom,$gt_width,$gt_height) { ?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css" integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ==" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js" integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew==" crossorigin=""></script>
<div style="height: <?php echo $gt_height;?>; width: <?php echo $gt_width;?>" id="mapid"></div>
<script>
lat = <?php echo $gt_latitude?>;
long = <?php echo $gt_longitude?>;

var mymap = L.map('mapid',{ zoomControl: false }).setView([<?php echo $gt_latitude;?>,<?php echo $gt_longitude;?>], <?php echo $gt_zoom;?>);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
      minZoom: 4.5,
      tileSize: 256,
      zoomOffset: 0,
      accessToken: 'pk.eyJ1IjoicmFjY29vbmNhc3QiLCJhIjoiY2s3YjZ0cDViMDM3ODNncnlwdWY5M2VudCJ9.X_icvui90_cQLuP3VjG7BA'
    }).addTo(mymap);

    L.marker([<?php echo $gt_latitude;?>,<?php echo $gt_longitude;?>]).addTo(mymap);
    </script>
<?php } ?>
