<?php

error_reporting(E_ALL and -E_NOTICE and -E_DEPRECATED);
ini_set("display_errors", "On");
date_default_timezone_set("America/Los_Angeles"); // or your local timezone

// Import FaaResponse class and GET function
include "utils/FAAResponse.php";
include "utils/FAARequest.php";
include "utils/FAAHandleErrors.php";

// Include CMGM-related functions
// For accent-color CSS
$allowGuests = true;
include "../includes/functions/sqlpw.php";
include "../includes/useridsys/native.php";

// Include functionality for storing FAA records longterm
include "utils/DB.php";

// Check that ASN is passed
if (!isset($_GET["asn"])) {
  // ASN not passed, redirect to search page
  http_response_code(302);
  header("location:search");
  return;
}

$asn = strtoupper($_GET["asn"]);

// Check that ASN is formed correctly
$re = "/^(\d{2}|\d{4})-(\w{3})-(\d+)-(OE|NRA)$/";
$re_match = preg_match($re, $asn, $cGroups);
if (!$re_match) {
  http_response_code(400);
  echo "Malformed asn parameter";
  return;
}

// Check if year is 2-digit instead of 4-digit
$year = $cGroups[1];
if (strlen($year) == 2) {
  // Will only work up to 2065 obviously, but ~1969 is the oldest OE3A record with this structure I know of
  if ((int) $year < 65) {
    $cGroups[1] = "20{$year}";
  } else {
    $cGroups[1] = "19{$year}";
  }
}

// Check if sequence number has leading zeroes
$cGroups[3] = ltrim($cGroups[3], "0");

// Re-assemble ASN with all changes
$asn = "{$cGroups[1]}-{$cGroups[2]}-{$cGroups[3]}-{$cGroups[4]}";

// Check if ASN already present in DB
$result = fetchFromDb($conn, $cGroups[0]);

// echo "Last Updated:" . $result["last_updated"];
if (is_array($result) && !isset($_GET["updateDb"])) {
  // Get JSON from db
  $json = $result["data"];

  $lastUpdated_date = date("Y-m-d", strtotime($result["last_updated"]));
  $lastUpdated_time = date("Y-m-d H:i:s", strtotime($result["last_updated"]));
} else {
  // Make fresh request to FAA
  $freshRequest = true;
  $json = makeRequest($cGroups[1], $cGroups[2], $cGroups[3], $cGroups[4]);

  // "Last updated" time is right now (since we just did the request)
  $lastUpdated_date = date("Y-m-d");
  $lastUpdated_time = date("Y-m-d H:i:s");
}

// Deserialize data from JSON
$data = json_decode($json, true, 512, JSON_INVALID_UTF8_SUBSTITUTE);

// Check for errors in parsed data
$err = handleFAAErrors($data, $json);
if ($err) {
  return;
} elseif ($freshRequest) {
  // Add to DB (since there were no errors)
  addToDb($conn, $asn, $json);
}

// Parse data against known response class
$faaResponse = FaaResponse::fromArray($data);
?>

