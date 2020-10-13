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
                    case 1:  $row_id = $value; break;
                    case 2:  $date_added = $value; break;
                    case 3:  $id = $value; break;
                    case 4:  $id_2 = $value; break;
                    case 5:  $id_3 = $value; break;
                    case 6:  $id_4 = $value; break;
                    case 7:  $id_5 = $value; break;
                    case 8:  $carrier = $value; break;
                    case 9:  $latitude = $value; break;
                    case 10:  $longitude = $value; break;
                    case 11:  $city = $value; break;
                    case 12:  $zip = $value; break;
                    case 13:  $state = $value; break;
                    case 14:  $address = $value; break;
                    case 15:  $bio = $value; break;
                    case 16:  $evidence_score = $value; break;
                    case 17:  $evidence_link = $value; break;
                    case 18:  $photo_link = $value; break;
                    case 19:  $attached_file_link = $value; break;
                    case 20:  $permit_cellsite = $value; break;
                    case 21:  $permit_suspected_carrier = $value; break;
                    case 22:  $trails_match = $value; break;
                    case 23:  $other_carriers_dont = $value; break;
                    case 24:  $antennas_match_carrier = $value; break;
                    case 25:  $cellmapper_triangulation = $value; break;
                    case 26:  $image_evidence = $value; break;
                    case 27:  $verified_by_visit = $value; break;
                    case 28:  $sector_split_match = $value; break;
                    case 29:  $contact_permit_carrier = $value; break;
                    case 30:  $archival_antenna_addition = $value; break;
                    case 31:  $only_reasonable_location = $value; break;
                    case 32:  $carrier_multiple = $value;
                    echo nl2br("<p class=" . "widget" .">Database ID: " . $row_id . "   </p>");
                    echo nl2br('<a target=_blank class="widget widget_emote" href="Edit.php?row_id='.$row_id.'">🔧</a>');
                    echo nl2br('<a target=_blank class="widget widget_emote" href="Delete.php?row_id='.$row_id.'">❌</a>');
                    echo ("<p>" . $carrier . "</p>");
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
                      $end = "map$beginning&latitude=$latitude&longitude=$longitude&zoom=18&showTowerLabels=false";
                      if (empty($id)) {
                        echo ("<a target=_blank href=https://www.cellmapper.net/$end>CellMapper</a>");
                        echo "<br>";
                      } else {
                        echo ("<a target=_blank href=https://www.cellmapper.net/$end>" . $id . "</a>");
                        echo "<br>";
                      }

                      echo nl2br('<a target="_blank" href="https://maps.google.com/maps?f=q&source=s_q&hl=en&q=' .$latitude . ',' .$longitude . '">' . $address . ' <br>' . $city . ', ' . $state . ' ' . $zip . '</a>');
                      echo ("<p>Evidence Score: " . $evidence_score . "</p>");

                      if(substr($evidence_link, 0, 14) == "image-evidence") {
                         $evidence_link = "uploads/$evidence_link";
                      }

                      if (!empty($evidence_link)) {
                        echo ("<a target=" . "_blank" . " href=" . "$evidence_link" . ">Evidence</a>");
                        echo "<br>";
                      }
                      if (!empty($bio)) {
                      echo ("<p>" . $bio . "</p>");
                    }
                    break;

                }
          }

    }
?>
</tbody>
</table>
</body>
</html>
