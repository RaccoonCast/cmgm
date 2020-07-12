 <script src="js/permit-copy.js"></script>
  <?php

  // Check what city we in and redirect to their page
  if (isset($_GET['permit_redirect'])) {
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
