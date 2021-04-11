<!DOCTYPE html>
<html lang="en">
   <head>
      <?php
      include "../functions.php";

      if (isset($_POST['type'])) {
          $filenameType = $_POST['type'];
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
