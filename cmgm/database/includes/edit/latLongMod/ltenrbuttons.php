<input type="button" value="A" id="pin-all-button" style="padding: 0em; margin: 0em; cursor: pointer;" class="pad-small-link pad-small-link-mobile2">
<script>
document.getElementById("pin-all-button").addEventListener("click", function() {
  <?php if (isset($LTE_1_mv) && $other_user_map_primary != "true") { echo 'window.open("'. $LTE_1_mv . '", "_blank", "noreferrer, noopener");' . PHP_EOL; }
  if (isset($LTE_2_mv)) { echo 'window.open("'. $LTE_2_mv . '", "_blank", "noreferrer, noopener");' . PHP_EOL; }
  if (isset($LTE_3_mv)) { echo 'window.open("'. $LTE_3_mv . '", "_blank", "noreferrer, noopener");' . PHP_EOL; }
  if (isset($LTE_4_mv)) { echo 'window.open("'. $LTE_4_mv . '", "_blank", "noreferrer, noopener");' . PHP_EOL; }
  if (isset($NR_1_mv))  { echo 'window.open("'. $NR_1_mv  . '", "_blank", "noreferrer, noopener");' . PHP_EOL; }
  if (isset($NR_2_mv))  { echo 'window.open("'. $NR_2_mv  . '", "_blank", "noreferrer, noopener");' . PHP_EOL; }
  if (isset($NR_3_mv))  { echo 'window.open("'. $NR_3_mv  . '", "_blank", "noreferrer, noopener");' . PHP_EOL; }
  ?>
  return false;
});
</script>
<?php if (isset($LTE_1_mv) && ($other_user_map_primary == "true")) { ?> <a rel="noreferrer noopener" class="pad-small-link pad-small-link-mobile2 error" target="_blank" href="<?php if (isset($LTE_1_mv)) echo $LTE_1_mv; ?>" >1</a><?php } ?>
<?php if (isset($LTE_1_mv) && ($other_user_map_primary == "")) { ?><a
rel="noreferrer noopener" class="pad-small-link pad-small-link-mobile2 warning" target="_blank" href="<?php if (isset($LTE_1_mv)) echo $LTE_1_mv; ?>" ><?php if (isMobile()) echo "LTE_"?>1</a><?php } ?><?php if (isset($LTE_1_mv) && ($other_user_map_primary == "false")) { ?><a
rel="noreferrer noopener" class="pad-small-link pad-small-link-mobile2" target="_blank" href="<?php if (isset($LTE_1_mv)) echo $LTE_1_mv; ?>" ><?php if (isMobile()) echo "LTE_"?>1</a><?php } if (isset($LTE_2_mv)) { ?><a
rel="noreferrer noopener" class="pad-small-link pad-small-link-mobile2" target="_blank" href="<?php if (isset($LTE_2_mv)) echo $LTE_2_mv; ?>" ><?php if (isMobile()) echo "LTE_"?>2</a><?php } if (isset($LTE_3_mv)) { ?><a
rel="noreferrer noopener" class="pad-small-link pad-small-link-mobile2" target="_blank" href="<?php if (isset($LTE_3_mv)) echo $LTE_3_mv; ?>" ><?php if (isMobile()) echo "LTE_"?>3</a><?php } if (isset($LTE_4_mv)) { ?><a
rel="noreferrer noopener" class="pad-small-link pad-small-link-mobile2" target="_blank" href="<?php if (isset($LTE_4_mv)) echo $LTE_4_mv; ?>" ><?php if (isMobile()) echo "LTE_"?>4</a><?php } ?>

<?php
if (isset($NR_1_mv) OR isset($NR_2_mv) OR isset($NR_3_mv)) { ?><p class="pad-small-link pad-small-link-mobile3">-</p><?php }
if (isset($NR_1_mv)) { ?><a class="pad-small-link pad-small-link-mobile2" rel="noreferrer noopener" target="_blank" href="<?php if (isset($NR_1_mv)) { echo $NR_1_mv; }?>"><?php if (isMobile()) echo "NR_"?>1</a><?php }
if (isset($NR_2_mv)) { ?><a class="pad-small-link pad-small-link-mobile2" rel="noreferrer noopener" target="_blank" href="<?php if (isset($NR_2_mv)) { echo $NR_2_mv; }?>"><?php if (isMobile()) echo "NR_"?>2</a><?php }
if (isset($NR_3_mv)) { ?><a class="pad-small-link pad-small-link-mobile2" rel="noreferrer noopener" target="_blank" href="<?php if (isset($NR_3_mv)) { echo $NR_3_mv; }?>"><?php if (isMobile()) echo "NR_"?>3</a><?php } ?>
