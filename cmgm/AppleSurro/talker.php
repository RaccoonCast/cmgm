<?php
if ($argc < 4) { 
    echo "Usage: php talker.php <carrier> <cid> <tac> [--desc|--asc] [--<mccmnc>]\n";
    exit(1);
}

$carrier = $argv[1];
$cid = $argv[2];
$tac = $argv[3];

$mcc = substr($carrier, 0, 3);
$mnc = substr($carrier, 3, 3);

$command = "node script-surro.js $mcc $mnc $cid $tac";
$array = json_decode(shell_exec($command));

if (!is_array($array)) {
    echo "Error: Invalid response from script-surro.js\n";
    exit(1);
}

// Default options
$sortOrder = 'asc';
$filterMccMnc = null;

// Parse optional arguments
foreach ($argv as $arg) {
    if ($arg === '--desc') {
        $sortOrder = 'desc';
    } elseif ($arg === '--asc') {
        $sortOrder = 'asc';
    } elseif (preg_match('/^--(\d{6})$/', $arg, $matches)) {
        $filterMccMnc = $matches[1];
    }
}

// Apply filtering if needed
if ($filterMccMnc) {
    $array = array_filter($array, function ($item) use ($filterMccMnc) {
        return ($item->mcc . $item->mnc) === $filterMccMnc;
    });
}

// Apply sorting
usort($array, function ($a, $b) use ($sortOrder) {
    return $sortOrder === 'desc' ? $b->eNB <=> $a->eNB : $a->eNB <=> $b->eNB;
});

// Function to print a formatted CLI table
function printTable($data) {
    if (empty($data)) {
        echo "No results found.\n";
        return;
    }

    $headers = ['Lat', 'Lon', 'Accuracy', 'eNB', 'MCC', 'MNC'];
    $widths = array_map('strlen', $headers);

    // Calculate column widths
    foreach ($data as $row) {
        $widths[0] = max($widths[0], strlen(number_format($row->lat, 6)));
        $widths[1] = max($widths[1], strlen(number_format($row->lon, 6)));
        $widths[2] = max($widths[2], strlen($row->accuracy));
        $widths[3] = max($widths[3], strlen($row->eNB));
        $widths[4] = max($widths[4], strlen($row->mcc));
        $widths[5] = max($widths[5], strlen($row->mnc));
    }

    $divider = '+-' . implode('-+-', array_map(fn($w) => str_repeat('-', $w), $widths)) . '-+';

    // Print table header
    echo "$divider\n";
    printf(
        "| %-" . $widths[0] . "s | %-" . $widths[1] . "s | %-" . $widths[2] . "s | %-" . $widths[3] . "s | %-" . $widths[4] . "s | %-" . $widths[5] . "s |\n",
        ...$headers
    );
    echo "$divider\n";

    // Print table rows
    foreach ($data as $row) {
        printf(
            "| %-" . $widths[0] . "s | %-" . $widths[1] . "s | %-" . $widths[2] . "s | %-" . $widths[3] . "s | %-" . $widths[4] . "s | %-" . $widths[5] . "s |\n",
            number_format($row->lat, 6),
            number_format($row->lon, 6),
            $row->accuracy,
            $row->eNB,
            $row->mcc,
            $row->mnc
        );
    }

    echo "$divider\n";
}

// Print the formatted table
printTable($array);
?>
