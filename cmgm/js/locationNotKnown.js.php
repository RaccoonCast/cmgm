<script>
function error(err) {
var db_latitude = "<?php echo $default_latitude?>";
var db_longitude = "<?php echo $default_longitude?>";
window.location = "convert.php?data=" + db_latitude + "," + db_longitude;
}

if (navigator.geolocation) {
  navigator.geolocation.getCurrentPosition(showPosition, error);
}

function showPosition(position) {
      window.location = "convert.php?data=" + position.coords.latitude + "," + position.coords.longitude;
  }
</script>
