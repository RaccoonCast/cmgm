<?php
// Regular functions
include "../functions.php";

// Get textbox values from form.php
include  "includes/submit/get-textbox-values.php";

// Check if data already in database
include  "includes/submit/duplicate-check.php";

// Calculate evidence scores
include  "includes/submit/evidence-scores.php";

// Compute SQL queries
include  "includes/submit/sql-query.php";
?>
