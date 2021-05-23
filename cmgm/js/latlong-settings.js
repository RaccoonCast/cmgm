function myFunction() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(showPosition);
  }

  function showPosition(position) {
      var latitude = position.coords.latitude;
      var longitude = position.coords.longitude;
      console.log(latitude);
      console.log(longitude);
      document.getElementById("latitude").value = latitude;
      document.getElementById("longitude").value = longitude;
  }
}
