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
if (!isset($poly_map_default_viewmode)) $poly_map_default_viewmode = "enbs";
if (!isset($poly_map_default_unload)) $poly_map_default_unload = "false";
$cells_quantity = null;

$plmn                  = get_param('plmn', '/^!?\d+(,!?\d+)*$/', 'Invalid PLMN provided, expected input like "310260" or "311480,310410"');
$view_mode             = get_param('view_mode', '/^(cells|enbs|cm)$/', 'Invalid view mode.', $poly_map_default_viewmode);
$cells_allow_list      = get_param('cells_allow_list', '/^\d+(,\d+)*$/', 'Invalid cells whitelist provided, expected input like "11,12,13" or "4,5,6"');
$cells_block_list      = get_param('cells_block_list', '/^\d+(,\d+)*$/', 'Invalid cells blacklist provided, expected input like "11,12,13" or "4,5,6"');
$cells                 = get_param('cells', '/^\d+(,\d+)*$/', 'Invalid cells list provided, expected input like "11,12,13" or "4,5,6"');
$tacs_allow_list       = get_param('tacs_allow_list', '/^(\d+(-\d+)?)(,(\d+(-\d+)?))*$/', 'Invalid tacs whitelist provided, expected input like "1024,1028" or "15000-15300"');
$tacs_block_list       = get_param('tacs_block_list', '/^(\d+(-\d+)?)(,(\d+(-\d+)?))*$/', 'Invalid tacs blacklist provided, expected input like "1024,1028" or "15000-15300"');
$enb_allow_list        = get_param('enb_allow_list', '/^(\d+(-\d+)?)(,(\d+(-\d+)?))*$/', 'Invalid enb whitelist provided, expected input like "67684", "85105-185943" or "8000-11000,15000-18000"');
$enb_block_list        = get_param('enb_block_list', '/^(\d+(-\d+)?)(,(\d+(-\d+)?))*$/', 'Invalid enb blacklist provided, expected input like "67684", "85105-185943" or "8000-11000,15000-18000"');
$oldest_date           = get_param('oldest_date', '/^[<>]?\d{4}-\d{2}-\d{2}$/', 'Invalid first seen date provided, expected input like ">2025-01-01" or "2025-05-25"');
if ($view_mode != 'cells') $newest_date = get_param('newest_date', '/^[<>]?\d{4}-\d{2}-\d{2}$/', 'Invalid last seen date provided, expected input like ">2025-01-01" or "2025-05-25"');
$rat                   = get_param('rat', '/^(LTE|NR)$/i', 'Invalid rat, valid options are: LTE, NR, All.', null, fn($v) => strtoupper($v));
$latitude              = get_param('latitude', '/^-?\d+(\.\d+)?$/', 'Malformed latitude');
$longitude             = get_param('longitude', '/^-?\d+(\.\d+)?$/', 'Malformed longitude');
$boundsNELat           = get_param('boundsNELatitude', '/^-?\d+(\.\d+)?$/', 'Malformed NE latitude');
$boundsNELon           = get_param('boundsNELongitude', '/^-?\d+(\.\d+)?$/', 'Malformed NE longitude');
$boundsSWLat           = get_param('boundsSWLatitude', '/^-?\d+(\.\d+)?$/', 'Malformed SW latitude');
$boundsSWLon           = get_param('boundsSWLongitude', '/^-?\d+(\.\d+)?$/', 'Malformed SW longitude');
$radius                = get_param('radius', '/^\d+(\.\d+)?$/', 'Invalid radius, expected a number like 5 or 3.75');
$label_settings        = get_param('label_settings', '/^[0-6]$/', 'Invalid label setting, expected integer from 0-6.', 3);
$score                 = get_param('score', '/^(?:[<>]?\d+|\d+\s*-\s*\d+)$/', 'Invalid score filter, expected something like >300 or 1-30' );
$reach                 = get_param('reach', '/^(?:[<>]?\d+|\d+\s*-\s*\d+)$/', 'Invalid reach filter, expected something like >10000 or 25000-75000' );
if ($view_mode != 'cells') $cells_quantity = get_param('cells_quantity', '/^(?:[<>])?\d+$/', 'Invalid cell quantity setting, expected something like <10');
$limit                 = get_param('limit', '/^\d+$/', 'Invalid limit', 450, fn($v) => (int)$v);
$enb                   = get_param('enb', '/^\d+$/', 'Invalid eNB', 0, fn($v) => (int)$v);
$cm_includes           = get_param('cm_includes', '/^[A-Z,]+$/', 'Invalid CM Filters, typo?', NULL);
$cm_excludes           = get_param('cm_excludes', '/^[A-Z,]+$/', 'Invalid CM Filters, typo?', NULL);
$random_color          = (@$_GET['random_color'] == 'true') ? 'checked' : null;
$permanentlyDelete     = (($_GET['permanentlyDelete'] ?? null) === 'true') ? true : null;
$showsql               = isset($_GET['showsql']);
$icon_size             = $_GET['icon_size'] ?? 10;
// $labels               = ($_GET['labels'] ?? 'true') === 'true' ? 'checked' : '';
// $forceLabelVisibility = ($_GET['forceLabelVisibility'] ?? 'true') === 'true' ? 'checked' : '';
$unload                = (isset($_GET['dont_unload']) || $poly_map_default_unload == "true") ? 'checked' : '';
$hide_cells            = (isset($_GET['hide_cells'])) ? 'checked' : '';
$whereFilters          = null;
$whereFiltersLocation  = null;
$locationFilter        = null;
$orderBy               = null;
$limitClause           = null;
$centerLat             = null;
$centerLon             = null;
?>