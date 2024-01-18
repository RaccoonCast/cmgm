<?php
// Mapping array for state abbreviations to full names
$stateMapping = array(
    'AL' => 'Alabama',
    'AK' => 'Alaska',
    'AZ' => 'Arizona',
    'AR' => 'Arkansas',
    'CA' => 'California',
    'CO' => 'Colorado',
    'CT' => 'Connecticut',
    'DE' => 'Delaware',
    'DC' => 'District of Columbia',
    'FL' => 'Florida',
    'GA' => 'Georgia',
    'HI' => 'Hawaii',
    'ID' => 'Idaho',
    'IL' => 'Illinois',
    'IN' => 'Indiana',
    'IA' => 'Iowa',
    'KS' => 'Kansas',
    'KY' => 'Kentucky',
    'LA' => 'Louisiana',
    'ME' => 'Maine',
    'MD' => 'Maryland',
    'MA' => 'Massachusetts',
    'MI' => 'Michigan',
    'MN' => 'Minnesota',
    'MS' => 'Mississippi',
    'MO' => 'Missouri',
    'MT' => 'Montana',
    'NE' => 'Nebraska',
    'NV' => 'Nevada',
    'NH' => 'New Hampshire',
    'NJ' => 'New Jersey',
    'NM' => 'New Mexico',
    'NY' => 'New York',
    'NC' => 'North Carolina',
    'ND' => 'North Dakota',
    'OH' => 'Ohio',
    'OK' => 'Oklahoma',
    'OR' => 'Oregon',
    'PA' => 'Pennsylvania',
    'PR' => 'Puerto Rico',
    'RI' => 'Rhode Island',
    'SC' => 'South Carolina',
    'SD' => 'South Dakota',
    'TN' => 'Tennessee',
    'TX' => 'Texas',
    'UT' => 'Utah',
    'VT' => 'Vermont',
    'VI' => 'Virgin Islands',
    'VA' => 'Virginia',
    'WA' => 'Washington',
    'WV' => 'West Virginia',
    'WI' => 'Wisconsin',
    'WY' => 'Wyoming'
);

$sql = "SELECT state, COUNT(state) AS state_count FROM db WHERE $db_vars_unamended AND state <> '' GROUP BY state ORDER BY state_count DESC LIMIT $limit;";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    $locationAbbreviation = $row['state'];
    
    // Convert state abbreviation to full name using the mapping array
    $locationFullName = isset($stateMapping[$locationAbbreviation]) ? $stateMapping[$locationAbbreviation] : $locationAbbreviation;

    $location_for_url = $current_url . "&state=" . $locationAbbreviation;

    $url = '<a href="'. $location_for_url.'">'.$locationFullName.'</a>';
    $plural_namething = ($row["state_count"] > 1) ? " records" : " record";
    echo $row["state_count"] . $plural_namething . " created in " . $url . "<br>";
}

echo "<br>";
?>
