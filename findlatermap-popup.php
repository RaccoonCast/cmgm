<!doctype html>
<html lang="en">
<head>
  <?php
  function isMobile() {
      return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
  }

  if(isMobile()){
    echo '<link rel="stylesheet" href="styles/findlatermap-popup/mobile.css">';
  } else {
    echo '<link rel="stylesheet" href="styles/findlatermap-popup/desktop.css">';
  }
   ?>
</head>
<body class="body">
<?php
echo "loading";
$servername = 'localhost';
$username = 'root';
$password = 'p50846';
$dbname = 'cmgm';
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
                      case 4:
                          $lat = $value;
                          break;
					  case 1:
                          $id = $value;
						  $end = "map?latitude=$latitude&longitude=$longitude&zoom=18&showTowerLabels=false";
						  echo ("<a target=_blank href=http://www.cellmapper.net/$end>" . $value . "</a>");
                          break;
                      case 5:
                          $long = $value;
                          $url = "hub.php?lat=$lat&long=$long";
                          echo '<a href="'.$url.'" target="_blank"></a>';
						  break;
                      case 6:
                      $firstseen = $value;
                      case 8:
                      $city = $value;
                          break;
                      case 9:
                      $zip = $value;
                          break;
                      case 10:
                      $state = $value;
                          break;
                      case 11:
                      $address = $value;
                          echo nl2br('<a target="_blank" href="https://maps.google.com/maps?f=q&source=s_q&hl=en&q=' .$lat . ',' .$long . '">' . $address . ' <br>' . $city . ', ' . $state . ' ' . $zip . '</a>');
                          break;
                      case 13:
                          break;
                    default:
                        echo ("<p>" . $value . "</p>");
                        break;
                  }
            }

    }
?>
</tbody>
</table>
</body>
</html>
