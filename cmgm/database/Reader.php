<?php
define ('SITE_ROOT', $_SERVER['DOCUMENT_ROOT']);
$SITE_ROOT = $_SERVER['DOCUMENT_ROOT'];
$allowGuests = "true";
include "../includes/functions/sqlpw.php";
include '../includes/useridsys/native.php';
include "../includes/functions/calculateEV.php";
@$id = preg_replace("/[^0-9]/", '', $_GET['id']);
@$show_empty_fields = $_GET['show_empty_fields'];

$sql = "SELECT * FROM db WHERE id = $id;";
$result = mysqli_query($conn,$sql);

while($row = $result->fetch_assoc()) {
    foreach ($row as $key => $value) {

        if (!empty($value) OR $show_empty_fields == 'true') {
          if ($key != "evidence_score" && $key != "edit_userid" && $key != "edit_lock") {
          echo "$key" . ": " . $value;
          echo "<br>";
          }
        }
        $$key = $value;
        }
      }

$recalcEV = calculateEV($id,$carrier);
if (!empty($recalcEV)) {
if (!empty($evidence_score)) {
echo "old_evidence_score: " . $evidence_score . "";
echo "<br>";
}
echo "new_evidence_score: " . $recalcEV . "";
}
?>
<br><br>
<a href="javascript:history.back()">Back</a><br>
<?php if ($show_empty_fields == 'false' OR !isset($show_empty_fields)) { ?>
<a href="Reader.php?id=<?php echo $id?>show_empty_fields=true">Show empty fields</a><?php } else { ?>
<a href="Reader.php?id=<?php echo $id?>&show_empty_fields=false">Don't show empty fields</a><?php } ?>
