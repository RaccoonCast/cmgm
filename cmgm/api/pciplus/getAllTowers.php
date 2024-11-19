<?php
//  cody and alps' purple iphones (CAAPI)
include "includes/functions.php";
include "../../includes/functions/convert-url.php";

  // If specific fields are requested, filter a-z0-9 to cheat sql injection prevention.
  $database_get_list = isset($_GET['properties']) ? preg_replace("/[^_a-zA-Z0-9,-]/", "", $_GET['properties']) : "*";
  
  // Check all LTE/NR fields for provided eNBs that match PLMN provided, remove edit_userid & edit_lock from fields returned by each tower.
  // Put each tower into $result_object PHP array.
  // Query using the $carrier variable directly
  $query = "SELECT carrier, lte_1, lte_2, lte_3, lte_4, lte_5, lte_6, lte_7, lte_8, lte_9, nr_1, nr_2, nr_3, nr_4 FROM db";

  // Execute the query
  if ($result = $conn->query($query)) {
    // Initialize an empty array for storing the result
    $result_object = [];

    // Fetch each row from the result
    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {

    // Determine carrier type
    $carrierType = isset($row['carrier']) ? $row['carrier'] : 'unknown';
    unset($row["carrier"]);
  
    // Remove empty values directly within the loop
    foreach ($row as $key => $value) {
        if (empty($value)) {
            unset($row[$key]);
        }
    }

    // Initialize the array for this carrier type if not already set
    if (!isset($carrierGroups[$carrierType])) {
      $carrierGroups[$carrierType] = array();
    }

    // Append the row data to the carrier type array
    $carrierGroups[$carrierType][] = $row;
    }

    // Free the result set
    $result->free();
  } else {
    // Handle error and pass the error message
    error($conn->error, $_GET['search']);
  }

echo json_encode($carrierGroups);

?>
