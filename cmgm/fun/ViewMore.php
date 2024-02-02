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
    echo '<script src="includes/functions/hideme.js"></script>';

    // Get current URL
    $current_url = $_SERVER['REQUEST_URI'];

    // Include DB filter
    include "../database/includes/DB-filter-get.php";

    // Display filters if 'showsql' is set
    if (isset($_GET['showsql'])) {
        echo "Filters: " . $db_vars . "<br><hr>";
    }

    // Set default filter
    $db_vars = isset($db_vars) ? "1=1 " . $db_vars : "1=1 ";

    include "includes/functions/getPercent.php";
    include "includes/counts/count_records_total.php";

    // Stat Box

    echo '<br><div class="statistics-wrapper">';

        $limit = 10000;
        $current_url = $_SERVER['REQUEST_URI'];
        $url = removeParameterFromURL($current_url, "q");
        $url = removeParameterFromURL($url, "title");
        $file = $_GET['q'];

    include "includes/functions/statbox.php";
    echo "</div>";
    include "includes/footer.php";

    // Close the database connection
    $conn->close();
?>
