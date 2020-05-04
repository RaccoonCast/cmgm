<!doctype html>
<html lang="en">
<head>
  <?php
  include '../functions.php';
   ?>
</head>
<body class="body">
<?php
$row_id = $_GET['row_id'];

$conn = mysqli_connect($servername, $username, $password, $dbname);
$sql = "SELECT * FROM database_db WHERE row_id = $row_id;";
$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) { // Important line !!! Check summary get row on array ..
  $colCount = 1;

    foreach ($row as $field => $value) {

      $sepCount = ($colCount++);

                  switch ($sepCount) {
                    case 1: $row_id = $value; break;
                    case 2: $id = $value; break;
                    case 3: $carrier = $value; break;
                    case 4: $carrier_multiple = $value; break;
                    case 5: $type = $value; break;
                    case 6: $lat = $value; break;
                    case 7: $long = $value; break;
                    case 8: $firstseen = $value; break;
                    case 9: $bands = $value; break;
                    case 10: $city = $value; break;
                    case 11: $zip = $value; break;
                    case 12: $state = $value; break;
                    case 13: $address = $value; break;
                    case 14: $bio = $value; break;
                    case 15: $permit_cellsite = $value; break;
                    case 16: $permit_suspected_carrier = $value; break;
                    case 17: $trails_match = $value; break;
                    case 18: $other_carriers_dont = $value; break;
                    case 19: $antennnas_match_carrier = $value; break;
                    case 20: $evidence_score = $value; break;
                    case 21:
                    $evidence_text = $value;
                    echo ("<p>Database ID: " . $row_id . "</p>");
                    echo ("<p>" . $carrier . "</p>");
                    echo ("<p>" . $type . "</p>");
                     if ("$carrier" == "T-Mobile") {
                      $beginning = "?MCC=310&MNC=260";
                    } elseif ("$carrier" == "Sprint") {
                      $beginning = "?MCC=310&MNC=120";
                    } elseif ("$carrier" == "ATT") {
                      $beginning = "?MCC=310&MNC=410";
                    } elseif ("$carrier" == "Verizon") {
                      $beginning = "?MCC=311&MNC=480";
                    } else {
                      $beginning = "?MCC=310&MNC=260";
                    }
                      $end = "map$beginning&latitude=$lat&longitude=$long&zoom=18&showTowerLabels=false";
                      if (empty($id)) {
                        echo ("<a target=_blank href=https://www.cellmapper.net/$end>CellMapper</a>");
                        echo "<br>";
                      } else {
                        echo ("<a target=_blank href=https://www.cellmapper.net/$end>" . $id . "</a>");
                        echo "<br>";
                      }

                      echo nl2br('<a target="_blank" href="https://maps.google.com/maps?f=q&source=s_q&hl=en&q=' .$lat . ',' .$long . '">' . $address . ' <br>' . $city . ', ' . $state . ' ' . $zip . '</a>');
                    break;

                }
          }

    }
?>
</tbody>
</table>
</body>
</html>
