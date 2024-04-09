<?php
    function statBox($file, $db_vars, $conn, $domain_with_http) {
      $limit = is_numeric($_GET['limit']) ? $_GET['limit'] : 15;
      if (isset($_GET['q'])) $limit = 99999;

      $curPageName = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);  

      $current_url = $_SERVER['REQUEST_URI'];
      echo '<div class="statistics-container">';
      echo '<div class="stat-box">';
      include "includes/counts/$file";
      $api_link = str_replace("/fun/", "/api/pciplus/fun.php", $current_url) . "&q=$file";
      $just_this_link = str_replace("/fun/", "/fun/ViewMore.php", $current_url);
      echo '<div class="bottomlink">';

      if (!isset($_GET['q'])) {
          echo '<a href="'. $current_url . '&q=' . $file . '">Just this </a> | ';
        } else {
          $q_tmp = $_GET['q'];
          echo '<a href="'.str_replace("&q=$q_tmp", "", $current_url).'">View all stats</a> | '; // Link to the file
        }

        echo '<a href="#" onclick="hideStatBox(this);">Hide Me </a> | ';
        echo '<a href="'.$api_link.'">API Link</a>';
        echo '</div></div></div>';
    }


?>