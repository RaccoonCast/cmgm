<?php if(!isMobile()) {?>
<a href="javascript:;" class="nounderline pad-small-link" onclick="copyToClipboard('<?php echo $latitude . "," . $longitude; ?>')">📋</a>
<a href="javascript:;" class="nounderline pad-small-link" onclick="copyToClipboard('<?php echo $address . ", " . $city . ", " . $state . " " . $zip; ?>')">📋</a>
<!-- <a href="<?php // echo $smart_permit_search; ?>" class="nounderline pad-small-link">📋</a> -->
<?php } ?>
