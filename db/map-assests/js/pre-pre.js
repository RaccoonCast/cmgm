var params = [];
var API_URL = "https://api.cellmapper.net/v6/";

function getQueryParams() {
	var qs = document.location.search;
	qs = qs.split('+').join(' ');

	var params = {},
		tokens,
		re = /[?&]?([^=]+)=([^&]*)/g;

	while (tokens = re.exec(qs)) {
		params[decodeURIComponent(tokens[1])] = decodeURIComponent(tokens[2]);
	}

	return params;
}
