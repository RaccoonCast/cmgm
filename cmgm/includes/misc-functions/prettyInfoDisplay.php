<?php
if(!isset($address)) $address = null;
if(!isset($city)) $city = null;
if(!isset($state)) $state = null;
if(!isset($zip)) $zip = null;
?>
<div class="prettyInfoDisplay">

<!-- SEARCH QUERY -->
<?php if (isset($data)) echo 'Search Query: ' . $data . "<br>"; ?>
<!-- SEARCH QUERY -->

<!-- LATITUDE / LONGITUDE -->
<div title="Click to copy latitude,longitude" onclick="copyToClipboard('<?php echo $latitude?>,<?php echo $longitude?>')">
  Latitude: <?php if (isset($latitude)) echo $latitude ?>
  <br>Longitude: <?php if (isset($longitude)) echo $longitude; ?>
</div>
<!-- LATITUDE / LONGITUDE -->

<!-- ADDRESS -->
<div title="Click to copy address" onclick="copyToClipboard('<?php if (isset($address)) {echo $address . ', ';}?><?php if (isset($city)) echo $city?>, <?php if (isset($state)) echo $state; ?> <?php if (isset($zip)) echo $zip; ?>')">
Address: <?php if (isset($address)) {echo $address . ', ';}?><?php if (isset($city)) echo $city?>, <?php if (isset($state)) echo $state; ?> <?php if (isset($zip)) echo $zip; ?></div>
<!-- ADDRESS -->
<div class="gmaps_api_link">
<?php
if(isset($url_1)) { ?> URL 1: <a class="gmaps_api_link" target="_blank" href="<?php echo $url_1; ?>"><?php echo substr($url_1,8,51).'...'; ?></a><br> <?php }
if(isset($url_2)) { ?> URL 2: <a class="gmaps_api_link" target="_blank" href="<?php echo $url_2; ?>"><?php echo substr($url_2,8,42).'...'; ?></a><br> <?php }
if(isset($conv_type)) { ?> Location Source: <?php echo $conv_type; ?><br> <?php } ?>
</div>
<script src="/js/copy.js"></script>
</div>
