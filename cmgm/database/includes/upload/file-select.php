<?php
if ($_FILES["fileToUpload"]["size"] > 25) {
  $target_dir = "uploads/";
  $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
  $fileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
  
  if ($fileType == "jpg" OR $fileType == "png" OR $fileType == "webp" OR $fileType == "jpeg") { $type = "image"; } else { $type = "misc"; }
  if ($fileType !== "jpg" OR $fileType !== "png" OR $fileType !== "webp" OR $fileType !== "jpeg" OR $fileType) { $type = "image"; } else { $type = "misc"; }
  $filename = $type . "-" . substr(str_shuffle(md5(time())),0,25);
  $filePath = "$target_dir" . "$filename.$fileType";
  
  // Check file size
  if ($_FILES["fileToUpload"]["size"] > 26214400) {
    echo "Sorry, your file is too large.";
    die();
  }
  
  $allowedFileTypes = ['png', 'jpg', 'pdf', 'webp', 'jpeg', 'json'];
  
  if (!in_array($fileType, $allowedFileTypes)) {
      echo "Invalid file type. Allowed types are: " . implode(', ', $allowedFileTypes);
      die();
  }
  
  
  $temp = explode(".", $_FILES["fileToUpload"]["name"]);
  $newfilename = round(microtime(true)) . '.' . end($temp);
  if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $filePath)) {
  $finishedFilename = $filename . "." . $fileType;
  redir ("?finishedFilename=$finishedFilename",0);
  }
}
?>
