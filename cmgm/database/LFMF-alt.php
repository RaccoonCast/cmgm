<!DOCTYPE html>
<head>
<?php
include "../functions.php";
include "../sqlpw.php";
$missing = "";
?>
</head>
<?php
// Let's Find Missing Files (LFMF) -- except it deletes the files that aren't in use.
//Get a list of file paths using the glob function.
$fileList = glob('uploads/*.*');

//Loop through the array that glob returned.
foreach($fileList as $filename){
   $output = str_replace("uploads/", "", $filename);
   $check = @mysqli_fetch_array(mysqli_query($conn, "SELECT id FROM database_db WHERE evidence_a='$output' OR evidence_b='$output' OR photo_a='$output' OR photo_b='$output' OR photo_c='$output' OR photo_d='$output' OR photo_e='$output' OR photo_f='$output' OR attached_a='$output' OR attached_b='$output'"))['id'];
   if (!isset($check)) {
     // echo '<a href="uploads/' . $output . '">' . $output . '</a> does not exist in SQL DB<br>';
     unlink('uploads/' . $output . '');
   }
}
?>
</body>
</html>
