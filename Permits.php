<?php
  if (isset($_GET['permit_redirect'])) {
  if ("$city" == "Glendale") { redir("https://csi.glendaleca.gov/csipropertyportal/","0");
  } elseif ("$city" == "Temple City") { redir("https://templecity.quickbase.com/db/bph9nwjs2?a=q&qid=1","0");
  } elseif ("$city" == "Glendora") { redir("https://etrakit.ci.glendora.ca.us/etrakit3/Search/permit.aspx","0");
  } elseif ("$city" == "Compton") { redir("https://www.citizenserve.com/Portal/PortalController?Action=showHomePage&ctzPagePrefix=Portal_&installationID=202&original_iid=0&original_contactID=0","0");
  } elseif ("$city" == "Santa Ana") { redir("http://apps.santa-ana.org/property-info/","0");
  } elseif ("$city" == "Torrance") {
    echo "The majority of shit is under LA County.";
    echo '<a href="https://dpw.lacounty.gov/bsd/bpv/">Im not gonna stop you</a>';
    redir("https://aca.torranceca.gov/citizenaccess/default.aspx","4");
  } elseif ("$city" == "Gardena") {
    echo "The majority of shit is under LA County.";
    echo '<a href="https://dpw.lacounty.gov/bsd/bpv/">Im not gonna stop you</a>';
    redir("https://www.cityofgardena.org/building-permit-issued/","4");
  } elseif ("$city" == "Los Angeles") { redir("http://ladbsdoc.lacity.org/idispublic/","0");
  } elseif ("$city" == "San Fernando") { redir("http://ladbsdoc.lacity.org/idispublic/","0");
  } elseif ("$city" == "Wilmington") { redir("http://ladbsdoc.lacity.org/idispublic/","0");
  } elseif ("$city" == "Chicago") { redir("https://webapps1.chicago.gov/buildingpermit/search/applicationsearch.do","0");
  } elseif ("$city" == "Anaheim") { redir("http://permits.anaheim.net/tm_bin/tmw_cmd.pl?tmw_cmd=StatusQueryFormbld","0");
  } elseif ("$city" == "Long Beach") { redir("http://citydocs.longbeach.gov/WebLink8/CustomSearch.aspx?SearchName=SearchbyAddress","0");
  } elseif ("$city" == "Burbank") { redir("https://permit.burbankca.gov/epalspi/","0");
  } elseif ("$city" == "San Francisco") { redir("https://dbiweb.sfgov.org/dbipts/default.aspx?page=AddressQuery","0");
  } elseif ("$city" == "Pasadena") { redir("https://eservices.cityofpasadena.net/iwrplandev/PropertySearch.aspx","0");
  } elseif ("$city" == "Paramount") { redir("https://paramount.portal.iworq.net/paramount/permits/600","0");
  } elseif ("$city" == "San Diego") { redir("https://opendsd.sandiego.gov/web/approvals/","0");
  } elseif ("$city" == "San Gabriel") { redir("http://blrenewals.sangabrielcity.com/weblink/browse.aspx?dbid=0","0");
  } elseif ("$city" == "Whittier") {
    echo "The majority of shit is under LA County.";
    echo '<a href="https://dpw.lacounty.gov/bsd/bpv/">Im not gonna stop you</a>';
    redir("https://aca-prod.accela.com/WHITTIER/Cap/CapHome.aspx?module=Building&TabName=Home","4");
  } elseif ("$city" == "West Hollywood") { redir("https://permits.weho.org/etrakit3/Search/permit.aspx","0");
  } elseif ("$city" == "Garden Grove") { redir("https://ch.ci.garden-grove.ca.us/permitsoft/permits/search","0");
  } elseif ("$city" == "San Jose") { redir("https://sjpermits.org/permits/general/combinedquery.asp","0");
  } elseif ("$city" == "Beverly Hills") { redir("https://cs.beverlyhills.org/cs/","0");
  } elseif ("$city" == "Santa Clarita") { redir("https://aca-prod.accela.com/SANTACLARITA/Default.aspx","0");
  } elseif ("$city" == "Sacramento" ) { redir("https://aca-prod.accela.com/SACRAMENTO/Cap/CapHome.aspx?module=Building&TabName=HOME","0");
  } elseif ("$city" == "Santa Monica") {
    echo "Active permits only.";
    redir("https://www.smgov.net/Departments/PCD/Permits/Active-Building-Permits/","2");
  } elseif ("$city" == "Monrovia") {
    echo "Firefox 31 - 32 Bit required.";
    redir("http://64.60.71.37/AppNet/Login.aspx?username=public&password=public","2");
  } else {
    echo "QUERY: $url";
    echo "$city not listed";
  }
}
?>
</body>
</html>
