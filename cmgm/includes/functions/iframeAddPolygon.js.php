<script>
    // Get carrier
    const carrier = "<?php echo $carrier ?>";
    const sector_configuration = "<?php echo $sector_configuration ?>";
    const split_sector = "<?php echo $split_sector ?>";
    
    // Base carrier/cell list for future addons
    let carrierCellList = [0, []];

    // Determine cell layout for each carrier
    if (carrier == 'ATT') {
        carrierCellList = [310410, [8,9,10, 15, 16, 17]];
        if (sector_configuration.startsWith("4")) {
          carrierCellList = [310410, [8, 9, 10, 11, 15, 16, 17, 18]];
        }
        if (sector_configuration.startsWith("2")) {
          carrierCellList = [310410, [8, 9, 15, 16]];
        }
        if (split_sector === "true") {
          carrierCellList = [310410, [8, 9, 10, 11, 12, 13, 15, 16, 17, 18, 19, 20]];
        }
    } else if (carrier == 'T-Mobile') {
        carrierCellList = [310260, [1,2,3]];
        if (sector_configuration.startsWith("4")) {
          carrierCellList = [310260, [1,2,3,4]];
        }
        if (sector_configuration.startsWith("2")) {
          carrierCellList = [310260, [1,2]];
        }
        if (sector_configuration.includes("+")) {
          carrierCellList[1].push(10, 12);
        }
    } else if (carrier == 'Verizon') {
        carrierCellList = [311480, [1,2,3]];
        if (sector_configuration.startsWith("4")) {
          carrierCellList = [311480, [1,2,3,4]];
        }
        if (sector_configuration.startsWith("2")) {
          carrierCellList = [311480, [1,2]];
        }
    } else {
        throw 'Carrier not supported!';
    }

    const eNB = "<?php echo $eNB; ?>";
    const TAC = "<?php echo $region_lte; ?>";

    if (eNB == "0") {
      throw 'eNB not set';
    }
	
    // // Generate data for request
    let postData = new FormData();
    postData.append('eNB', eNB);
    postData.append('tac', TAC);
    postData.append('plmn', carrierCellList[0]);
    postData.append('rat', 'LTE');
    postData.append('cellList', carrierCellList[1].join(','));

    // Get user ID so we can pass it in the request
    const userId = "<?php echo $userID; ?>";

    /**
     * Check 'Extras' for existing poly link
     */
    function getExistingPoly() {
      let extras = [...document.getElementsByClassName('extra_cw')]

      for (const el of extras) {
        if (
          el.value.startsWith("https://cmgm.us/poly/") ||
          el.value.startsWith("http://cmgm.us/poly/")
        ) {
          return el.value;
        }
      }
    }

    /**
     * Get map URL for site
     */
    async function getUrl() {
        const req = await fetch('/poly/web.php', {
          method: 'POST',
          body: postData,
          rejectUnauthorized: false 
        });

        const res = await req.json();

        let iframeUrl = res.URL;

        if (res?.URL == undefined) {
          return `/database/Map.php?latitude=<?php echo $lat?>&longitude=<?php echo $long?>&marker_latitude=<?php echo $lat?>&marker_longitude=<?php echo $long?>&zoom=<?php echo $zoom?>&limit=<?php echo $limit?>&pin_style=carrier&hideui=true&showPolyLink`
        }

        // Get site lat/lng/zoom
        // const siteLatitude = <?php echo $lat; ?>;
        // const siteLongitude = <?php echo $long; ?>;
        // const siteZoom = <?php echo $zoom; ?>;

        // replace given lat/lng with provided site loc
        // iframeUrl =iframeUrl.replace(/latitude=[\d\.\-]+/, `latitude=${siteLatitude}`);
        // iframeUrl =iframeUrl.replace(/longitude=[\d\.\-]+/, `longitude=${siteLongitude}`);
        // iframeUrl =iframeUrl.replace(/zoom=[\d\.\-]+/, `zoom=${siteZoom}`);

        // Add marker lat/lng
        iframeUrl = iframeUrl + '&marker_latitude=<?php echo $lat?>&marker_longitude=<?php echo $long?>&limit=<?php echo $limit?>&pin_style=carrier&showPolyLink';

        // Hide UI
        iframeUrl = iframeUrl + '&hideui=true'

        // Force browsers to load most recent poly in iframe
        iframeUrl = iframeUrl + `&cachebuster=${Date.now()}`

        // Skip polygon zoom (stay on default CMGM zoom)
        // iframeUrl = iframeUrl + '&skipPolyZoom';

        return iframeUrl;
    }


    // Change URL for existing iframe
    function changeUrl(iframe) {   

      // If just changed, and we need an onclick, set onclick
      if (window.polyAdded && window.polyAdded !== 'existing') {
        iframe.contentDocument.querySelector('#openPolyButton').onclick = () => {

        // Generate our own poly URL  
        const polyUrl = `/poly/?plmn_1=${carrierCellList[0]}&rat_1=LTE&eNB_1=${eNB}&tac_1=${TAC}&cellList_1=${carrierCellList[1]}&cachebuster=${Date.now()}`

        // Set it to open in new tab
        window.open(polyUrl, '_blank');
        }
      }

      // If added already at all, return
      if (window.polyAdded) {
        return;
      }

      // Get existing poly link from extras
      const existingPoly = getExistingPoly();

      // Use existing poly (from extras), if available
      if (existingPoly) {
        // Append 'hidePolyForm' (to hide inputs) and marker lat/lng
        // With these arguments passed, it will also place a Leaflet control to open itself
        iframe.src = existingPoly + `&hidePolyForm&marker_latitude=<?php echo $lat?>&marker_longitude=<?php echo $long?>&cachebuster=${Date.now()}`;
        window.polyAdded = 'existing';
        return;
      }

      // Otherwise, we need to get a new poly
      (async () => {
        const url = await getUrl();
        iframe.src = url;
        window.polyAdded = url;        
      })();


    }
    

  </script>