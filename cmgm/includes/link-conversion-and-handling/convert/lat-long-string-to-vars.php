<?php
$pattern = '/Latitude:\s*([\d.]+)˚([NS]),\s*Longitude:\s*([\d.]+)˚([EW])/';

// Check if the input matches the pattern
if (preg_match($pattern, $data, $matches)) {
    // Extract and convert latitude and longitude
    $latitude = (float)$matches[1] * ($matches[2] === 'S' ? -1 : 1);
    $longitude = (float)$matches[3] * ($matches[4] === 'W' ? -1 : 1);
    
    // Set conversion type to prevent further includes
    $conv_type = "Complicated Lat,Lng";
}
?>