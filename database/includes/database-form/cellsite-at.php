
<p>The cell site at</p>
<?php
$cmlink = "https://www.cellmapper.net/map?latitude=$latitude&longitude=$longitude&zoom=18&showTowerLabels=false";
echo '<a href="'.$cmlink.'" target="_blank">' . $latitude . ','  . $longitude . '</a>';
?>
   <form action="database-submit.php" method="get">
<input type="hidden" name="latitude" value="<?php echo $latitude;?>">
<input type="hidden" name="longitude" value="<?php echo $longitude;?>">
