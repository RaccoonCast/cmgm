function hasKey(obj, key) {
  return obj.hasOwnProperty(key);
}

// Parse all request parameters
params = getQueryParams();
var MCC = null;
if(params.MCC != undefined)
  MCC = parseInt(params.MCC);
var MNC = null;
if(params.MNC != undefined)
  MNC = parseInt(params.MNC);

var latitude = 0.0;
if(params.latitude != undefined)
  latitude = parseFloat(params.latitude);
var longitude = 0.0;
if(params.longitude != undefined)
  longitude = parseFloat(params.longitude);

  var zoom = 9;
	if(getParamOrCookie("zoom") != undefined)
		zoom = parseInt(getParamOrCookie("zoom"));
