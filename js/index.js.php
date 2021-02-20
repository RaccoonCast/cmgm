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

<?php
if (isset($cookie_latitude) && isset($cookie_longitude)) { ?>
function error(err) {
var cookie_latitude = "<?php echo $cookie_latitude?>";
var cookie_longitude = "<?php echo $cookie_longitude?>";
var url = "convert.php?data=" + cookie_latitude + "," + cookie_longitude;
window.location = url;
} <?php } else { ?>
function error(err) {
var preset_latitude = "38.89951743540001";
var preset_longitude = "-77.03655226691319";
var url = "convert.php?data=" + preset_latitude + "," + preset_longitude;
window.location = url;
} <?php } ?>

if (navigator.geolocation) {
  navigator.geolocation.getCurrentPosition(showPosition_2, error);
}

function showPosition_2(position) {
      var url = "convert.php?data=" + position.coords.latitude + "," + position.coords.longitude;
      window.location = url;
  }
}
function changeFormAction() {
  document.getElementById("form").setAttribute('action', 'convert.php');
}
function submit() {
  document.getElementById("form").submit();
}
</script>
<?php
