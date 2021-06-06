<script>
function error(err) {
}

if (navigator.geolocation) {
  navigator.geolocation.getCurrentPosition(showPosition, error);
}

function showPosition(position) {
      echo 'window.location = "Home.php?goto=HomeSmart&data=" + position.coords.latitude + "," + position.coords.longitude;';
    }
</script>
