<?php
if(!isset($address)) $address = null;
if(!isset($city)) $city = null;
if(!isset($state)) $state = null;
if(!isset($zip)) $zip = null;
?>
<div title="Click to copy latitude,longitude" onclick="copyToClipboard('<?php echo $latitude?>,<?php echo $longitude?>')">
Latitude: <?php if (isset($latitude)) echo $latitude ?>
<br> Longitude: <?php if (isset($longitude)) echo $longitude; ?>
</div>
<?php if (isset($data)) echo 'Search Query: ' . $data; ?>
<div title="Click to copy address" onclick="copyToClipboard('<?php if (isset($address)) {echo $address . ', ';}?><?php if (isset($city)) echo $city?>, <?php if (isset($state)) echo $state; ?> <?php if (isset($zip)) echo $zip; ?>')">
Address: <?php if (isset($address)) {echo $address . ', ';}?><?php if (isset($city)) echo $city?>, <?php if (isset($state)) echo $state; ?> <?php if (isset($zip)) echo $zip; ?>
</div>
<script src="/js/copy.js"></script>
