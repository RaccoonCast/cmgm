<?php
    !isset($_GET['limit']) ? header('Location: '.$current_url.'?limit=15') && die() : $limit = $_GET['limit'];

    function removeParameterFromURL($url, $parameterName) {
        $urlParts = parse_url($url);
        parse_str($urlParts['query'], $queryParams);
        unset($queryParams[$parameterName]);
        $newQueryString = http_build_query($queryParams);
        return $urlParts['path'] . ($newQueryString ? '?' . $newQueryString : '');
    }

    $allowGuests = "true";
    include "../functions.php";
    $current_url = $_SERVER['REQUEST_URI'];
    include "../database/includes/DB-filter-get.php";
    $db_vars_unamended = isset($db_vars_unamended) ? "1=1 " . $db_vars_unamended : "1=1 ";

    // Most created by X user.
    if (!isset($_GET['username'])) include "includes/count_records_created_by_everyone.php";
    if (!isset($_GET['username'])) include "includes/count_records_created_by_user.php";

    // Carrier counts.
    if (!isset($_GET['carrier'])) include "includes/count_most_common_carrier.php";

    // Records during last X whatever.
    if (!isset($_GET['date'])) include "includes/count_records_by_timeframe.php";

    // Count most records created during X day.
    if (!isset($_GET['date'])) include "includes/count_records_by_day.php";

    // Count most records created during X month.
    if (!isset($_GET['date'])) include "includes/count_records_by_month.php";

    // Count most records created during X year.
    if (!isset($_GET['date'])) include "includes/count_records_by_year.php";

    // Count most common cities..
    if (!isset($_GET['city'])) include "includes/count_most_common_cities.php";

    // Count concealed vs unconcealed sites.
    if (!isset($_GET['concealed'])) include "includes/count_concealed_vs_unconcealed.php";

    $map_url = $domain_with_http . "/database/Map.php?latitude=" . $default_latitude . "&longitude=" . $default_longitude . "&zoom=11" . preg_replace('/\/fun\/\?limit=\d+/', '', $current_url);
    echo '<a href='.$map_url.'>View filters on Map</a>';

    $conn->close();
 ?>
