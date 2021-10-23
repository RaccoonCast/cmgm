<?php
$multiplier = 1;
if (isset($_GET['multiplier'])) $multiplier = $_GET['multiplier'];
$modDigit = 0.00006;
$modDigit = $modDigit * $multiplier;
?>
<?php if($isMobile == "false") {?>
<?php if (!isset($delete) && !isset($_GET['new'])) {?>
<a style="color: blue;" href="Edit.php?id=<?php echo $id; ?>&multiplier=<?php echo $multiplier - 0.1; ?>">-</a>
<a style="color: blue;" href="Edit.php?id=<?php echo $id; ?>&multiplier=<?php echo $multiplier + 0.1; ?>">+</a>
<?php } ?>
<a href="javascript:;" onclick="copyToClipboard('<?php echo $latitude + $modDigit . "," . $longitude - $modDigit ?>')" >R</a>
<a href="javascript:;" onclick="copyToClipboard('<?php echo $latitude + $modDigit . "," . $longitude ?>')">M</a>
<a href="javascript:;" onclick="copyToClipboard('<?php echo $latitude + $modDigit . "," . $longitude + $modDigit ?>')" >L</a>
<?php } ?>
<a href="javascript:;" class="pad-small-link" onclick="copyToClipboard('<?php echo $latitude . "," . $longitude; ?>')">Copy</a>
