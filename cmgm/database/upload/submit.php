<?php
$target_dir = "../uploads/";
$filenameType = $_POST['type'];
if ($filenameType == "image-evidence") {
  $filenameSuffix = "image-evidence-";
} elseif ($filenameType == "image") {
  $filenameSuffix = "image-";
} elseif ($filenameType == "photo") {
  $filenameSuffix = "photo-";
} elseif ($filenameType == "misc") {
  $filenameSuffix = "misc-";
}
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$fileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

$filename = $filenameSuffix . substr(str_shuffle(md5(time())),0,25);
$filename = "$filename.$fileType";
echo $filename;
die();

// Check if file already exists
if (file_exists($target_file)) {
  echo "Sorry, file already exists.";
  $uploadOk = 0;
}

// Check file size
if ($_FILES["fileToUpload"]["size"] > 25000000) {
  echo "Sorry, your file is too large.";
  $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 1) {
  $temp = explode(".", $_FILES["file"]["name"]);
$newfilename = round(microtime(true)) . '.' . end($temp);
  if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file . $filename)) {
    echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
  } else {
    echo "Sorry, there was an error uploading your file.";
  }
}
?>
