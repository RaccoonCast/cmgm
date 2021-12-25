<?php
// Multiplier
$multiplier = 1;
if (isset($_GET['multiplier'])) $multiplier = $_GET['multiplier'];
$modDigit = 0.00006;
$modDigit = $modDigit * $multiplier;
?>
<a href="Edit.php?id=<?php echo $id; ?>&multiplier=<?php echo $multiplier - 0.1; ?>">-</a>
<a class="pad-small-link-right" href="Edit.php?id=<?php echo $id; ?>&multiplier=<?php echo $multiplier + 0.1; ?>">+</a>
