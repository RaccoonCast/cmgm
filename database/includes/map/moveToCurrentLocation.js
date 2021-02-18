function moveToCurrentLocation()
{
  // Get location
  if(navigator.geolocation) {
  browserSupportFlag = true;
  navigator.geolocation.getCurrentPosition(function(position) {
    map.getView().setCenter(ol.proj.transform([position.coords.longitude, position.coords.latitude], 'EPSG:4326', 'EPSG:3857'));
  }, function() {


  });

  // Try Google Gears Geolocation

  } else if (google.gears) {
    browserSupportFlag = true;
    var geo = google.gears.factory.create('beta.geolocation');
    geo.getCurrentPosition(function(position) {
    centreMap(position.latitude,position.longitude);
  }, function() {

  });

  // Browser doesn't support Geolocation

  }
}
