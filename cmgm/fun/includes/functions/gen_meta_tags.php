<?php
function get_cellsite_normalized($cellsite_type) {
    include SITE_ROOT . '/includes/functions/tower_types.php';
    $category = ucfirst(explode('_', $cellsite_type)[0]);
    $cellsite_type = $options[$category][$cellsite_type];
    $cellsite_type_normalized = $cellsite_type . @$category_suffix;

    return $cellsite_type_normalized;
}

$filters_text = '';
$filters = [];
$unfiltered_filters = [];

$months = [
    1 => 'January',
    2 => 'February',
    3 => 'March',
    4 => 'April',
    5 => 'May',
    6 => 'June',
    7 => 'July',
    8 => 'August',
    9 => 'September',
    10 => 'October',
    11 => 'November',
    12 => 'December',
];

foreach ($_GET as $key => $value) {
    if (!in_array($key, ['limit', 'q', 'showsql']) && $value !== '') {
        switch ($key) {
            // Use month-formatting
            case 'month':
                array_push($filters, $months[(int) $value]);
                break;
            // Format as value only
            case 'year':
            case 'county':
            case 'state':
            case 'city':
            case 'carrier':
            case 'date':
            case 'username':
                array_push($filters, ucfirst($value));
                break;
            // Format Concealed / Unconcealed
            case 'concealed':
                if ($value == 'false') {
                    array_push($filters, 'Unconcealed');
                } else {
                    array_push($filters, 'Concealed');
                }
                break;
            // Format Unverified / Verified
            case 'status':
                if ($value == 'unverified') {
                    array_push($filters, 'Unverified');
                } else {
                    array_push($filters, 'Verified');
                }
                break;
            // Format cellsite type (use normalization already coded elsewhere)
            case 'cellsite_type':
                $cellsite_type_normalized = get_cellsite_normalized($value);
                array_push($filters, $cellsite_type_normalized);
                break;
            // Format hour-stamp
            case 'time':
                $formatted_hour = date('g A', strtotime("$hour:00:00"));
                array_push($filters, $formatted_hour);
                break;
            default:
                $val = htmlspecialchars($value);
                array_push($unfiltered_filters, "{$key}={$val}");
                break;
        }
    }
}

if (!empty($filters)) {
    sort($filters);
    if (!empty($unfiltered_filters)) {
        sort($unfiltered_filters);
        foreach ($unfiltered_filters as $val) {
            array_push($filters, $val);
        }
    }

    $filters_text = implode(', ', $filters);
}
if (!empty($unfiltered_filters)) {
}

$pin_or_pins = (int) $total == 1 ? 'Pin' : 'Pins';
?>

<title><?= number_format($total) ?> <?= $pin_or_pins ?> - CMGM </title>
<meta property="og:site_name" content="cmgm.us/fun" >
<meta property="og:title" content="CMGM - <?= number_format($total) ?> <?= $pin_or_pins ?> ">
<meta property="og:description" content="Filtered By (<?= $filters_text ?>)">
<meta property="og:type" content="website">
<meta property="og:url" content="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']; ?>">
<!-- <meta property="og:image" content="https://cmgm.us/favicon.ico"> -->