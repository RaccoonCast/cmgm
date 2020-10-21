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
  if ("$city" == "Glendale") { redir("https://csi.glendaleca.gov/csipropertyportal/","0");
  } elseif ("$city" == "Los Angeles") { redir("http://ladbsdoc.lacity.org/idispublic/","0");
  } elseif ("$city" == "Long Beach") { redir("http://citydocs.longbeach.gov/WebLink8/CustomSearch.aspx?SearchName=SearchbyAddress","0");
  } elseif ("$city" == "Burbank") { redir("https://permit.burbankca.gov/epalspi/","0");
  } elseif ("$city" == "San Francisco") { redir("https://dbiweb.sfgov.org/dbipts/default.aspx?page=AddressQuery","0");
  } elseif ("$city" == "Pasadena") { redir("https://eservices.cityofpasadena.net/iwrplandev/PropertySearch.aspx","0");
  } else {
    echo "QUERY: $url";
    echo "$city not listed";
  }
}
?>
</body>
</html>
