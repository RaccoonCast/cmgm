<?php ?>
<script>
function myFunction() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(showPosition);
  }

function showPosition(position) {
      var url = "convert.php?data=" + position.coords.latitude + "," + position.coords.longitude;
      window.location = url;
  }
}

function locateMe() {

function error(err) {
  var cookie_latitude = "<?php echo $cookie_latitude?>"
  var cookie_longitude = "<?php echo $cookie_longitude?>"
  var url = "convert.php?data=" + cookie_latitude + "," + cookie_longitude;
  window.location = url;
}

if (navigator.geolocation) {
  navigator.geolocation.getCurrentPosition(showPosition_2, error);
}

function showPosition_2(position) {
      var url = "convert.php?data=" + position.coords.latitude + "," + position.coords.longitude;
      window.location = url;
  }
}
</script>
<?php