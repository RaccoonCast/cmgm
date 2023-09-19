<?php
    $current_url = $domain_with_http . $_SERVER['REQUEST_URI'];
    !isset($_GET['limit']) ? header('Location: '.$current_url.'?limit=15') && exit : $limit = $_GET['limit'];

    function removeParameterFromURL($url, $parameterName) {
        $urlParts = parse_url($url);
        parse_str($urlParts['query'], $queryParams);
        unset($queryParams[$parameterName]);
        $newQueryString = http_build_query($queryParams);
        return $urlParts['path'] . ($newQueryString ? '?' . $newQueryString : '');
    }

    $allowGuests = "true";
    include "../functions.php";
    include "../database/includes/DB-filter-get.php";

    // Most created by X user.
    include "includes/count_records_created_by_everyone.php";
    include "includes/count_records_created_by_user.php";

    // Records during last X whatever.
    include "includes/count_records_by_timeframe.php";

    // Count most records created during X day.
    include "includes/count_records_by_day.php";

    // Count most records created during X month.
    include "includes/count_records_by_month.php";

    // Count most records created during X year.
    include "includes/count_records_by_year.php";

    // Count most common cities..
    include "includes/count_most_common_cities.php";

    $conn->close();
 ?>
