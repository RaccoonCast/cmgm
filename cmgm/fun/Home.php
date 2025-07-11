<!DOCTYPE HTML>
<html>
    <head>
    <link rel="canonical" href="https://cmgm.us/fun">
    <?php
    // Add limit if necessary
    if (!isset($_GET['q'])) {
        if (!isset($_GET['limit'])) {
            // add limit to header via script
            ?>
            <script>
                // const params = new URLSearchParams(window.location.search);
                const url = new URL(window.location);
                url.searchParams.set('limit', '15');
                window.history.replaceState({}, '', url);
            </script>
            <?php $limit = 15;
        } else {$limit = $_GET['limit'];}
    }

    $allowGuests = true;

    // Include common functions
    $titleOverride = 'true';
    include '../functions.php';
    echo '<script src="includes/functions/hideme.js"></script>';

    // Get current URL
    $current_url = $_SERVER['REQUEST_URI'];

    // Include DB filter
    include '../database/includes/DB-filter-get.php';

    // Set default filter
    $db_vars = isset($db_vars) ? '1=1 ' . $db_vars : '1=1 ';

    // Function to create a statistics box
    include 'includes/functions/statbox.php';

    include 'includes/functions/getPercent.php'; //
    include 'includes/counts/count_records_total.php';

    echo "</head><body>";

    // Display filters if 'showsql' is set
    // (Run within <body>, or it starts <body> early)
    if (isset($_GET['showsql'])) {
        echo 'Filters: ' . $db_vars . '<br><hr><br>';
    }

    if (!isset($_GET['q'])) {
        include 'includes/counts/count_all.php';
    } else {
        echo '<div class="statistics-wrapper">';
        statBox(preg_replace('/[^a-zA-Z._]/', '', $_GET['q']), $db_vars, $conn, $domain_with_http);
        echo '</div>';
    }

    include 'includes/footer.php';
    echo '</body></html>';
    // Close the database connection
    $conn->close();

?>
