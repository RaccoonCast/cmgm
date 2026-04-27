<?php
function error($key, $msg) {
    if (basename($_SERVER['SCRIPT_FILENAME']) === 'enbMap.php') {
        remove ($key);
    }
    http_response_code(400);
    echo json_encode(array(
        "error" => $msg,
    ));
    die();
}
function remove($param) {
    $query = $_GET;
    unset($query[$param]);
    $queryString = http_build_query($query);
    $url = strtok($_SERVER["REQUEST_URI"], '?');
    if (!empty($queryString)) {
        $url .= '?' . $queryString;
    }

    // Redirect
    header("Location: $url");
    exit;
}
function get_param($key, $pattern, $errorMsg, $default = null, $cast = null) {
    if (!isset($_GET[$key]) || $_GET[$key] == '') return $default;

    $value = $_GET[$key];

    if (!preg_match($pattern, $value)) {
        error($key, $errorMsg);
    }

    return $cast ? $cast($value) : $value;
}

$plmn                 = get_param('plmn', '/^!?\d+(,!?\d+)*$/', 'Invalid PLMN provided, expected input like "310260" or "311480,310410"');
$viewMode             = get_param('viewMode', '/^(cells|enbs)$/', 'Invalid view mode, expected "cells" or "enbs"', 'enbs');
$cellsAllowList       = get_param('cellsAllowList', '/^\d+(,\d+)*$/', 'Invalid cells whitelist provided, expected input like "11,12,13" or "4,5,6"');
$cellsBlockList       = get_param('cellsBlockList', '/^\d+(,\d+)*$/', 'Invalid cells blacklist provided, expected input like "11,12,13" or "4,5,6"');
$tacsAllowList        = get_param('tacsAllowList', '/^\d+(,\d+)*$/', 'Invalid tacs whitelist provided, expected input like "1024,1028,1152" or "15280,15480,16001"');
$tacsBlockList        = get_param('tacsBlockList', '/^\d+(,\d+)*$/', 'Invalid tacs blacklist provided, expected input like "1024,1028,1152" or "15280,15480,16001"');
$enbAllowList         = get_param('enbAllowList', '/^(\d+(-\d+)?)(,(\d+(-\d+)?))*$/', 'Invalid enb whitelist provided, expected input like "67684", "85105-185943" or "8000-11000,15000-18000"');
$enbBlockList         = get_param('enbBlockList', '/^(\d+(-\d+)?)(,(\d+(-\d+)?))*$/', 'Invalid enb blacklist provided, expected input like "67684", "85105-185943" or "8000-11000,15000-18000"');
$oldest_date          = get_param('oldest_date', '/^[<>]?\d{4}-\d{2}-\d{2}$/', 'Invalid first seen date provided, expected input like ">2025-01-01" or "2025-05-25"');
if ($viewMode != 'cells') $newest_date = get_param('newest_date', '/^[<>]?\d{4}-\d{2}-\d{2}$/', 'Invalid last seen date provided, expected input like ">2025-01-01" or "2025-05-25"');
$rat                  = get_param('rat', '/^(LTE|NR)$/i', 'Invalid rat, valid options are: LTE, NR, All.', null, fn($v) => strtoupper($v));
$latitude             = get_param('latitude', '/^-?\d+(\.\d+)?$/', 'Malformed latitude');
$longitude            = get_param('longitude', '/^-?\d+(\.\d+)?$/', 'Malformed longitude');
$boundsNELat          = get_param('boundsNELatitude', '/^-?\d+(\.\d+)?$/', 'Malformed NE latitude');
$boundsNELon          = get_param('boundsNELongitude', '/^-?\d+(\.\d+)?$/', 'Malformed NE longitude');
$boundsSWLat          = get_param('boundsSWLatitude', '/^-?\d+(\.\d+)?$/', 'Malformed SW latitude');
$boundsSWLon          = get_param('boundsSWLongitude', '/^-?\d+(\.\d+)?$/', 'Malformed SW longitude');
$radius               = get_param('radius', '/^\d+(\.\d+)?$/', 'Invalid radius, expected a number like 5 or 3.75');
$labelSettings        = get_param('labelSettings', '/^[1-5]$/', 'Invalid label setting, expected integer from 1-5.', 2);
$limit                = get_param('limit', '/^\d+$/', 'Invalid limit', 450, fn($v) => (int)$v);
$locationType         = get_param('locationType', '/^\d+$/', 'Invalid locationType, expected 1 or 2.', false, fn($v) => (int)$v);
$perfectSurroOnly     = (($_GET['perfectSurroOnly'] ?? null) === 'true') ? true : null;
$showsql              = isset($_GET['showsql']);
$iconSize             = $_GET['iconSize'] ?? 10;
// $labels               = ($_GET['labels'] ?? 'true') === 'true' ? 'checked' : '';
// $forceLabelVisibility = ($_GET['forceLabelVisibility'] ?? 'true') === 'true' ? 'checked' : '';
$unload               = isset($_GET['dontUnload']) ? 'checked' : '';
$whereFilters         = null;
$whereFiltersLocation = null;
$locationFilter       = null;
$orderBy              = null;
$limitClause          = null;
$centerLat            = null;
$centerLon            = null;
?>