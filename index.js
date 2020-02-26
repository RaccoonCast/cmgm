function myFunction() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(showPosition);
  }

  function showPosition(position) {
    if (document.getElementById("txtresult").value == "") {
      var result = position.coords.latitude + "," + position.coords.longitude;

      document.getElementById("txtresult").value = result;
    }
  }
}

if (navigator.geolocation) {
  navigator.geolocation.getCurrentPosition(showPosition);
}

function showPosition(position) {
  var latitude = position.coords.latitude;
  var longitude = position.coords.longitude;
  var href = "dbinfo.php?latitude=" + latitude + "&longitude=" + longitude;
  var yourElement = document.getElementById("findlater");
  yourElement.setAttribute("href", href);
}
