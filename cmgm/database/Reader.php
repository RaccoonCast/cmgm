<?php
$id = $_GET['id'];
include "../includes/functions/sqlpw.php";
include "../includes/functions/calculateEV.php";
if ($_GET['show_empty_fields'] == 'true') {
  $show_empty_fields = "true";
} else {
  $show_empty_fields = "false";
}
if (isset($_GET['back_url'])) {
  $back = $_GET['back_url'];
} else {
  $back = "Home";
}
if ($back == "Map-popup") $back_url = "Map-popup.php?id=" . $id;
if ($back == "DB") $back_url = "DB.php#" . $id ;
if ($back == "Home") $back_url = "\Home.php";


$sql = "SELECT * FROM database_db WHERE id = $id;";
$result = mysqli_query($conn,$sql);

while($row = $result->fetch_assoc()) {
    foreach ($row as $key => $value) {

        if (!empty($value) OR $show_empty_fields == 'true') {
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
<a href="<?php echo $back_url ?>">Back</a><br>
<?php if ($show_empty_fields == 'false') { ?>
  <a href="Reader.php?id=<?php echo $id?>&back_url=<?php echo $back?>&show_empty_fields=true">Show empty fields</a>
<?php } else {
  ?>
    <a href="Reader.php?id=<?php echo $id?>&back_url=<?php echo $back?>&show_empty_fields=false">Don't show empty fields</a>
  <?php
} ?>
