<?php
function mapWithPin($lat,$long,$zoom,$width,$height,$limit) { ?>
<iframe src="/database/Map.php?latitude=<?php echo $lat?>&longitude=<?php echo $long?>&marker_latitude=<?php echo $lat?>&marker_longitude=<?php echo $long?>&zoom=<?php echo $zoom?>&limit=<?php echo $limit?>"
  width="<?php echo $width?>" height="<?php echo $height?>" style="border:none;"></iframe>
<?php } ?>