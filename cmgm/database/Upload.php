<!DOCTYPE html>
<html lang="en">
   <head>
     <script src="https://code.jquery.com/jquery-latest.min.js"></script>
     <script src="../js/pasteimages.js"></script>
      <?php
      include "../functions.php";
      if (isset($_POST['type'])) $filenameType = $_POST['type'];

      if ($siteroot == "/home/spane2003/cmgm.gq") {
        define('UPLOAD_DIR', $siteroot . '/database/uploads/');
      } else {
        define('UPLOAD_DIR', $siteroot . '\database\uploads\\');
      }

      if (isset($_POST['base64_file'])) {
        include "includes/upload/ctrlv.php";
      } elseif (isset($_POST['type'])) {
        include "includes/upload/file-select.php";
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
        <?php if (isset($finishedFilename)) { ?>
          <p onclick="copyToClipboard('<?php echo $finishedFilename; ?>')">It has been uploaded as <?php echo $finishedFilename; ?>, click to copy.</p>
        <?php } ?>

        <!-- Misc -->

        <br><br><br><br><br><br><br><p>File name examples </p><hr>
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
