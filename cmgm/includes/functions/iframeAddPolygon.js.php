<script>
    // Get carrier
    const carrier = "<?php echo $carrier ?>";

    // Base carrier/cell list for future addons
    let carrierCellList = [0, []];

    // Determine cell layout for each carrier
    if (carrier == 'ATT') {
        carrierCellList = [310410, [8,9,10, 15, 16, 17]];
    } else if (carrier == 'T-Mobile') {
        carrierCellList = [310260, [1,2,3]];
    } else if (carrier == 'Verizon') {
        carrierCellList = [311480, [1,2,3]];
    } else {
        throw 'Carrier not supported!';
    }

    // Generate data for request
    let postData = new FormData();
    postData.append('eNB', "<?php echo $eNB; ?>");
    postData.append('plmn', carrierCellList[0]);
    postData.append('rat', 'LTE');
    postData.append('cellList', carrierCellList[1].join(','));

    // Get user ID so we can pass it in the request
    const userId = "<?php echo $userID; ?>";

    /**
     * Get map URL for site
     */
    async function getUrl() {
        const req = await fetch('https://cmgm.us/poly/web.php', {
          method: 'POST',
          body: postData,
          rejectUnauthorized: false 
        });

        const res = await req.json();

        let iframeUrl = res.URL;

        // Get site lat/lng/zoom
        const siteLatitude = <?php echo $lat; ?>;
        const siteLongitude = <?php echo $long; ?>;
        const siteZoom = <?php echo $zoom; ?>;

        // replace given lat/lng with provided site loc
        iframeUrl =iframeUrl.replace(/latitude=[\d\.\-]+/, `latitude=${siteLatitude}`);
        iframeUrl =iframeUrl.replace(/longitude=[\d\.\-]+/, `longitude=${siteLongitude}`);
        iframeUrl =iframeUrl.replace(/zoom=[\d\.\-]+/, `zoom=${siteZoom}`);

        // Add marker lat/lng
        iframeUrl = iframeUrl + '&marker_latitude=<?php echo $lat?>&marker_longitude=<?php echo $long?>&limit=<?php echo $limit?>&pin_style=carrier';

        // Hide UI
        iframeUrl = iframeUrl + '&hideui=true'


        // Skip polygon zoom (stay on default CMGM zoom)
        // iframeUrl = iframeUrl + '&skipPolyZoom';

        return iframeUrl;
    }


    // Change URL for existing iframe
    function changeUrl(iframe) {   
      if (window.polyAdded) return;

      (async () => {
        const url = await getUrl();
        iframe.src = url;
        window.polyAdded = true;
      })();
    }
    

  </script>