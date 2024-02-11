<?php
// URLs for navigation
    $map_url = $domain_with_http . "/database/Map.php?latitude=" . $default_latitude . "&longitude=" . $default_longitude . "&zoom=11" . preg_replace('/\/fun\/\?limit=\d+/', '', $current_url);
    $db_url = $domain_with_http . "/database/DB.php?latitude=" . $default_latitude . "&longitude=" . $default_longitude . "&zoom=11" . preg_replace('/\/fun\/\?limit=\d+/', '', $current_url);
    $fun_percents_url = !isset($_GET['percents_view']) ? '<a href="' . $current_url . '&percents_view">Stats with percentages</a>' :
                                                         '<a href="' . str_replace("&percents_view", "", $current_url) . '">Stats with raw numbers</a>';
    $fun_url = $domain_with_http . "/fun/";

    // Output navigation links
    echo '<div id="footerContainer"><footer>';
    echo '<a href='.$domain_with_http.'>Home</a>';
    echo '<a href='.$map_url.'>Map</a>';
    echo '<a href='.$db_url.'>DB</a>';
    echo '<span id="divider"></span>';
    echo ''.$fun_percents_url.'';
    echo '<a href='.$fun_url.'>Clear all filters</a>';
    echo '</footer></div>'
?>