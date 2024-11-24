<?php
// Function to generate URL
function generateURL($responses) {
    $totalLat = 0;
    $totalLng = 0;
    $polygon = [];
    $labels = [];
    $eNBCount = count($responses);

    // Loop through each eNB
    foreach ($responses as $eNB => $cells) {
        $cellLatitudes = [];
        $cellLongitudes = [];
        
        // Loop through each cell within the eNB
        foreach ($cells as $index => $cell) {
            $lat = $cell['lat'];
            $lng = $cell['lng'];
            
            // Accumulate latitudes and longitudes for the average
            $totalLat += $lat;
            $totalLng += $lng;

            // Add the coordinates to the polygon
            $polygon[] = "$lat,$lng";

            // Add the label: use the eNB ID + cell number if multiple eNBs; use the Cell ID if only one eNB
            if ($eNBCount > 1) {
                $labels[] = $eNB . '-' . $index;
            } else {
                $labels[] = $index;  // Use the Cell ID for a single eNB
            }
        }
    }

    // Calculate the average latitude and longitude
    $totalCells = array_sum(array_map('count', $responses)); // Total number of cells
    $avgLat = $totalLat / $totalCells;
    $avgLng = $totalLng / $totalCells;

    // Create the polygon string
    $polygonString = implode(',', $polygon);
    
    // Create the labels string
    $labelsString = implode(',', $labels);

    // Build the URL
    $url = "https://cmgm.us/database/Map.php?latitude=$avgLat&longitude=$avgLng&zoom=15.31150876055&polygon=$polygonString&polygonlabels=$labelsString";
    
    return $url;
}
$url = generateURL($responses);
?>