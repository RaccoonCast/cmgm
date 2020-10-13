
<p  style="display: inline-block;" >For the cellsite at</p>
<?php
$cmlink = "https://www.cellmapper.net/map?latitude=$latitude&longitude=$longitude&zoom=18&showTowerLabels=false";
echo '<a style="display: inline-block;" title="' . $address . ', ' . $city . ', ' . $state . ' ' . $zip . '" href="'.$cmlink.'" target="_blank">' . $latitude . ','  . $longitude . '</a>';
?>
<p style="display: inline-block;">,</p>
<p style="display: inline-block;"> the carrier is</p>
   <form action="database-submit.php" method="get">
<input type="hidden" name="latitude" value="<?php echo $latitude;?>">
<input type="hidden" name="longitude" value="<?php echo $longitude;?>">
<input type="hidden" name="zip" value="<?php echo $zip;?>">
<input type="hidden" name="city" value="<?php echo $city;?>">
<input type="hidden" name="state" value="<?php echo $state;?>">
