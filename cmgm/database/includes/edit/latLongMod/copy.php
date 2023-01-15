<?php if(!isMobile()) {?>
<a href="javascript:;" class="nounderline pad-small-link" title="Copy latitude,longitude" onclick="copyToClipboard('<?php echo $latitude . "," . $longitude; ?>')">📋</a>
<a href="javascript:;" class="nounderline pad-small-link" title="Copy address" onclick="copyToClipboard('<?php echo $address . ", " . $city . ", " . $state . " " . $zip; ?>')">📋</a>
<!-- <a href="<?php // echo $smart_permit_search; ?>" class="nounderline pad-small-link">📋</a> -->
<?php } ?>
