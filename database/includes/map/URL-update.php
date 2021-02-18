<?php ?> <script>
var centre = map.getView().getCenter();
var coord = ol.proj.toLonLat(centre);
var URI = "http://<?php echo $_SERVER['HTTP_HOST']?> + "map?carrier=" + carrier + "&latitude=" + coord[1] + "&longitude=" +coord[0] + "&zoom=" + map.getView().getZoom();
//Update URL same as link back address
<?php if ($_SERVER['HTTP_HOST'] == "cmgm.gq") { ?>URI = URI.replace("http://", "https://"); <?php } ?>
history.pushState("obj", "", URI);



$('#linkback').val(URI);
</script>
