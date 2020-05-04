<!doctype html>
<html lang="en">
<head>
  <?php
  include '../functions.php';
   ?>
</head>
<body class="body">
<?php
$latitude = $_GET['latitude'];
$longitude = $_GET['longitude'];
$conn = mysqli_connect($servername, $username, $password, $dbname);

$sql2 = "SELECT DISTINCT *, (3959 * ACOS(COS(RADIANS($latitude)) * COS(RADIANS(latitude)) * COS(RADIANS(longitude) - RADIANS($longitude)) + SIN(RADIANS($latitude)) * SIN(RADIANS(latitude)))) AS DISTANCE FROM findlater ORDER BY distance LIMIT 1 ";
$result = mysqli_query($conn, $sql2); // First parameter is just return of "mysqli_connect()" function

while ($row = mysqli_fetch_assoc($result)) { // Important line !!! Check summary get row on array ..
  $colCount = 1;

    foreach ($row as $field => $value) {

      $sepCount = ($colCount++);

                  switch ($sepCount) {
                    case 1:
                      echo ("<p>Findlater ID: " . $value . "</p>");
                      $row_id = $value;
                        break;
                      case 5:
                          $lat = $value;
                          break;
                      case 3:
                      $carrier = $value;
                      echo ("<p>" . $value . "</p>");
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
                          break;
					           case 2:
                          $id = $value;
                          break;
                      case 6:
                          $long = $value;
                          $end = "map$beginning&latitude=$lat&longitude=$long&zoom=18&showTowerLabels=false";
                          if (empty($id)) {
                            echo ("<a target=_blank href=https://www.cellmapper.net/$end>CellMapper</a>");
                            echo "<br>";
                          } else {
                            echo ("<a target=_blank href=https://www.cellmapper.net/$end>" . $id . "</a>");
                            echo "<br>";
                          }
						  break;
                      case 7:
                      $firstseen = $value;
                      case 9:
                      $city = $value;
                          break;
                      case 10:
                      $zip = $value;
                          break;
                      case 11:
                      $state = $value;
                          break;
                      case 12:
                      $address = $value;
                          echo nl2br('<a target="_blank" href="https://maps.google.com/maps?f=q&source=s_q&hl=en&q=' .$lat . ',' .$long . '">' . $address . ' <br>' . $city . ', ' . $state . ' ' . $zip . '</a>');
                          echo nl2br('<td><a href="Delete.php?findlaterID='.$row_id.'">❌</a></td>');
                          break;
                      case 14:
                          break;
                    default:
                    if (empty($value)) { } else {
                        echo ("<p>" . $value . "</p>");
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
