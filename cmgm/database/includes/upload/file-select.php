<?php
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$fileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

if ($fileType == "jpg" OR $fileType == "png") { $type = "image"; } else { $type = "misc"; }
$filename = $type . "-" . substr(str_shuffle(md5(time())),0,25);
$filePath = "$target_dir" . "$filename.$fileType";

// Check file size
if ($_FILES["fileToUpload"]["size"] > 10000000) {
  echo "Sorry, your file is too large.";
  die();
}

$temp = explode(".", $_FILES["fileToUpload"]["name"]);
$newfilename = round(microtime(true)) . '.' . end($temp);
if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $filePath)) {
$finishedFilename = $filename . "." . $fileType;
redir ("Upload.php?finishedFilename=$finishedFilename&type=$type",0);
}
?>
