<!doctype html>
<html lang="en-us">
   <head>
     <script src="https://code.jquery.com/jquery-latest.min.js"></script>
     <script src="../js/pasteimages.js"></script>
     <?php include '../functions.php';?>
   </head>
   <body>
     <?php
     @$type = $_POST['type'];
     
     if (isset($_POST['base64_file'])) {
       if ($debug_flag != "off") echo "Ctrl+V upload attempting...";
       include "includes/upload/ctrlv.php";
     } elseif(isset($_POST['type'])) {
       if ($debug_flag != "off") echo "File select upload attempting...";
       include "includes/upload/file-select.php";
     } else {
       if ($debug_flag != "off") echo "No file to upload has been found. (don't worry if you haven't tried yet.)";
     }
      ?>
     <div class="body">
          <form action="Upload.php" name="image_upload" method="post" enctype="multipart/form-data">
            <input type="hidden" name="file" id="base64_file_form" />
              <div id="picture" ></div><br>
              Select file to upload (Max: 10MB)<br><br>
              <select name="type">
                <option style="display:none" disabled selected="selected"></option>
                <option value="image-evidence">Image evidence</option>
                <option value="image">Image of cell site</option>
                <option value="photo">High Quality Image of cell site</option>
                <option value="misc">Miscellaneous</option>
              </select>
              <input type="file" name="fileToUpload" onchange="form.submit()" id="fileToUpload">
              <?php if (isset($finishedFilename)) { ?>
                <p onclick="copyToClipboard('<?php echo $finishedFilename; ?>')">It has been uploaded as <?php echo $finishedFilename; ?>, click to copy.</p>
              <?php } ?>

      <?php
      // Get footer
      include  "includes/footer.php";
      ?>
    </form>
   </body>
</html>
