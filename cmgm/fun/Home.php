<?php
    // Add limit to URL if not set.
    !isset($_GET['limit']) ? header('Location: '.$_SERVER['REQUEST_URI'].'?limit=15') && die() : $limit = $_GET['limit'];

    // Include common functions
    $titleOverride = "true";
    include "../functions.php";
    echo '<script src="includes/functions/hideme.js"></script>';

    // Get current URL
    $current_url = $_SERVER['REQUEST_URI'];

    // Include DB filter
    include "../database/includes/DB-filter-get.php";

    // Display filters if 'showsql' is set
    if (isset($_GET['showsql'])) {
        echo "Filters: " . $db_vars . "<br><hr><br>";
    }

    // Set default filter
    $db_vars = isset($db_vars) ? "1=1 " . $db_vars : "1=1 ";

    // Function to create a statistics box
    function statBox($file, $title, $db_vars, $conn, $domain_with_http) {
        $limit = is_numeric($_GET['limit']) ? $_GET['limit'] : 15;
        include "includes/functions/statbox.php";
    }

    include "includes/functions/getPercent.php"; //
    include "includes/counts/count_records_total.php";
    include "includes/counts/count_all.php";
    include "includes/footer.php";

    // Close the database connection
    $conn->close();
?>
