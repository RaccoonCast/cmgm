<!DOCTYPE html>
<head>
<?php
include "../functions.php";
$missing = "";
?>
</head>
<?php
$database_get_list = "evidence_a,evidence_b,evidence_c,photo_a,photo_b,photo_c,photo_d,photo_e,photo_f,attached_a,attached_b,attached_c";

// todo:// add edit_history, edit_lock(IPs, name?)
$list = null;
$counter=0;
$sql = "SELECT $database_get_list FROM database_db";
// echo $sql;
$result = mysqli_query($conn,$sql);

while($row = $result->fetch_assoc()) {
    foreach ($row as $key => $value)
    $list = $value . " " . $list;
}

// Let's Find Missing Files (LFMF) -- except it deletes the files that aren't in use.
//Get a list of file paths using the glob function.
$fileList = glob('uploads/*.*');

//Loop through the array that glob returned.
foreach($fileList as $filename){
  $output = str_replace("uploads/", "", $filename);
  if (!strpos($list, $output)) {
    unlink('uploads/' . $output . '');
  }
}
?>
</body>
</html>
