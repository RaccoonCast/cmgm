<!doctype html>
<html lang="en">
<head>
  <?php include '../functions.php';?>
</head>
<body>
<?php
$conn = mysqli_connect($servername, $username, $password, $dbname);

if (isset($_GET['debug'])) $debug = $_GET['debug'];

$row_id = $_GET['findlaterID'];

// THIS CODE ONLY GETS RUN AFTER CONFIRMATION

if (isset($_GET['delete_confirmed'])) {
  $sql = "DELETE FROM `cmgm`.`findlater` WHERE  `row_id`=" . $row_id . ";";
  mysqli_query($conn, $sql); // First parameter is just return of "mysqli_connect()" function
  header('Location: https://cmgm.gq/');
}

$sql = "SELECT * FROM findlater WHERE row_id = $row_id;";
$result = mysqli_query($conn, $sql);
?>
<form action="Delete.php" id="form1" method="get">
<input type="hidden" name="findlaterID" value="<?php echo $row_id?>">
<input type="hidden" name="delete_confirmed" value="true">
<p>Confirm deletion of: </P>
<table border="1">
<thead>
<tr>
    <th>eNB ID</th>
    <th>Carrier</th>
    <th>Type</th>
    <th>First Seen</th>
    <th>Band(s)</th>
    <th>Address</th>
    <th>Bio</th>
</tr>
</thead>
<tbody>
<?php
while ($row = mysqli_fetch_assoc($result)) { // Important line !!! Check summary get row on array ..
  $colCount = 1;
    echo "<tr>";
    foreach ($row as $field => $value) {

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
                    echo nl2br("<td>" . $id . "</td>");
                    echo nl2br("<td>" . $carrier . "</td>");
                    echo nl2br("<td>" . $type . "</td>");
                    echo nl2br("<td>" . $firstseen . "</td>");
                    echo nl2br("<td>" . $bands . "</td>");
                    echo nl2br('<td class="address"><a href="/Hub.php?latitude='.$lat.'&longitude='.$long.'">' . $address . ' <br>' . $city . ', ' . $state . ' ' . $zip . '</a></td>');
                    echo nl2br("<td>" . $bio . "</td>");
                      break;
                  }
            }
echo "</tr>";
/* edit example
UPDATE `cmgm`.`findlater` SET `type`='Rooftop' WHERE  `row_id`=2;
SELECT `row_id`, `id`, `carrier`, `type`, `latitude`, `longitude`, `firstseen`, `bands`, `city`, `zip`, `state`, `address`, `bio`
FROM `cmgm`.`findlater` WHERE  `row_id`=2;
*/
    }
?>
</tbody>
</table>
<br>
<button type="submit" form="form1" value="Submit">Submit</button>
</form>
</body>
</html>
