<?php
$id = $_GET['id'];
include "../includes/functions/sqlpw.php";
include "../includes/functions/calculateEV.php";
$back_url = $_GET['back_url'];
if ($back_url == "Map-popup") $back_url = "Map-popup.php?id=" . $id;
if ($back_url == "DB") $back_url = "DB.php#" . $id ;


$sql = "SELECT * FROM database_db WHERE id = $id;";
$result = mysqli_query($conn,$sql);

while($row = $result->fetch_assoc()) {
    foreach ($row as $key => $value) {
        if (!empty($value)) {
          if ($key != "evidence_score") {
          echo "$key" . ": " . $value;
          echo "<br>";
          }
        }
        $$key = $value;
        }
      }


$recalcEV = calculateEV($LTE_1,$carrier);
if (!empty($recalcEV)) {
if (!empty($evidence_score)) {
echo "old_evidence_score: " . $evidence_score . "";
echo "<br>";
}
echo "new_evidence_score: " . $recalcEV . "";
}
?>
<br>
<br>
<a href="<?php echo $back_url ?>">Back</a>
