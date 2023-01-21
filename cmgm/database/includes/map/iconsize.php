// regular

<?php if (!isset($pin_size)) {
  if (isMobile()) {
    $pin_size = "22";
  } else {
    $pin_size = "20";
  }
}
 ?>

var unverified = L.icon({ iconUrl: '../images/map_pins/red.png', iconSize: [<?php echo $pin_size?>, <?php echo $pin_size?>], popupAnchor: [0, -12] });
var verified = L.icon({ iconUrl: '../images/map_pins/green.png', iconSize: [<?php echo $pin_size?>, <?php echo $pin_size?>], popupAnchor: [0, -12] });
var special = L.icon({ iconUrl: '../images/map_pins/purple.png', iconSize: [<?php echo $pin_size?>, <?php echo $pin_size?>], popupAnchor: [0, -12] });
var weird = L.icon({ iconUrl: '../images/map_pins/blue.png', iconSize: [<?php echo $pin_size?>, <?php echo $pin_size?>], popupAnchor: [0, -12] });
var unmapped = L.icon({ iconUrl: '../images/map_pins/snowball.png', iconSize: [<?php echo $pin_size?>, <?php echo $pin_size?>], popupAnchor: [0, -12] });
var wip = L.icon({iconUrl: '../images/map_pins/green-light.png', iconSize: [<?php echo $pin_size?>, <?php echo $pin_size?>], popupAnchor: [0, -12] });

// celltype

var darkgreen = L.icon({ iconUrl: '../images/map_pins/green.png', iconSize: [<?php echo $pin_size?>, <?php echo $pin_size?>], popupAnchor: [0, -12] });
var lightgreen = L.icon({ iconUrl: '../images/map_pins/green-lighter.png', iconSize: [<?php echo $pin_size?>, <?php echo $pin_size?>], popupAnchor: [0, -12] });
var darkgray = L.icon({ iconUrl: '../images/map_pins/gray-dark.png', iconSize: [<?php echo $pin_size?>, <?php echo $pin_size?>], popupAnchor: [0, -12] });
var lightgray = L.icon({ iconUrl: '../images/map_pins/gray-light.png', iconSize: [<?php echo $pin_size?>, <?php echo $pin_size?>], popupAnchor: [0, -12] });
var tower = L.icon({ iconUrl: '../images/map_pins/tower.png', iconSize: [<?php echo $pin_size?>, <?php echo $pin_size?>], popupAnchor: [0, -12] });
var unknown = L.icon({ iconUrl: '../images/map_pins/yellow.png', iconSize: [<?php echo $pin_size?>, <?php echo $pin_size?>], popupAnchor: [0, -12] });

// carrier

var tmobile = L.icon({ iconUrl: '../images/map_pins/purple.png', iconSize: [<?php echo $pin_size?>, <?php echo $pin_size?>], popupAnchor: [0, -12] });
var verizon = L.icon({ iconUrl: '../images/map_pins/red.png', iconSize: [<?php echo $pin_size?>, <?php echo $pin_size?>], popupAnchor: [0, -12] });
var dish = L.icon({ iconUrl: '../images/map_pins/orange.png', iconSize: [<?php echo $pin_size?>, <?php echo $pin_size?>], popupAnchor: [0, -12] });
var att = L.icon({ iconUrl: '../images/map_pins/blue.png', iconSize: [<?php echo $pin_size?>, <?php echo $pin_size?>], popupAnchor: [0, -12] });
var sprint = L.icon({ iconUrl: '../images/map_pins/yellow.png', iconSize: [<?php echo $pin_size?>, <?php echo $pin_size?>], popupAnchor: [0, -12] });
var sprint_keep = L.icon({ iconUrl: '../images/map_pins/purple-yellow.png', iconSize: [<?php echo $pin_size?>, <?php echo $pin_size?>], popupAnchor: [0, -12] });
