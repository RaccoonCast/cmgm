<!doctype html>
<html lang="en">
<head>
  <?php include "functions.php";
  if(isMobile()){
    echo '<link rel="stylesheet" href="styles/Hub/mobile.css">';
  } else {
    echo '<link rel="stylesheet" href="styles/Hub/desktop.css">';
  }
  ?>
</head>
<body class="flex">
  <?php
  $url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($latitude).','.trim($longitude).'&key=' . $api_key . '';
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
  $response = curl_exec($ch);
  curl_close($ch);

  // Parse the json output

   $response = json_decode($response);


   $addressComponents = $response->results[0]->address_components;
   foreach ($addressComponents as $addrComp) {
       if ($addrComp->types[0] == 'postal_code') $zip = $addrComp->long_name;
       if ($addrComp->types[0] == 'street_number') $number = $addrComp->short_name;
       if ($addrComp->types[0] == 'route') $short_street_name = $addrComp->short_name; $long_street_name = $addrComp->long_name;
       if ($addrComp->types[0] == 'locality') $city = $addrComp->short_name;
       if ($addrComp->types[0] == 'administrative_area_level_1') $state = $addrComp->short_name;
       }

    $directionCheck_a = explode(' ',trim($short_street_name));
    $directionCheck_b = explode(' ',trim($long_street_name));

    if ("$directionCheck_a[0]" == "N" && "$directionCheck_b[0]" == "North") {$short_street_name = substr($short_street_name,2); }
    if ("$directionCheck_a[0]" == "E" && "$directionCheck_b[0]" == "East")  {$short_street_name = substr($short_street_name,2); }
    if ("$directionCheck_a[0]" == "S" && "$directionCheck_b[0]" == "South") {$short_street_name = substr($short_street_name,2); }
    if ("$directionCheck_a[0]" == "W" && "$directionCheck_b[0]" == "West")  {$short_street_name = substr($short_street_name,2); }
    // REMOVE THE LAST WORD

    $words = explode(' ',$short_street_name);
    $noofwords = count($words);
    unset($words[$noofwords-1]);
    $street_name = implode(' ',$words);

    $text = "$number $street_name";
    echo $text
  ?>

   <script>
   var copy = "<?php echo $text ?>"
   copyToClipboard(copy);

    function copyToClipboard(text) {
       var dummy = document.createElement("textarea");
       document.body.appendChild(dummy);
       dummy.value = text;
       dummy.select();
       document.execCommand("copy");
       document.body.removeChild(dummy);
   }
  </script>
  <?php
    if ("$city" == "Glendale") {
      echo '<meta http-equiv="Refresh" content="0; url=https://csi.glendaleca.gov/csipropertyportal/" />';
    } elseif ("$city" == "Los Angeles") {
      echo '<meta http-equiv="Refresh" content="0; url=http://ladbsdoc.lacity.org/idispublic/" />';
    } elseif ("$city" == "Long Beach") {
      echo '<meta http-equiv="Refresh" content="0; url=http://citydocs.longbeach.gov/WebLink8/CustomSearch.aspx?SearchName=SearchbyAddress" />';
    } elseif ("$city" == "Burbank") {
      echo '<meta http-equiv="Refresh" content="0; url=https://permit.burbankca.gov/epalspi/" />';
    } elseif ("$city" == "Pasadena") {
      echo '<meta http-equiv="Refresh" content="0; url=https://eservices.cityofpasadena.net/iwrplandev/PropertySearch.aspx" />';
    } else {
      echo "QUERY: $url";
      echo "$city not listed";
    }
?>
</body>
</html>
