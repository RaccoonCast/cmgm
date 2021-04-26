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

function changeFormAction() {
  document.getElementById("form").setAttribute('action', 'convert.php');
}

</script>
