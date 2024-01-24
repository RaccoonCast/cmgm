<?php
    function removeParameterFromURL($url, $parameterName) {
        $urlParts = parse_url($url);
        parse_str($urlParts['query'], $queryParams);
        unset($queryParams[$parameterName]);
        $newQueryString = http_build_query($queryParams);
        return $urlParts['path'] . ($newQueryString ? '?' . $newQueryString : '');
    }

    // Include common functions
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

    include "includes/count_records_total.php";

    // Stat Box

    echo '<br><div class="statistics-wrapper">';

        $limit = 10000;
        $current_url = $_SERVER['REQUEST_URI'];
        echo '<div class="statistics-container">';
        echo '<div class="stat-box">';
        echo "<h3>$title</h3>";
        $q = $_GET['q'];
        include "includes/$q";
        $url = removeParameterFromURL($current_url, "q");
        $url = removeParameterFromURL($url, "title");
        echo '<a class="bottomlink" href="'.str_replace("ViewMore.php", "", $url).'">View all stats</a>'; // Link to the file
        echo '</div></div>';

    echo "</div>";

    // URLs for navigation
    $map_url = $domain_with_http . "/database/Map.php?latitude=" . $default_latitude . "&longitude=" . $default_longitude . "&zoom=11" . preg_replace('/\/fun\/ViewMore.php\?limit=\d+/', '', $current_url);
    $db_url = $domain_with_http . "/database/DB.php?latitude=" . $default_latitude . "&longitude=" . $default_longitude . "&zoom=11" . preg_replace('/\/fun\/ViewMore.php\?limit=\d+/', '', $current_url);
    $fun_url = $domain_with_http . "/fun/";

    // Output navigation links
    echo '<br><br><a href='.$map_url.'>View filters on Map</a>';
    echo '<br><a href='.$db_url.'>View filters on DB</a>';
    echo '<br><a href='.$fun_url.'>Clear all filters</a>';

    // Close the database connection
    $conn->close();
?>
