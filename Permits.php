<script src="js/permit-copy.js"></script>
  <?php
  // whittier: https://aca-prod.accela.com/WHITTIER/Cap/CapHome.aspx?module=Building&TabName=Home
  // west hollywood: https://permits.weho.org/etrakit3/Search/permit.aspx
  // garden grove: https://ch.ci.garden-grove.ca.us/permitsoft/permits/search
  // san jose: https://www.sanjoseca.gov/your-government/departments/planning-building-code-enforcement/building-division/online-permits
  // santa monica (active only): https://www.smgov.net/Departments/PCD/Permits/Active-Building-Permits/
  // chicago (ugly ui) https://webapps1.chicago.gov/buildingpermit/search/applicationsearch.do
  // anaheim: http://permits.anaheim.net/tm_bin/tmw_cmd.pl?tmw_cmd=StatusQueryFormbld
  // temple city: http://weblink.templecity.us/WebLink/0/fol/19107/Row1.aspx
  // wilmington: la city
  // monrovia: http://64.60.71.37/AppNet/Login.aspx?username=public&password=public (Firefox 31 - 32 bit only)
  // San Fernando: la city
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