<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
      :root {
        --accent-color: #<?= $userID == "guest" ? "black" : $accent_color ?>;
      }
    </style>
    <title><?= $faaResponse->asn ?></title>
    <link rel="stylesheet" href="css/index.css">
    <script src="../js/copyToClipboard.js"></script>
    <script src="js/downloadLetter.js"></script>
    <script src="js/removeUpdateParams.js"></script>
    <?php include "js/updateUrlAsn.js.php"; ?>
  </head>
  <body>
  <div class="container">
    <div style="width: 100%;">
      <h1 style="text-decoration: underline; width: 60%; float: left;">Form 7460-1 for ASN <?= $asn ?></h1>
        <span id="lastUpdatedContainer">
          <h4 id="lastUpdatedText" title="<?= $lastUpdated_time ?>">Cached On: <?= $lastUpdated_date ?></h4>
          <img id="refreshIcon" src="img/refresh.svg" onclick="window.location.replace(window.location.href + '&updateDb');"/>
        </span>
    </div>

    <?php $endDate = $faaResponse->durationCode_ended ? date("Y-m-d", ((int) $faaResponse->durationCode_ended) / 1000000) : null; ?>
    <div class="section">
      <h2>Basic Info</h2>  
      <p>
        <span class="label">ASN:</span> <?= htmlspecialchars($faaResponse->asn) ?>
      </p>
      <p>
        <span class="label">Preceding ASN:</span>
         <?= $faaResponse->priorAsn ? "<a href=\"?asn={$faaResponse->priorAsn}\">{$faaResponse->priorAsn}</a>" : "N/A" ?>
      </p>
      <p>
        <span class="label">Created On:</span> <?= date("Y-m-d H:i:s", $faaResponse->createdOn / 1000) ?>
      </p>
      <p>
        <span class="label">Updated On:</span> <?= date("Y-m-d H:i:s", $faaResponse->updatedOn / 1000) ?>
      </p>
      <p>
        <span class="label">Case Status:</span> <?= htmlspecialchars($faaResponse->caseStatus) ?>
      </p>
      <p>
        <span class="label">Duration:</span> <?= htmlspecialchars(
          $faaResponse->durationCodeLabel
          // $faaResponse->durationCodeLabel == "Temporary" ? "Temporary (Until {$endDate})" : $faaResponse->durationCodeLabel
        ) ?>
      </p>
    </div>


    <?php
    $coord = $faaResponse->pointConverted;
    $coords = "{$coord->latitude}, {$coord->longitude}";
    ?>
    <div class="section">
      <h2>Location</h2>
      <p>
        <span class="label">Height (AGL):</span> <?= htmlspecialchars($faaResponse->heightAglFoot) ?> ft
      </p>
      <p>
        <span class="label">Elevation:</span> <?= htmlspecialchars($faaResponse->elevationFoot) ?> ft
      </p>
      <p>
        <span class="label">Description:</span> <?= htmlspecialchars($faaResponse->locationDescription) ?>
      </p>
      <p>
        <span class="label">Nearest County:</span> <?= htmlspecialchars($faaResponse->countyLabel) ?>
      </p>
      <p>
        <span class="label">Coordinates:</span>
        <a href="#" onclick="copyToClipboard('<?= $coords ?>'); return false;"> <?= htmlspecialchars($coords) ?></a>
      </p>
    </div>

    <?php $contactData = $faaResponse->sponsorJsContact; ?>
    <div class="section">
      <h2>Sponsor</h2>
      <p>
        <span class="label">Name: </span><?= htmlspecialchars($contactData->fullName) ?></p>
      <p>
        <span class="label">Owner Display Name:</span> <?= htmlspecialchars($faaResponse->formRepOwnerDisplayName) ?></p>
      <p>
        <span class="label">Email: </span><?= htmlspecialchars(str_replace("mailto:", "", $contactData->email)) ?></p>
      <p>
        <span class="label">Phone: </span><?= htmlspecialchars(str_replace("tel:", "", $contactData->phone)) ?></p>
      <p>
        <span class="label">Address: </span><?= htmlspecialchars(
          "{$contactData->street}, {$contactData->locality}, {$contactData->region} {$contactData->postcode} ({$contactData->extension})"
        ) ?></p>
    </div>

    <?php $contactData = $faaResponse->formRepJsContact; ?>
    <div class="section">
      <h2>Sponsor's Representative</h2>
      <p><span class="label">Name: </span><?= htmlspecialchars($contactData->fullName) ?></p>
      <span class="label">Owner Display Name:</span> <?= htmlspecialchars($faaResponse->formRepOwnerDisplayName) ?></p>
      <p><span class="label">Email: </span><?= htmlspecialchars(str_replace("mailto:", "", $contactData->email)) ?></p>
      <p><span class="label">Phone: </span><?= htmlspecialchars(str_replace("tel:", "", $contactData->phone)) ?></p>
      <p><span class="label">Address: </span><?= htmlspecialchars(
        "{$contactData->street}, {$contactData->locality}, {$contactData->region} {$contactData->postcode} ({$contactData->extension})"
      ) ?></p>
    </div>

   

    <!-- Letters + Frequencies section -->
    <div class="section">
      <h2>Letters</h2>
      <?php if (isset($faaResponse->letterMetaData)) {
        foreach ($faaResponse->letterMetaData as $letter) {
          $formattedDate = date("Y-m-d", (int) $letter->letterDate / 1000); ?>
          
          <a href="#" onclick="downloadLetter('<?= $letter->letterId ?>', '<?= $letter->externalStatusCode ?>', event); return false;" ><?= $formattedDate ?> - <?= $letter->externalStatusCode ?></a>
          <br/>
      <?php
        }
      } else {
         ?>
        <span style="font-style: italic;">[No Letters Found]</span>
        <br/>
      <?php
      } ?>

      <br/>

       <!-- PHP code to properly sort frequency -->
      
      <?php if (!empty($faaResponse->frequencies) && is_array($faaResponse->frequencies)) {

        include "utils/sort_freq.php";
        $frequencies = sortFrequencies($faaResponse->frequencies);
        ?>
        <h2>Frequencies</h2>
        <details>
          <summary id="freqSummary"></summary>
          <table style="width: 100%; border-collapse: collapse;">
            <thead>
              <tr>
                <th class="freqTableHeader">
                  Freq Band
                </th>
                <th class="freqTableHeader">
                  ERP
                </th>
              </tr>
            </thead>
            <tbody>
              <?php
              $usedFreqs = [];
              foreach ($frequencies as $freq) {

                // $freq is a Frequency object
                $bandObj = $freq->band; // FrequencyBand
                $erpObj = $freq->erp; // FrequencyEirp

                // Format the frequency band
                if (is_array($bandObj->value) && count($bandObj->value) === 2) {
                  list($low, $high) = $bandObj->value;
                  $bandLabel = "{$low}\u{2013}{$high} {$bandObj->uom}";
                } elseif (is_array($bandObj->value) && count($bandObj->value) === 1) {
                  $bandLabel = "{$bandObj->value[0]} {$bandObj->uom}";
                } else {
                  // fallback if empty or unexpected
                  $bandLabel = "Unknown";
                }

                // Format the ERP
                $erpLabel = "{$erpObj->value} {$erpObj->uom}";

                // Check that band isn't already added (skip duplicates)
                if (in_array($bandLabel, $usedFreqs)) {
                  continue;
                } else {
                  // if not, add it
                  array_push($usedFreqs, $bandLabel);
                }
                ?>
                <tr>
                  <td style="padding: 0.5rem; border-bottom: 1px solid #eee;">
                    <?= htmlspecialchars($bandLabel, ENT_QUOTES, "UTF-8") ?>
                  </td>
                  <td style="padding: 0.5rem; border-bottom: 1px solid #eee;">
                    <?= htmlspecialchars($erpLabel, ENT_QUOTES, "UTF-8") ?>
                  </td>
                </tr>
              <?php
              }
              ?>
            </tbody>
          </table>
        </details>
      <?php
      } ?>
    </div>
    
 
    <div class="section">
      <h2>Structure</h2>
      <p><span class="label">Type:</span> <?= htmlspecialchars($faaResponse->structureType->label) ?></p>
      <p><span class="label">Type Code:</span> <?= htmlspecialchars($faaResponse->structureType->structureType) ?></p>
      <p><span class="label">Structure Name:</span> <?= htmlspecialchars($faaResponse->structureName) ?></p>
      <span class="label">FCC ASR:</span> <?= htmlspecialchars($faaResponse->fccAsr) ?></p>
      <p><span class="label">Proposal:</span> <?= nl2br(htmlspecialchars($faaResponse->proposalDescription)) ?></p>
    </div>

</body>
</html>