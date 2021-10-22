<?php
$multiplier = 1;
$modDigit = 0.000075;
$modDigit = $modDigit * $multiplier;
?>
<?php if($isMobile == "false") {?>
<a href="javascript:;" onclick="copyToClipboard('<?php echo $latitude + $modDigit . "," . $longitude - $modDigit ?>')" >R</a>
<a href="javascript:;" onclick="copyToClipboard('<?php echo $latitude + $modDigit . "," . $longitude ?>')">M</a>
<a href="javascript:;" onclick="copyToClipboard('<?php echo $latitude + $modDigit . "," . $longitude + $modDigit ?>')" >L</a>
<?php } ?>
<a href="javascript:;" class="pad-small-link" onclick="copyToClipboard('<?php echo $latitude . "," . $longitude; ?>')">Copy</a>
