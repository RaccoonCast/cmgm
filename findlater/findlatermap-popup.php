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
$sql = "SELECT * FROM findlater WHERE row_id = $row_id;";
$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {
  // columnCount
  $colCount = 1;
    foreach ($row as $field => $value) {
   // columnCount goes up
    $sepCount = ($colCount++);
                  switch ($sepCount) {
                    case 1: $row_id = $value; break;
                    case 2: $id = $value; break;
                    case 3: $carrier = $value; break;
                    case 4: $type = $value; break;
                    case 5: $lat = $value;  break;
                    case 6: $long = $value; break;
                    case 7: $firstseen = $value; break;
                    case 8: $bands = $value; break;
                    case 9: $city = $value; break;
                    case 10: $zip = $value; break;
                    case 11: $state = $value; break;
                    case 12: $address = $value;break;
                    case 13: $bio = $value;
                    echo nl2br("<p class=" . "widget" .">Findlater ID: " . $row_id . "   </p>");
                    echo nl2br('<a class="widget" href="Edit.php?row_id='.$row_id.'">🔧</a>');
                    echo nl2br('<a class="widget" href="Delete.php?row_id='.$row_id.'">❌</a>');
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
                      $end = "map$beginning&latitude=$lat&longitude=$long&zoom=18&showTowerLabels=false";
                      if (empty($id)) {
                        echo ("<a target=_blank href=https://www.cellmapper.net/$end>CellMapper</a>");
                        echo "<br>";
                      } else {
                        echo ("<a target=_blank href=https://www.cellmapper.net/$end>" . $id . "</a>");
                        echo "<br>";
                      }
                      echo nl2br('<a target="_blank" href="https://maps.google.com/maps?f=q&source=s_q&hl=en&q=' .$lat . ',' .$long . '">' . $address . ' <br>' . $city . ', ' . $state . ' ' . $zip . '</a>');

                      echo ("<p>" . $bio . "</p>");
                      break;

                  }
            }

    }
?>
</tbody>
</table>
</body>
</html>
