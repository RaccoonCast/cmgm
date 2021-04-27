<script>
function error(err) {
window.location = "<?php convert($default_latitude,$default_longitude); ?>";
}

if (navigator.geolocation) {
  navigator.geolocation.getCurrentPosition(showPosition, error);
}

function showPosition(position) {
      window.location = "convert.php?data=" + position.coords.latitude + "," + position.coords.longitude;
  }
</script>
