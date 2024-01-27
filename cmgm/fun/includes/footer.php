<?php
// URLs for navigation
    $map_url = $domain_with_http . "/database/Map.php?latitude=" . $default_latitude . "&longitude=" . $default_longitude . "&zoom=11" . preg_replace('/\/fun\/\?limit=\d+/', '', $current_url);
    $db_url = $domain_with_http . "/database/DB.php?latitude=" . $default_latitude . "&longitude=" . $default_longitude . "&zoom=11" . preg_replace('/\/fun\/\?limit=\d+/', '', $current_url);
    $fun_percents_url = !isset($_GET['percents_view']) ? '<a href="' . $current_url . '&percents_view">View stats with percentages</a>' :
                                                         '<a href="' . str_replace("&percents_view", "", $current_url) . '"> View stats with raw numbers</a>';
    $fun_url = $domain_with_http . "/fun/";

    // Output navigation links
    echo '<div class="footerlinks">';
    echo '<br><a href='.$map_url.'>View filters on Map</a>';
    echo '<br><a href='.$db_url.'>View filters on DB</a>';
    echo '<br>'.$fun_percents_url.'';
    echo '<br><a href='.$fun_url.'>Clear all filters</a>';
?>