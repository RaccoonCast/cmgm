<!DOCTYPE html>
<head>
<?php
include "../functions.php";
$missing = null;
$list = null;
?>
</head>
<?php
// Let's Find Missing Files (LFMF) -- except it deletes the files that aren't in use.
$sql = "SELECT evidence_a,evidence_b,evidence_c,photo_a,photo_b,photo_c,photo_d,photo_e,photo_f,attached_a,attached_b,attached_c FROM database_db";
$result = mysqli_query($conn,$sql);

while($row = $result->fetch_assoc()) {
    foreach ($row as $key => $value)
    $list = $value . " " . $list;
}

//Get a list of file paths using the glob function.
$fileList = glob('uploads/*.*');

foreach($fileList as $filename){
  $output = str_replace("uploads/", "", $filename);

  // If X was not found in list remove it.
  if (!strpos($list, $output)) unlink('uploads/' . $output . '');
}
?>
</body>
</html>
