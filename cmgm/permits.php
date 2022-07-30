<!DOCTYPE HTML>
<head>
  <script src="/js/redir.js"></script>
  <script src="/js/copyToClipboard.js"></script>
  <?php
  include 'functions.php';

  if(isMobile()){
    css("mobile","Home");
  } else {
    css("desktop","Home");
  }
  ?>
</head>
<html>
<body class="flex">
<?php

function button($silent,$address,$button_text,$link) { ?>

  <div id="form" action="" method="post" autocomplete="off">
    <div class="buttons">
      <hr>
      <?php
      if ($silent == "false") echo $address . "<br>";
      if ($silent == "false") echo $button_text . "<br>";
      echo $link . "<br>" . "<br>";
      ?>
      <input type="submit" class="sb w-50 cmgm-btn" style="color:#D93A6C" id="Home" onclick="copyToClipboard('<?php echo $address;?>'); redir('<?php echo $link; ?>')" value="<?php echo $button_text; ?>">
    </div>
  </div> <?php
}

if (isset($_GET['city'])) $city = $_GET['city'];

if ("$city" == "Glendale") { button($address,"Glendale","https://csi.glendaleca.gov/csipropertyportal/");
} elseif ("$city" == "Buena Park") { button($address,"Buena Park","http://weblink.buenapark.com/public/Browse.aspx?startid=31798");
} elseif ("$city" == "Temple City") { button($address,"Temple City","https://templecity.quickbase.com/db/bph9nwjs2?a=q&qid=1");
} elseif ("$city" == "Boston") { button($address,"Boston","https://scerisecm.boston.gov/ScerIS/CmPublic/#/SearchCriteria?f=11");
} elseif ("$city" == "Glendora") { button($address,"Glendora","https://etrakit.ci.glendora.ca.us/etrakit3/Search/permit.aspx");
} elseif ("$city" == "Compton") { button($address,"Compton","https://www.citizenserve.com/Portal/PortalController?Action=showHomePage&ctzPagePrefix=Portal_&installationID=202&original_iid=0&original_contactID=0");
} elseif ("$city" == "Santa Ana") { button($address,"Santa Ana","https://www.santa-ana.org/pb/property-information-search");
} elseif ("$city" == "San Fernando") { button($address,"San Fernando","http://ladbsdoc.lacity.org/idispublic/");
} elseif ("$city" == "Chino Hills") { button($address,"Chino Hills","https://publicportal.chinohills.org/WebLink/Welcome.aspx");
} elseif ("$city" == "Wilmington") { button($address,"Wilmington","http://ladbsdoc.lacity.org/idispublic/");
} elseif ("$city" == "Chicago") { button($address,"Chicago","https://webapps1.chicago.gov/buildingpermit/search/applicationsearch.do");
} elseif ("$city" == "Anaheim") { button($address,"Anaheim","http://permits.anaheim.net/tm_bin/tmw_cmd.pl?tmw_cmd=StatusQueryFormbld");
} elseif ("$city" == "Long Beach") { button($address,"Long Beach","http://citydocs.longbeach.gov/WebLink8/CustomSearch.aspx?SearchName=SearchbyAddress");
} elseif ("$city" == "Burbank") { button($address,"Burbank","https://permit.burbankca.gov/epalspi/");
} elseif ("$city" == "Tucson") { button($address,"Tucson","https://www.tucsonaz.gov/PRO/pdsd/");
} elseif ("$city" == "San Francisco") { button($address,"San Francisco","https://dbiweb.sfgov.org/dbipts/default.aspx?page=AddressQuery");
} elseif ("$city" == "Pasadena") { button($address,"Pasadena","https://eservices.cityofpasadena.net/iwrplandev/PropertySearch.aspx");
} elseif ("$city" == "Paramount") { button($address,"Paramount","https://paramount.portal.iworq.net/paramount/permits/600");
} elseif ("$city" == "San Diego") { button($address,"San Diego","https://opendsd.sandiego.gov/web/approvals/");
} elseif ("$city" == "Phoenix") { button($address,"Phoenix","https://apps-secure.phoenix.gov/PDD/Search/Permits");
} elseif ("$city" == "San Gabriel") { button($address,"San Gabriel","http://blrenewals.sangabrielcity.com/weblink/browse.aspx?dbid=0");
} elseif ("$city" == "Garden Grove") { button($address,"Garden Grove","https://ch.ci.garden-grove.ca.us/permitsoft/permits/search");
} elseif ("$city" == "San Jose") { button($address,"San Jose","https://sjpermits.org/permits/general/combinedquery.asp");
} elseif ("$city" == "Beverly Hills") { button($address,"Beverly Hills","https://cs.beverlyhills.org/cs/");
} elseif ("$city" == "Santa Clarita") { button($address,"Santa Clarita","https://aca-prod.accela.com/SANTACLARITA/Default.aspx");
} elseif ("$city" == "Yorba Linda") { button($address,"Yorba Linda","https://aca-prod.accela.com/YORBALINDA/Cap/CapHome.aspx?module=Building&TabName=Building&TabList=Home%7C0%7CBuilding%7C1%7CCode%7C2%7CEngineering%7C3%7CPlanning%7C4%7CCurrentTabIndex%7C1");
} elseif ("$city" == "Culver City") { button($address,"Culver City","https://data.culvercity.org/Permits/Building-Safety-Permits/qew5-a3up/data");
} elseif ("$city" == "Sacramento") { button($address,"Sacramento","https://aca-prod.accela.com/SACRAMENTO/Cap/CapHome.aspx?module=Building&TabName=HOME");
} elseif ("$city" == "West Hollywood") { button($address,"West Hollywood","https://permits.weho.org/etrakit3/Search/permit.aspx");
} elseif ("$city" == "Oakland") { button($address,"Oakland","https://aca-prod.accela.com/oakland/customization/common/launchpad.aspx");
} elseif ("$city" == "Los Angeles") {
   button("false",$address,"Los Angeles","http://ladbsdoc.lacity.org/");
   button("true",$address,"Los Angeles - Old","https://navigatela.lacity.org/navigatela/");
   button("true",$address,"Los Angeles - New","https://www.ladbsservices2.lacity.org/OnlineServices/");
   button("true",$address,"LA Open Data","https://data.lacity.org/A-Prosperous-City/Building-Permits/nbyu-2ha9/data");
   button("true",$address,"County - New","https://epicla.lacounty.gov/SelfService/#/search");
   button("true",$address,"County - Old","http://ladbsdoc.lacity.org/");
} elseif ("$city" == "Thousand Oaks") { button($address,"Thousand Oaks","https://thou-egov.aspgov.com/Click2GovBP/index.html");
} elseif ("$city" == "Torrance") {
  echo "The majority of permits are filed under Los Angeles County.";
  button($address,"Los Angeles County","https://dpw.lacounty.gov/bsd/bpv/");
  button($address,"Torrance","https://aca.torranceca.gov/citizenaccess/default.aspx");
} elseif ("$city" == "Santa Monica") {
  echo "Active permits only.";
  button($address,"Santa Monica","https://www.smgov.net/Departments/PCD/Permits/Active-Building-Permits/");
} elseif ("$city" == "Gardena") {
  echo "The majority of permits are filed under Los Angeles County.";
  button($address,"Los Angeles County","https://dpw.lacounty.gov/bsd/bpv/");
  button($address,"Gardena","https://www.cityofgardena.org/building-permit-issued/");
} elseif ("$city" == "Monrovia") {
  echo "Firefox 31 - 32 Bit required.";
  button($address,"Monrovia","http://64.60.71.37/AppNet/Login.aspx?username=public&password=public");
} elseif ("$city" == "Whittier") {
  echo "The majority of permits are filed under Los Angeles County.";
  button($address,"Los Angeles County","https://dpw.lacounty.gov/bsd/bpv/");
  button($address,"Whittier","https://aca-prod.accela.com/WHITTIER/Cap/CapHome.aspx?module=Building&TabName=Home");
} else {
  echo "$city does not have a permit search page listed.";
}
?>
</body>
</html>
