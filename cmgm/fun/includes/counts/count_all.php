<?php
    echo '<div class="statistics-wrapper">';

    // Count most common cities.
    if (!isset($_GET['city'])) {
        statBox("count_most_common_cities.php", "Most Common Cities", $db_vars, $conn, $domain_with_http);
    }
    
        // Count most common counties.
    if (!isset($_GET['county'])) {
       statBox("count_most_common_counties.php", "Most Common Counties", $db_vars, $conn, $domain_with_http);
    }

        // Count most common states.
    if (!isset($_GET['state'])) {
       statBox("count_most_common_states.php", "Most Common States", $db_vars, $conn, $domain_with_http);
    }
    
    // Count most records created during X day.
    if (!isset($_GET['date'])) {
        statBox("count_records_by_day.php", "Most pins per day", $db_vars, $conn, $domain_with_http);
    }

    // Count most records created during X month.
    if (!isset($_GET['date']) && !isset($_GET['month'])) {
        if (!isset($_GET['year'])) {
        statBox("count_records_by_month.php", "Pins created by month during every year", $db_vars, $conn, $domain_with_http);
        } else {
        $year_tmp = $_GET['year'];
        statBox("count_records_by_month.php", "Pins created by month during $year_tmp", $db_vars, $conn, $domain_with_http);    
        }
    }

    // Most created by X user.
    if (!isset($_GET['username'])) {
        statBox("count_records_created_by_user.php", "Pins created by user", $db_vars, $conn, $domain_with_http, $domain_with_http);
    }

    // Carrier counts.
    if (!isset($_GET['carrier'])) {
        statBox("count_most_common_carrier.php", "Pins per carrier", $db_vars, $conn, $domain_with_http);
    }

    // Count concealed vs unconcealed sites.
    if (!isset($_GET['concealed'])) {
        statBox("count_concealed_vs_unconcealed.php", "Concealed vs Unconcealed Sites", $db_vars, $conn, $domain_with_http);
    }

    // Records during last X whatever.
    if (!isset($_GET['date']) && !isset($_GET['year']) && !isset($_GET['month'])) {
        statBox("count_records_by_timeframe.php", "Pins created by timelines", $db_vars, $conn, $domain_with_http);
    }

    // Count most records created during X year.
    if (!isset($_GET['date']) && !isset($_GET['year'])) {
        statBox("count_records_by_year.php", "Pins created by year", $db_vars, $conn, $domain_with_http);
    }

    // Count most common cell site types.
    if (!isset($_GET['cellsite_type'])) {
        statBox("count_cellsite_type.php", "Most common cellsite types", $db_vars, $conn, $domain_with_http);
    }

    // Count most common cell site types.
    if (!isset($_GET['time'])) {
        statBox("count_records_by_hour.php", "Pin Times", $db_vars, $conn, $domain_with_http);
    }
    echo "</div>";
    ?>