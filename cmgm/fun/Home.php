<?php
    // Add limit to URL if not set.
    !isset($_GET['limit']) ? header('Location: '.$_SERVER['REQUEST_URI'].'?limit=15') && die() : $limit = $_GET['limit'];

    // Include common functions
    $titleOverride = "true";
    include "../functions.php";

    // Get current URL
    $current_url = $_SERVER['REQUEST_URI'];

    // Include DB filter
    include "../database/includes/DB-filter-get.php";

    // Display filters if 'showsql' is set
    if (isset($_GET['showsql'])) {
        echo "Filters: " . $db_vars_unamended . "<br><hr><br>";
    }

    // Set default filter
    $db_vars_unamended = isset($db_vars_unamended) ? "1=1 " . $db_vars_unamended : "1=1 ";

    // Function to create a statistics box
    function statBox($file, $title, $db_vars_unamended, $conn, $domain_with_http) {
        $limit = is_numeric($_GET['limit']) ? $_GET['limit'] : 15;
        $current_url = $_SERVER['REQUEST_URI'];
        echo '<div class="statistics-container">';
        echo '<div class="stat-box">';
        echo "<h3>$title</h3>";
        include "includes/$file";
        $link = str_replace("/fun/", "/fun/ViewMore.php", $current_url);
        
        echo '<a class="bottomlink" href="'. $link . '&q=' . $file . '&title=' . $title . '">Just this</a>'; // Link to the file
        echo '</div></div>';
    }
    
    
    include "includes/count_records_total.php";
    
    echo !($total == $total_out_of_everything) ? "<h3>With your current filters applied, you're looking at $total out of $total_out_of_everything CMGM pins.</h3>" : "<br>";

    echo '<div class="statistics-wrapper">';
    
    // Count most common cities.
    if (!isset($_GET['city'])) {
        statBox("count_most_common_cities.php", "Most Common Cities", $db_vars_unamended, $conn, $domain_with_http);
    }
    
        // Count most common counties.
    if (!isset($_GET['county'])) {
       statBox("count_most_common_counties.php", "Most Common Counties", $db_vars_unamended, $conn, $domain_with_http);
    }

        // Count most common states.
    if (!isset($_GET['state'])) {
       statBox("count_most_common_states.php", "Most Common States", $db_vars_unamended, $conn, $domain_with_http);
    }
    
    // Count most records created during X day.
    if (!isset($_GET['date'])) {
        statBox("count_records_by_day.php", "Most pins per day", $db_vars_unamended, $conn, $domain_with_http);
    }

    // Count most records created during X month.
    if (!isset($_GET['date']) && !isset($_GET['month'])) {
        if (!isset($_GET['year'])) {
        statBox("count_records_by_month.php", "Pins created by month during every year", $db_vars_unamended, $conn, $domain_with_http);
        } else {
        $year_tmp = $_GET['year'];
        statBox("count_records_by_month.php", "Pins created by month during $year_tmp", $db_vars_unamended, $conn, $domain_with_http);    
        }
    }

    // Most created by X user.
    if (!isset($_GET['username'])) {
        statBox("count_records_created_by_user.php", "Pins created by user", $db_vars_unamended, $conn, $domain_with_http, $domain_with_http);
    }

    // Carrier counts.
    if (!isset($_GET['carrier'])) {
        statBox("count_most_common_carrier.php", "Pins per carrier", $db_vars_unamended, $conn, $domain_with_http);
    }

    // Count concealed vs unconcealed sites.
    if (!isset($_GET['concealed'])) {
        statBox("count_concealed_vs_unconcealed.php", "Concealed vs Unconcealed Sites", $db_vars_unamended, $conn, $domain_with_http);
    }

    // Records during last X whatever.
    if (!isset($_GET['date']) && !isset($_GET['year']) && !isset($_GET['month'])) {
        statBox("count_records_by_timeframe.php", "Pins created by timelines", $db_vars_unamended, $conn, $domain_with_http);
    }

    // Count most records created during X year.
    if (!isset($_GET['date']) && !isset($_GET['year'])) {
        statBox("count_records_by_year.php", "Pins created by year", $db_vars_unamended, $conn, $domain_with_http);
    }


    echo "</div>";

    // URLs for navigation
    $map_url = $domain_with_http . "/database/Map.php?latitude=" . $default_latitude . "&longitude=" . $default_longitude . "&zoom=11" . preg_replace('/\/fun\/\?limit=\d+/', '', $current_url);
    $db_url = $domain_with_http . "/database/DB.php?latitude=" . $default_latitude . "&longitude=" . $default_longitude . "&zoom=11" . preg_replace('/\/fun\/\?limit=\d+/', '', $current_url);
    $fun_url = $domain_with_http . "/fun/";

    // Output navigation links
    echo '<div class="footerlinks">';
    echo '<br><a href='.$map_url.'>View filters on Map</a>';
    echo '<br><a href='.$db_url.'>View filters on DB</a>';
    echo '<br><a href='.$fun_url.'>Clear all filters</a>';
    echo '</div>';

    // Close the database connection
    $conn->close();
?>
