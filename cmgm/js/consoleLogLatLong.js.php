<?php ?>
<script>
function getLocation() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(showPosition, positionError);
  } else {
    console.log("Geolocation is not supported by this browser.");
  }
}

function showPosition(position) {
  // Success, can use position.
  console.log("Your position is: " + position.coords.latitude + "," + position.coords.longitude);
}

function positionError(error) {
  if (error.PERMISSION_DENIED) {
    console.log("Error: permission denied");
    // Your custom modal here.
    showError('Geolocation is not enabled. Please enable to use this feature.');
  } else {
    // Handle other kinds of errors.
    console.log("Other kind of error: " + error);
  }
}

function showError(message) {
  // TODO
}

getLocation();
</script>
