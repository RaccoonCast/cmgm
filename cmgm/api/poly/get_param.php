<?php
function error($msg) {
    http_response_code(400);
    echo $msg;
    die();
}
function get_param($key, $pattern, $errorMsg, $default = null, $cast = null) {
    if (!isset($_GET[$key]) || $_GET[$key] == '') return $default;

    $value = $_GET[$key];

    if (!preg_match($pattern, $value)) {
        error($errorMsg);
    }

    return $cast ? $cast($value) : $value;
}

$plmn              = get_param('plmn', '/^\d+(,\d+)*$/', 'Invalid plmn');
$cellsAllowList    = get_param('cellsAllowList', '/^\d+(,\d+)*$/', 'Invalid cells_allow');
$cellsBlockList    = get_param('cellsBlockList', '/^\d+(,\d+)*$/', 'Invalid cells_block');
$tacsAllowList     = get_param('tacsAllowList', '/^\d+(,\d+)*$/', 'Invalid tacs_allow');
$tacsBlockList     = get_param('tacsBlockList', '/^\d+(,\d+)*$/', 'Invalid tacs_block');
$enbAllowList      = get_param('enbAllowList', '/^(\d+(-\d+)?)(,(\d+(-\d+)?))*$/', 'Invalid enb_allowList');
$enbBlockList      = get_param('enbBlockLIst', '/^(\d+(-\d+)?)(,(\d+(-\d+)?))*$/', 'Invalid enb_blockList');
$oldest_date       = get_param('oldest_date', '/^[<>]?\d{4}-\d{2}-\d{2}$/', 'Invalid oldest_date');
$newest_date       = get_param('newest_date', '/^[<>]?\d{4}-\d{2}-\d{2}$/', 'Invalid newest_date');
$rat               = get_param('rat', '/^(LTE|NR)$/i', 'Invalid rat', null, fn($v) => strtoupper($v));
$latitude          = get_param('latitude', '/^-?\d+(\.\d+)?$/', 'Invalid latitude');
$longitude         = get_param('longitude', '/^-?\d+(\.\d+)?$/', 'Invalid longitude');
$boundsNELat       = get_param('boundsNELatitude', '/^-?\d+(\.\d+)?$/', 'Invalid NE latitude');
$boundsNELon       = get_param('boundsNELongitude', '/^-?\d+(\.\d+)?$/', 'Invalid NE longitude');
$boundsSWLat       = get_param('boundsSWLatitude', '/^-?\d+(\.\d+)?$/', 'Invalid SW latitude');
$boundsSWLon       = get_param('boundsSWLongitude', '/^-?\d+(\.\d+)?$/', 'Invalid SW longitude');
$radius            = get_param('radius', '/^\d+(\.\d+)?$/', 'Invalid radius');
$limit             = get_param('limit', '/^\d+$/', 'Invalid limit', 450, fn($v) => (int)$v);
$locationType      = get_param('locationType', '/^\d+$/', 'Invalid locationType', null, fn($v) => (int)$v);
$useAggregateTable = (isset($_GET['useAggregateTable']) || basename($_SERVER['SCRIPT_FILENAME']) === 'getPolyEnbs.php' || basename($_SERVER['SCRIPT_FILENAME']) === 'gui.php');
$perfectSurroOnly  = (($_GET['perfectSurroOnly'] ?? null) === 'true') ? true : null;
$showsql           = isset($_GET['showsql']);
$iconSize          = $_GET['iconSize'] ?? 10;
$labels            = ($_GET['labels'] ?? 'true') === 'true' ? 'checked' : '';
$unload            = isset($_GET['dontUnload']) ? 'checked' : '';
$whereFilters      = null;
$locationFilter    = null;
$orderBy           = null;
$limitClause       = null;
?>