<?php
        $curPageName = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);  

        $current_url = $_SERVER['REQUEST_URI'];
        echo '<div class="statistics-container">';
        echo '<div class="stat-box">';
        echo "<h3>$title</h3>";
        include "includes/counts/$file";
        $api_link = str_replace("/fun/", "/api/pciplus/fun.php", $current_url) . "&q=$file";
        $just_this_link = str_replace("/fun/", "/fun/ViewMore.php", $current_url);
        echo '<div class="bottomlink">';

        if (strpos($current_url, 'ViewMore.php') === false) {
            echo '<a href="'. $just_this_link . '&q=' . $file . '&title=' . $title . '">Just this </a> | ';
          } else {
            echo '<a href="'.str_replace("ViewMore.php", "", $current_url).'">View all stats</a> | '; // Link to the file
          }

          echo '<a href="#" onclick="hideStatBox(this);">Hide Me </a> | ';
          echo '<a href="'.$api_link.'">API Link</a>';
          echo '</div></div></div>';
?>