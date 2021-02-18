function getTowersInView(inMCC, inMNC, clear, inNetType)
	{
//console.log("getTowersInView(" + inMCC +", " + inMNC +", " + clear + ", " + inNetType + ")");

		if(inMCC == null || inMNC == null)
			return;

		$("#doHex").prop('checked', showHex);

		// Set new centre if default
		if(	map.getView().getCenter()[1] == 0)
			getCentre(MCC);

		if(!mapBounds)
			return;
		// Make map not freeze when scrolling large providers
		if(towersXHR != null && towersXHR.state() === 'pending')
			towersXHR.abort();


		towersXHR = $.ajax({
			dataType: "json",
			global: false,
			url: API_URL + "getTowers",
			data: {
			MCC: inMCC,
			MNC: inMNC,
			RAT: inNetType,
			boundsNELatitude: ol.extent.getTopRight(mapBounds)[1],
			boundsNELongitude:  correctLongitude(ol.extent.getTopRight(mapBounds)[0]),

			boundsSWLatitude:  ol.extent.getBottomLeft(mapBounds)[1],
			boundsSWLongitude:   correctLongitude(ol.extent.getBottomLeft(mapBounds)[0]),
			filterFrequency : showFrequencyOnly,
			showOnlyMine : showMineOnly,
			showUnverifiedOnly : showUnverifiedOnly,
			showENDCOnly : showENDCOnly

		},
			beforeSend : function(response)
			{
			/*	$("#cellhint").html("<b>Loading Towers...</b>");
				$("#cellhint").show();*/
			},
			success : function(response)
				{
				setTimeout(function(){
				if(clear)
				{
					removeAllTowers();
				}
				else
				{
					removeInvisibleTowers();
				}
				var towerData = handleResponse(response);
				$.each(towerData, function(i,item){

					var label = "Site ";
					if(item.RAT == "LTE")
						label = "eNB ID ";

					var labelText = label;
					labelText+= item.siteID;

          var marker = new ol.Feature({
            geometry: new ol.geom.Point(ol.proj.transform([parseFloat(item.longitude), parseFloat(item.latitude)], 'EPSG:4326','EPSG:3857')),
            name:  item.siteID,
            draggable: (isLoggedIn ? true : false)
          });

          if(!showTowerLabels)
            labelText = null;

					if(item.regionID == undefined)
						item.regionID = 0;

            var iconStyleVerified = new ol.style.Style({
              text: new ol.style.Text({
                  text: labelText,
                  font: 'bold 11px Helvetica, verdana',
                  textAlign: 'center',
                  textBaseline: 'middle',
                  offsetX: 0,
                  offsetY: 25,
                  backgroundStroke: new ol.style.Stroke({
                    color: 'black',
                    width: 1
                  }),
                  backgroundFill: new ol.style.Fill({
                      color: 'white',
                  }),
                  fill: new ol.style.Fill({
                      color: 'black',
                  }),
				  padding: [3,3,3,3]
                }),
              image: new ol.style.Icon({
                color: '#4abe00',
                src: '../images/red.png'
              })
            });

						marker.setStyle(iconStyleVerified);
						marker.set("verified" ,true);
					}

					marker.set("orphan",!item.visible);

					// Check if we have a marker there, if not, add it
					if(!markerExists(marker))
					{
						Towers.push(marker);
            vectorSourceTowers.addFeature(marker);
					}
          else {
            return;
          }

				refreshTowers();
				 },50);

				} // end success
		});	// end req


	 }
