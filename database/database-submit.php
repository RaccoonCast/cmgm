<?php
// Regular functions
include "../functions.php";

// Google Maps convert latitude and longitude to Physical Address
include  "includes/database-submit/google-maps.php";

// Get textbox values from database-form.php
include  "includes/database-submit/get-textbox-values.php";

// Check if data already in database
include  "includes/database-submit/duplicate-check.php";

// Calculate evidence scores
include  "includes/database-submit/evidence-scores.php";

// Compute SQL queries
include  "includes/database-submit/sql-query.php";
?>
