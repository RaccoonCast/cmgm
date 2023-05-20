
<?php if (!isset($pin_size)) {
  if (isMobile()) {
    $pin_size = "22";
  } else {
    $pin_size = "20";
  }
}
 ?>
// regular

var unverifiedIcon = L.icon({ iconUrl: '../images/map_pins/red.png', iconSize: [20, 20], popupAnchor: [0, -12] });
var verifiedIcon = L.icon({ iconUrl: '../images/map_pins/green.png', iconSize: [20, 20], popupAnchor: [0, -12] });
var specialIcon = L.icon({ iconUrl: '../images/map_pins/purple.png', iconSize: [20, 20], popupAnchor: [0, -12] });
var weirdIcon = L.icon({ iconUrl: '../images/map_pins/blue.png', iconSize: [20, 20], popupAnchor: [0, -12] });
var unmappedIcon = L.icon({ iconUrl: '../images/map_pins/snowball.png', iconSize: [20, 20], popupAnchor: [0, -12] });
var wipIcon = L.icon({iconUrl: '../images/map_pins/green-light.png', iconSize: [20, 20], popupAnchor: [0, -12] });

// celltype

var darkgreenIcon = L.icon({ iconUrl: '../images/map_pins/green.png', iconSize: [<?php echo $pin_size?>, <?php echo $pin_size?>], popupAnchor: [0, -12] });
var lightgreenIcon = L.icon({ iconUrl: '../images/map_pins/green-lighter.png', iconSize: [<?php echo $pin_size?>, <?php echo $pin_size?>], popupAnchor: [0, -12] });
var darkgrayIcon = L.icon({ iconUrl: '../images/map_pins/gray-dark.png', iconSize: [<?php echo $pin_size?>, <?php echo $pin_size?>], popupAnchor: [0, -12] });
var lightgrayIcon = L.icon({ iconUrl: '../images/map_pins/gray-light.png', iconSize: [<?php echo $pin_size?>, <?php echo $pin_size?>], popupAnchor: [0, -12] });
var towerIcon = L.icon({ iconUrl: '../images/map_pins/tower.png', iconSize: [<?php echo $pin_size?>, <?php echo $pin_size?>], popupAnchor: [0, -12] });
var unknownIcon = L.icon({ iconUrl: '../images/map_pins/yellow.png', iconSize: [<?php echo $pin_size?>, <?php echo $pin_size?>], popupAnchor: [0, -12] });

// carrier

var tmobileIcon = L.icon({ iconUrl: '../images/map_pins/purple.png', iconSize: [<?php echo $pin_size?>, <?php echo $pin_size?>], popupAnchor: [0, -12] });
var verizonIcon = L.icon({ iconUrl: '../images/map_pins/red.png', iconSize: [<?php echo $pin_size?>, <?php echo $pin_size?>], popupAnchor: [0, -12] });
var dishIcon = L.icon({ iconUrl: '../images/map_pins/orange.png', iconSize: [<?php echo $pin_size?>, <?php echo $pin_size?>], popupAnchor: [0, -12] });
var attIcon = L.icon({ iconUrl: '../images/map_pins/blue.png', iconSize: [<?php echo $pin_size?>, <?php echo $pin_size?>], popupAnchor: [0, -12] });
var sprintIcon = L.icon({ iconUrl: '../images/map_pins/yellow.png', iconSize: [<?php echo $pin_size?>, <?php echo $pin_size?>], popupAnchor: [0, -12] });
var sprint_keepIcon = L.icon({ iconUrl: '../images/map_pins/purple-yellow.png', iconSize: [<?php echo $pin_size?>, <?php echo $pin_size?>], popupAnchor: [0, -12] });
