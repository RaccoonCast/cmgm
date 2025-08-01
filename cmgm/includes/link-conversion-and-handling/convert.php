<?php
/**
 * This function converts a location specified by a string in a number of different formats
 * into latitude and longitude coordinates. It then performs various actions depending on the
 * value of the "goto" argument, such as returning the coordinates or address information, or
 * including and executing additional code.
 *
 *  $data                 The location to be converted.
 *  $goto                 Specifies the actions to be performed after the conversion.
 *  $default_latitude     Use $default_latitude (cmgm settings) as a starting point for a location search if the user provides a location like "Burger King"
 *  $default_longitude    Use $default_longitude (cmgm settings) as a starting point for a location search if the user provides a location like "Burger King"
 *  $maps_api_key         A Google Maps API key.
 *  $userID               The user ID.
 *  $default_carrier      The default carrier to use.
 *  $cm_mapType           The map type to use on the CellMapper website.
 *  $cm_groupTowers       Specifies whether to group towers on the CellMapper website.
 *  $cm_showLabels        Specifies whether to show labels on the CellMapper website.
 *  $cm_showLowAcc        Specifies whether to show low accuracy data on the CellMapper website.
 *  $cm_zoom              The zoom level to use on the CellMapper website.
 *
 * return                 An array containing the results of the conversion.
 */
function convert($data,$goto,$default_latitude,$default_longitude,$maps_api_key,$userID,$default_carrier,$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc,$cm_zoom) {
include SITE_ROOT . "/includes/functions/getGetVars.php";
include SITE_ROOT . "/includes/functions/sqlpw.php";

// Attemnpt to recognize location as FAA Case
if (preg_match('/^(\d{2}|\d{4})-(\w+)-(\d+)-(OE|NRA)$/i', $data)) redir("../faa/?asn=" . $data, 0);

// Attempt coordinate string conversion (e.g., "Latitude: 42.3723˚N, Longitude: 71.9544˚W")
if(preg_match('/Latitude: [\d.]+˚[NS], Longitude: [\d.]+˚[EW]/', $data)) include "convert/lat-long-string-to-vars.php";
// Attempt to convert lat,long that has labels such as "Latitude: 34.23923 Longitude: -118.4843".
if ((strpos($data, 'Latitude') !== false || strpos($data, 'Longitude') !== false) OR
    (strpos($data, 'LAT ') !== false || strpos($data, 'LONG') !== false) && !isset($conv_type)) {
    include "convert/lat,long-mod.php";
}
// Attempt comma seperating lat,long to $lat,$long
if(strpos($data, ',') !== false && !isset($conv_type)) include "convert/lat,long.php";
// Attempt CellMapper URL Conversion (incl testmap)
if(substr("$data", 12, 14) == 'cellmapper.net' && !isset($conv_type)) include "convert/cellmapper.net.php";
// Attempt Google Maps URL Conversion
if(substr("$data", 0, 28) == 'https://www.google.com/maps/' && !isset($conv_type)) include "convert/google-maps-url-conversion.php";
// Attempt DMS TO DEC
if (!isset($conv_type)) include "convert/dmstodec.php";
// NOTHING? Google Maps search for the entered data
if (!isset($conv_type)) include "convert/google-maps-conversion.php";
// Get address info for location
if ($goto != "HomeWOAddr") include "convert/get-address-for-loc.php";

$latitude = substr($latitude,0,12);
$longitude = substr($longitude,0,12);

if (($goto == "HomeWOAddr") OR ($goto == "HomeWAddr")) return [$latitude,$longitude,@$carrier,@$address,@$zip,@$city,@$county,@$state,$goto,@$conv_type,@$url_1,@$url_2];

// When creating records w/ PCI+, it calls edit.php w/ just lat&long, address info is expected to be passed to edit.
if (($goto == "pciplus")) return [@$address,@$city,@$county,@$zip,@$state];

include_once "function_goto.php";
        $user_data = [
            'latitude'       => $latitude,
            'longitude'      => $longitude,
            'carrier'        => @$carrier,
            'address'        => @$address,
            'zip'            => @$zip,
            'city'           => @$city,
            'county'         => @$county,
            'state'          => @$state,
            'conv_type'       => NULL,
            'mapType'        => $cm_mapType,
            'cm_groupTowers'    => $cm_groupTowers,
            'cm_showLabels'     => $cm_showLabels,
            'cm_showLowAcc'     => $cm_showLowAcc,
            'cm_zoom'           => $cm_zoom,
            'cm_mapType'        => @$cm_mapType,
            'cm_netType'        => @$cm_netType,
        ];
return function_goto($user_data,$goto);
}
