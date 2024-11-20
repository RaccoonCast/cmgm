<?php 
// Error function
function error($msg) {
	echo $msg . PHP_EOL;
	die();
}

// Check for the correct number of arguments
if ($argc < 2) {
	error("Usage: php {$argv[0]} <plmn> <eNB> [list,of,cells]");
}

// Get required variables from command-line arguments.
$plmn = $argv[1];
$eNB = intval($argv[2]);
$numbersArg = $argv[3] ?? '';

// Call the master.
include "master.php";

// Check whether anything was found, if so continue.
if (empty($latLngPairs)) { 
		error("No cells could be found for $eNB"); }
	else {
		echo "Located $eNB with Google Geolocation API:" . PHP_EOL;
}

// Echo each found set of coordinates
foreach ($detailedLatLngPairs as $pair) {
	echo "Cell " . $pair['cellNumber'] . " found within " . round($pair['accuracyMiles'], 2)  . " miles of " . $pair['latLng'] . PHP_EOL;
}

// Echo each lat,lng,z25 for an easy copy and paste into CM.
if (count($latLngPairs) >= 1) {
	
	if (count($latLngPairs) >= 3) {
		echo PHP_EOL . "Drawn polygon of the above coordinates are available in EX_A.";
		echo PHP_EOL . "EX_A: " . $link . PHP_EOL;
	}

	if (count($latLngPairs) == 1) echo PHP_EOL . "Copy and paste below into location lookup box for the location of Cell " . $numbersArg . PHP_EOL;
	if (count($latLngPairs) == 2) echo PHP_EOL . "Copy and paste below into location lookup box to draw a line " . $numbersArg . PHP_EOL;
	if (count($latLngPairs) == 3) echo PHP_EOL . "Copy and paste below into location lookup box to draw a triangle " . $numbersArg . PHP_EOL;
	if (count($latLngPairs) > 3)  echo PHP_EOL . "Copy and paste below into location lookup box to draw a polygon " . $numbersArg . PHP_EOL;
	
	foreach ($latLngPairs as $latLngPair) {
		echo $latLngPair . ",25" . PHP_EOL;
	}
}


?>