<?php
if (isset($_GET['permit_redirect'])) {
// Code I stole to get JSON data
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

 // Retrieve important information from JSON and set the data to a corresponding variable
 $addressComponents = $response->results[0]->address_components;
 foreach ($addressComponents as $addrComp) {
     if ($addrComp->types[0] == 'postal_code') $zip = $addrComp->long_name;
     if ($addrComp->types[0] == 'street_number') $number = $addrComp->short_name;
     if ($addrComp->types[0] == 'route') $short_street_name = $addrComp->short_name;
     if ($addrComp->types[0] == 'route') $long_street_name = $addrComp->long_name;
     if ($addrComp->types[0] == 'locality') $city = $addrComp->short_name;
     if ($addrComp->types[0] == 'administrative_area_level_1') $state = $addrComp->short_name;
     }

    // Remove blank spaces
    $directionCheck_a = explode(' ',trim($short_street_name));
    $directionCheck_b = explode(' ',trim($long_street_name));

    // Remove - North / East / South / West & N / E / S / W
    if ("$directionCheck_a[0]" == "N" && "$directionCheck_b[0]" == "North") {$long_street_name = substr($long_street_name,5); }
    if ("$directionCheck_a[0]" == "E" && "$directionCheck_b[0]" == "East")  {$long_street_name = substr($long_street_name,4); }
    if ("$directionCheck_a[0]" == "S" && "$directionCheck_b[0]" == "South") {$long_street_name = substr($long_street_name,5); }
    if ("$directionCheck_a[0]" == "W" && "$directionCheck_b[0]" == "West")  {$long_street_name = substr($long_street_name,4); }

    $street_name = $long_street_name;

    // Set short address variable
    $text = "$number $street_name";
  ?>

   <script>
   // Copy short address to clipboard
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
  // Check what city we in and redirect to their page
    if ("$city" == "Glendale") {
      echo '<meta http-equiv="Refresh" content="0; url=https://csi.glendaleca.gov/csipropertyportal/" />';
    } elseif ("$city" == "Los Angeles") {
      echo '<meta http-equiv="Refresh" content="0; url=http://ladbsdoc.lacity.org/idispublic/" />';
    } elseif ("$city" == "Long Beach") {
      echo '<meta http-equiv="Refresh" content="0; url=http://citydocs.longbeach.gov/WebLink8/CustomSearch.aspx?SearchName=SearchbyAddress" />';
    } elseif ("$city" == "Burbank") {
      echo '<meta http-equiv="Refresh" content="0; url=https://permit.burbankca.gov/epalspi/" />';
    } elseif ("$city" == "San Francisco") {
      echo '<meta http-equiv="Refresh" content="0; url=https://dbiweb.sfgov.org/dbipts/default.aspx?page=AddressQuery" />';
    } elseif ("$city" == "Pasadena") {
      echo '<meta http-equiv="Refresh" content="0; url=https://eservices.cityofpasadena.net/iwrplandev/PropertySearch.aspx" />';
    } else {
      echo "QUERY: $url";
      echo "$city not listed";
    }
    }
?>
</body>
</html>
