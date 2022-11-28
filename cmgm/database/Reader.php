<?php
define ('SITE_ROOT', $_SERVER['DOCUMENT_ROOT']);
$SITE_ROOT = $_SERVER['DOCUMENT_ROOT'];
include "../includes/functions/sqlpw.php";
include '../includes/useridsys/native.php';
include "../includes/functions/calculateEV.php";
@$id = $_GET['id'];
@$id = $_GET['mp-id'];
@$show_empty_fields = $_GET['show_empty_fields'];
@$back = $_GET['back_url'];
if ($back == "/database/Edit.php") $back_url = "Edit.php?id=" . $id;
if ($back == "/database/Map-popup.php") $back_url = "Map-popup.php?mp-id=" . $id . $url_suffix;
if ($back == "/database/DB.php") $back_url = "DB.php#" . $id ;
if ($back == "Home" OR !isset($back)) $back_url = "\Home.php";

$sql = "SELECT * FROM db WHERE id = $id;";
$result = mysqli_query($conn,$sql);

while($row = $result->fetch_assoc()) {
    foreach ($row as $key => $value) {

        if (!empty($value) OR $show_empty_fields == 'true') {
          if ($key != "evidence_score" & $key != "edit_userid") {
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
<a href="<?php echo $back_url ?>">Back</a><br>
<?php if ($show_empty_fields == 'false' OR !isset($show_empty_fields)) { ?>
<a href="Reader.php?mp-id=<?php echo $id?>&back_url=<?php echo $back?>&show_empty_fields=true">Show empty fields</a><?php } else { ?>
<a href="Reader.php?mp-id=<?php echo $id?>&back_url=<?php echo $back?>&show_empty_fields=false">Don't show empty fields</a><?php } ?>
