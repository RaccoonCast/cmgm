<!DOCTYPE html>
<html lang="en">
   <head>
     <script src="https://code.jquery.com/jquery-latest.min.js"></script>
     <script src="../js/pasteimages.js"></script>
      <?php
      if (isset($_POST['type'])) $filenameType = $_POST['type'];
      include "../functions.php";
      if (isset($_POST['base64_file'])) {
        $siteroot = $_SERVER['DOCUMENT_ROOT'];
        if ($siteroot == "/home/spane2003/cmgm.gq") {
          define('UPLOAD_DIR', $siteroot . '/database/uploads/');
        } else {
          define('UPLOAD_DIR', $siteroot . '\database\uploads\\');
        }
        $img = $_POST['base64_file'];
        $img = str_replace('data:image/png;base64,', '', $img);
        $img = str_replace(' ', '+', $img);
        $data = base64_decode($img);
        $randomString = substr(str_shuffle(md5(time())),0,25);
        $file = UPLOAD_DIR . $filenameType . '-' . $randomString . '.png';
        $fileNoExtension = UPLOAD_DIR . $filenameType . '-' . $randomString;
        $success = file_put_contents($file, $data);

        $filePath = $file;
        $image = imagecreatefrompng($filePath);
        $bg = imagecreatetruecolor(imagesx($image), imagesy($image));
        imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));
        imagealphablending($bg, TRUE);
        imagecopy($bg, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
        imagedestroy($image);
        $quality = 80; // 0 = worst / smaller file, 100 = better / bigger file
        imagejpeg($bg, $fileNoExtension . ".jpg", $quality);
        imagedestroy($bg);
        unlink($file);
        $evidence_a = $filenameType . '-' . $randomString . '.jpg';
        echo $evidence_a;
      } elseif (isset($_POST['type'])) {
          $target_dir = "uploads/";
          $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
          $uploadOk = 1;
          $fileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

          $filename = $filenameType . "-" . substr(str_shuffle(md5(time())),0,25);
          $filePath = "$target_dir" . "$filename.$fileType";

          // Check if file already exists
          if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
          }

          // Check file size
          if ($_FILES["fileToUpload"]["size"] > 10000000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
          }

          // Check if $uploadOk is set to 0 by an error
          if ($uploadOk == 1) {
            $temp = explode(".", $_FILES["fileToUpload"]["name"]);
            $newfilename = round(microtime(true)) . '.' . end($temp);
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $filePath)) {
              ?>
              <p onclick="copyToClipboard('<?php echo $filename; ?>.<?php echo $fileType; ?>')">It has been uploaded as <?php echo $filename?>.<?php echo $fileType?>, click me to copy.</p>
              <?php
              echo "<br>";
            } else {
              echo "Sorry, there was an error uploading your file.";
            }
          }
        }
      ?>
   </head>
   <body>
      <form action="Upload.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="file" id="base64_file_form" />
          <div id="picture" ></div>
        Select file to upload (Max: 10MB)<br><br>
        <select name="type" required>
          <option style="display:none" disabled selected="selected"></option>
          <option value="image-evidence">Image evidence</option>
          <option value="image">Image of cell site</option>
          <option value="photo">High Quality Image of cell site</option>
          <option value="misc">Miscellaneous</option>
        </select>
        <input type="file" name="fileToUpload" id="fileToUpload" required>
        <input type="submit" value="Upload Image" name="submit">
        <br><br><br><p>File name examples </p><hr>
        <?php
        $randomString = $randomString = substr(str_shuffle(md5(time())),0,25);
        echo "image-evidence-" . $randomString . ".pdf";
        ?> <br><br> <?php
        $randomString = $randomString = substr(str_shuffle(md5(time())),0,25);
        echo "image-" . $randomString . ".jpg";
        ?>
        <script> if ( window.history.replaceState ) { window.history.replaceState( null, null, window.location.href );}</script>
      </form>
      <?php include "includes/footer.php"; ?>
   </body>
</html>
