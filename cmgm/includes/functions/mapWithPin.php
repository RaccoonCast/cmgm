<?php
function MapWithPin($lat,$long,$zoom,$width,$height,$limit) { ?>
<iframe src="/database/Map.php?latitude=<?php echo $lat?>&longitude=<?php echo $long?>&marker_latitude=<?php echo $lat?>&marker_longitude=<?php echo $long?>&zoom=<?php echo $zoom?>&limit=<?php echo $limit?>&pin_style=carrier"
   width="<?php echo $width?>" height="<?php echo $height?>" style="float: right; border:none; display: inline-block;"></iframe>
<?php } ?>
