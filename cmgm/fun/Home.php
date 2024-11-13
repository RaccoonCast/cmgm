<?php
    echo "<!DOCTYPE HTML><html><head>" . PHP_EOL;
    ?>
<meta property="og:type" content="website">
<meta property="og:title" content="CMGM">
<meta property="og:url" content="<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'] ?>">
<meta property="og:image" content="https://cmgm.us/favicon.ico">
<meta property="og:description" content="1,234 Pins">
    <?php
    // Add limit to URL if not set.
    if (!isset($_GET['q'])) !isset($_GET['limit']) ? header('Location: '.$_SERVER['REQUEST_URI'].'?limit=15') && die() : $limit = $_GET['limit'];

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
    include "includes/functions/statbox.php";
    
    include "includes/functions/getPercent.php"; //
    include "includes/counts/count_records_total.php";

    echo "</head><body>";

    if (!isset($_GET['q'])) {
        include "includes/counts/count_all.php";
    } else {
        echo '<div class="statistics-wrapper">';
        statBox(preg_replace('/[^a-zA-Z._]/', '', $_GET['q']), $db_vars, $conn, $domain_with_http);
        echo '</div>';
    }

    include "includes/footer.php";
    echo "</body></html>";
    // Close the database connection
    $conn->close();
?>
