function myFunction() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(showPosition);
  }

  function showPosition(position) {
    if (document.getElementById("latitude").value == "") {
      var latitude = position.coords.latitude;
      var longitude = position.coords.longitude;

      document.getElementById("latitude").value = latitude;
      document.getElementById("longitude").value = longitude;
    }
  }
}

if (navigator.geolocation) {
  navigator.geolocation.getCurrentPosition(showPosition);
}
