<?php
// SQL Login Information
include "../includes/functions/sqlpw.php";

// Get textbox values from form.php
include  "includes/submit/get-textbox-values.php";

// Check if data already in database
include  "includes/submit/duplicate-check.php";

// Compute SQL queries
include  "includes/submit/sql-query.php";
?>