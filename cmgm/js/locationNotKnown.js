function error(err) {
  // window.location = "/?q=" + position.coords.latitude + "," + position.coords.longitude;
}

if (navigator.geolocation) {
  navigator.geolocation.getCurrentPosition(showPosition, error);
}

function showPosition(position) {
      location.href = "/?q=" + position.coords.latitude + "," + position.coords.longitude;
    }