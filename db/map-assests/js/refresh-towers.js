function refreshTowers()
{
  /*
  visibleCount = 0;
  for(var i in Towers)
  {
    var visible = false;
    if(Towers[i].get("system") == netType)
      visible = true;

    // Filter dates
    if(DateFilterType == "None")
    {
      visible = true;
    }
    else if(startDate && endDate)
    {
      if(DateFilterType == "FirstSeen")
      {
        if(Towers[i].get("firstseendate") >= startDate && Towers[i].get("firstseendate") <= endDate && visible)
          visible = true;
        else
          visible = false;
      }
      else
      {

        if(Towers[i].get("lastseendate") >= startDate && Towers[i].get("lastseendate") <= endDate && visible)
        {
          visible = true;
        }
        else
        {
          visible = false;
        }

      }
    }

    // Always put at end
    if(Towers[i].get("orphan") == 1)
    {
      if(showOrphans && visible)
      {
        visible = true;
      }
      else
      {
        if(!(MCC == 302 && MNC == 220 && Towers[i].get("system") == "LTE"))
        {
          visible = false;
        }

      }

    }

    if(LACList[Towers[i].get("LAC")] != undefined && LACList[Towers[i].get("LAC")]['show'] == false)
      visible = false;


    var hasBands = false;

    if(typeof Towers[i].get("bands") !== 'undefined' && Towers[i].get("bands").length == 0) // UNknown bands
    {
      if(BandList[0]['show'] == true) // Filter unknown
        hasBands = true;
    }
    //console.log("---");
    for(var j in Towers[i].get("bands"))
    {

      var Band = parseInt(Towers[i].get("bands")[j]);

      if(Band == 0 || Band == -1 || Band == undefined)
        hasBands = true;

      if(BandList[Band] != undefined && BandList[Band]['show'] == true)
        hasBands = true;


    }


    // Freq

    var hasAtLeastOneARFCN = false;

    if(ARFCNList[0] != undefined && ARFCNList[0]['show'] == true) // Filter unknown
      hasAtLeastOneARFCN = true;
    for(var j in Towers[i].get("arfcns"))
    {

      var arfcn = Towers[i].get("arfcns")[j];
      //console.log(Towers[i].base + " arfcn is =" +Towers[i].arfcns.length  +" and im checking for " + arfcn + " in array " + Towers[i].arfcns);
      // special 0 cases
      if(arfcn == 0 && ARFCNList[arfcn]['show'] == true )
          hasBands = true;
      else if(arfcn == 0 && ARFCNList[arfcn]['show'] == false && isNull(Towers[i].get("arfcns")))
          hasBands = false;

      if(ARFCNList[arfcn] != undefined && ARFCNList[arfcn]['show'] == true)
        hasAtLeastOneARFCN = true;
    }

    if(!hasAtLeastOneARFCN )
      visible = false;

    if(!hasBands)
      visible = false;

    if(!showTowers)
      visible = false;

    if(showFrequencyOnly == true && Towers[i].get("frequencyData") == false)
      visible = false;

    if(showBand != 0 && jQuery.inArray(showBand, Towers[i].get("bands")) == -1)
      visible = false;

    if(showNoFrequencyOnly == true && Towers[i].get("frequencyData") == true)
      visible = false;

    if(showBandwidthOnly == true && Towers[i].get("bandwidthData") == false)
      visible = false;

    if(showLTECAOnly == true && Towers[i].get("subSystem") != "LTE-A")
      visible = false;

    if(showENDCOnly == true && Towers[i].get("towerAttributes").TOWER_ENDC_AVAILABLE != true)
      visible = false;

    if(showVerifiedOnly == true && Towers[i].get("verified") == false)
      visible = false;

    if(showUnverifiedOnly == true && Towers[i].get("verified") == true)
      visible = false;
  //  REAL COMMENTS //	if(showMineOnly && userID != null)
  //  REAL COMMENTS // if(Towers[i].towerMover == userID)
  //  REAL COMMENTS //   visible = true;
  //  REAL COMMENTS // else
  //  REAL COMMENTS //   visible = false;
  //  REAL COMMENTS //
  //TODO: check if exists instead of trty catch
          try {
          if(visible)
          {
            if(!vectorSourceTowers.hasFeature(Towers[i]))
              vectorSourceTowers.addFeature(Towers[i]);
          }
          else
          {
            if(vectorSourceTowers.hasFeature(Towers[i]))
              vectorSourceTowers.removeFeature(Towers[i]);
          }
        }
        catch(error) {
          console.error(error);
        }

    if(visible)
    {
      var extent = ol.proj.transformExtent(Towers[i].getGeometry().getExtent(), 'EPSG:3857', 'EPSG:4326');
      if(Towers[i] != undefined && ol.extent.containsExtent(mapBounds, extent))
        visibleCount++;
    }
  }

  var towerCountMax = ($( window ).width() * $( window ).height()) / 10000;
  //console.log("max towers" + towerCountMax);

  if(visibleCount < towerCountMax)
  {
      clusterer.setVisible(false);
      towerLayer.setVisible(true);
  }
  else if(map.getView().getZoom() >= 13 && clusterEnabled)
  {

      refreshClustering(false);
  }
  else if(map.getView().getZoom() < 13  && clusterEnabled)
  {
    refreshClustering(true);

  }
  if(isMobileDevice && visibleCount > 0 && !$("#sidebar").is(':visible'))
  {
    $("#cellhint").hide();
  }
  */
  updateLinkback();
}
