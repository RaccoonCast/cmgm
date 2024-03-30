<script>
// Get active vars if available
// Otherwise, fall back to db
function getCurrentParams() {
  const params = new URLSearchParams(window.location.search);
  let lat = params.get('latitude') ?? "<?php echo $latitude ?>";
  let lon = params.get('longitude') ?? "<?php echo $longitude ?>";
  let zoom = params.get('zoom') ?? "<?php echo $cm_zoom ?>";
  let carrier = params.get('carrier') ?? "<?php echo $cm_carrier ?>";


  return {
    lat: lat,
    lon: lon,
    zoom: zoom,
    carrier: carrier
  }
}

/**
 * Generate CellMapper link
 */
function getCmLink() {
  // Initialize dynamic vars
  const { lat, lon, zoom, carrier } = getCurrentParams();

  // Initialize RAT
  let cm_rat = "LTE";
  if (carrier == 'Dish') {
    cm_rat = 'NR';
  }

  // Generate mcc/mnc pair based on carrier
  let mcc, mnc;
  switch (carrier) {
    case 'T-Mobile':
      mcc = 310;
      mnc = 260;
      break;
    case 'Sprint':
      mcc = 310;
      mnc = 120;
      break;
    case 'ATT':
      mcc = 310;
      mnc = 410;
      break;
    case 'Verizon':
      mcc = 311;
      mnc = 480;
      break;
    case 'Dish':
      mcc = 313;
      mnc = 340;
      break;
    default:
      // Use last known carrier
      mcc = -1;
      mnc = -1;
  }

  // Get user settings
  const cm_groupTowers = "<?php echo $cm_groupTowers ?>";
  const cm_showLabels = "<?php echo $cm_showLabels ?>";
  const cm_showLowAcc = "<?php echo $cm_showLowAcc ?>";

  // get ppT/ppL if available
  let ppString = '';;
  if ("<?php echo @$LTE_1 ?>") {
    // LTE
    let id = "<?php echo @$LTE_1 ?>";
    ppString += `&ppT=${id}`

    let tac = "<?php echo @$region_lte ?>";
    ppString += `&ppL=${tac || '0'}`
  } else if ("<?php echo @$NR_1 ?>") {
    // NR
    let id = "<?php echo @$NR_1 ?>";
    ppString += `&ppT=${id}`

    let tac = "<?php echo @$region_nr ?>";
    ppString += `&ppL=${tac || '0'}`

    // Set rat to nr too
    cm_rat = 'NR';
  }

  // Generate URL
  const url = `https://www.cellmapper.net/map?${mcc != -1 ? `MCC=${mcc}&MNC=${mnc}&` : ''}type=${cm_rat}&latitude=${lat}&longitude=${lon}&zoom=${zoom}&clusterEnabled=${cm_groupTowers}&showTowerLabels=${cm_showLabels}&showOrphans=${cm_showLowAcc}` + ppString;

  // Return URL
  return url;
}
</script>