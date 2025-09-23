<?php
    echo '<div class="statistics-wrapper">';

    // Count most common cities.
    if (!isset($_GET['city'])) {
        statBox("count_most_common_cities.php", $db_vars, $conn, $domain_with_http);
    }
    
        // Count most common counties.
    if (!isset($_GET['county'])) {
       statBox("count_most_common_counties.php", $db_vars, $conn, $domain_with_http);
    }

        // Count most common states.
    if (!isset($_GET['state'])) {
       statBox("count_most_common_states.php", $db_vars, $conn, $domain_with_http);
    }
    
    // Count most records created during X day.
    if (!isset($_GET['date']) || (isset($_GET['date'][0]) && ($_GET['date'][0] === '<' || $_GET['date'][0] === '>'))) {
        statBox("count_records_by_day.php", $db_vars, $conn, $domain_with_http);
    }

    // Count most records created during X month.
    if (!isset($_GET['date']) && !isset($_GET['month'])) {
        statBox("count_records_by_month.php", $db_vars, $conn, $domain_with_http);    
    }

    // Most created by X user.
    if (!isset($_GET['username'])) {
        statBox("count_records_created_by_user.php", $db_vars, $conn, $domain_with_http, $domain_with_http);
    }

    // Carrier counts.
    if (!isset($_GET['carrier'])) {
        statBox("count_most_common_carrier.php", $db_vars, $conn, $domain_with_http);
    }

    // Count concealed vs unconcealed sites.
    @$_GET['status'] && @$_GET['concealed'] ?: statBox("count_concealed_vs_unconcealed_and_status.php", $db_vars, $conn, $domain_with_http);

    // Records during last X whatever.
    if (!isset($_GET['date']) && !isset($_GET['year']) && !isset($_GET['month'])) {
        statBox("count_records_by_timeframe.php", $db_vars, $conn, $domain_with_http);
    }

    // Count most records created during X year.
    if (!isset($_GET['date']) && !isset($_GET['year'])) {
        statBox("count_records_by_year.php", $db_vars, $conn, $domain_with_http);
    }

    // Count most common cell site types.
    if (!isset($_GET['cellsite_type'])) {
        statBox("count_cellsite_type.php", $db_vars, $conn, $domain_with_http);
    }

    // Count most common cell site types.
    if (!isset($_GET['time'])) {
        statBox("count_records_by_hour.php", $db_vars, $conn, $domain_with_http);
    }

    // Count most common tags
    if (!isset($_GET['tags'])) {
        statBox('count_most_common_tags.php', $db_vars, $conn, $domain_with_http);
    }
    echo "</div>";
    ?>