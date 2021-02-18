<!doctype html>
<html lang="en">
  <head>
    <link rel="dns-prefetch" href="//code.jquery.com">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js" integrity="sha384-6ePHh72Rl3hKio4HiJ841psfsRJveeS+aLoaEf3BWfS+gTF0XdAqku2ka8VddikM" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" integrity="sha384-PtTRqvDhycIBU6x1wwIqnbDo8adeWIWP3AHmnrvccafo35E7oIvW7HPXn2YimvWu" crossorigin="anonymous"></script>
    <script type="text/javascript" src="includes/map/jquery/jquery.cookie.js"></script>
    <script type="text/javascript" src="includes/map/jquery/chosen.jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.js" integrity="sha384-ch1nZWLCNJ31V+4aC8U2svT7i40Ru+O8WHeLF4Mvq4aS7VD5ciODxwuOCdkIsX86" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.9/validator.min.js" integrity="sha384-38PYlsq0vWFYdiwAXpB0nFeTgsOMC8AyIFC5g/qDp0ihLbjeYiFAWz3rcNVR1+lI" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.5.0/css/ol.css" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" integrity="sha384-VK/ia2DWrvtO05YDcbWI8WE3WciOH0RhfPNuRJGSa3dpAs5szXWQuCnPNv/yzpO4" crossorigin="anonymous">
    <style>
      .map {
        height: 400px;
        width: 100%;
      }
    </style>
    <script src="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.5.0/build/ol.js"></script>
    <title>OpenLayers example</title>
  </head>
  <body>
    <h2>My Map</h2>
    <div id="map" class="map"></div>
    <script src="includes/map/pre-pre.js"></script>
    <script src="includes/map/getParamOrCookie.js"></script>
    <script src="includes/map/latitude-longitude.js"></script>
    <?php include "includes/map/URL-update.php" ?>
    <script src="includes/map/moveToCurrentLocation.js"></script>
    <script type="text/javascript">
      var map = new ol.Map({
        target: 'map',
        layers: [
          new ol.layer.Tile({
            source: new ol.source.OSM()
          })
        ],
        view: new ol.View({
          center: ol.proj.fromLonLat([37.41, 8.82]),
          zoom: 4
        })
      });
      console.log(params.latitude)
      console.log(params.longitude)

      if(params.latitude == null)
    		moveToCurrentLocation();
      else
        map.getView().setCenter(ol.proj.transform([longitude, latitude], 'EPSG:4326', 'EPSG:3857'));

      if(params.zoom != null)
        map.getView().setZoom(zoom);

    </script>
  </body>
</html>
