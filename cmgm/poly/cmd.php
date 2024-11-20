<?php 
echo PHP_EOL;

// Error function
function error($msg) {
	echo $msg . PHP_EOL;
	die();
}

// Check for the correct number of arguments
if ($argc >= 2) {
	error("Usage: php {$argv[0]} <plmn> <eNB> [list,of,cells]");
}

$plmn = $argv[1];
$eNB = intval($argv[2]);
$numbersArg = $argv[3] ?? '';
$maps_api_key = file_get_contents("../secret_maps_api_key.hiddenpass");

include "master.php";

if (!empty($latLngPairs) echo PHP_EOL . "Located $eNB with Google Geolocation API:";
// Echo each found set of coordinates
foreach ($latLngPairs as $pair) {
	echo $pair;
}

// Echo URL
echo PHP_EOL;
echo "Drawn polygon of the above coordinates are available in EX_A.";
echo "EX_A: " . $link

// Echo each lat,lng,z25 for an easy copy and paste into CM.
if (count($latLngPairs) >= 1) {
	echo PHP_EOL;
	if (count($latLngPairs) == 1) echo PHP_EOL . "Copy and paste below into location lookup box for the location of Cell " . $numbersArg;
	if (count($latLngPairs) == 2) echo PHP_EOL . "Copy and paste below into location lookup box to draw a line " . $numbersArg;
	if (count($latLngPairs) == 3) echo PHP_EOL . "Copy and paste below into location lookup box to draw a triangle " . $numbersArg;
	if (count($latLngPairs) > 3)  echo PHP_EOL . "Copy and paste below into location lookup box to draw a polygon " . $numbersArg;
	
	foreach ($latLngPairs as $latLngPair) {
		echo $latLngPair . ",25" . PHP_EOL;
	}
}


?>