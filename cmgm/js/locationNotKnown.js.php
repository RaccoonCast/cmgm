<script>
function error(err) {
window.location = "<?php ob_start(); $url = convert($default_latitude,$default_longitude); ob_end_clean(); echo $url;?>";
}

if (navigator.geolocation) {
  navigator.geolocation.getCurrentPosition(showPosition, error);
}

function showPosition(position) {
      window.location = "convert.php?data=" + position.coords.latitude + "," + position.coords.longitude;
  }
</script>
