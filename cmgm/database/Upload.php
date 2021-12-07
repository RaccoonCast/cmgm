<!doctype html>
<html lang="en-us">
   <head>
     <script src="https://code.jquery.com/jquery-latest.min.js"></script>
     <script src="../js/pasteimages.js"></script>
     <script src="../js/copyToClipboard.js"></script>
     <?php include '../functions.php';?>
   </head>
   <body>
     <?php
     @$type = $_POST['type'];

     if (isset($_POST['base64_file'])) {
       if ($debug_flag != "0") echo "Ctrl+V upload attempting... <br>";
       include "includes/upload/ctrlv.php";
     } elseif(isset($_POST['type'])) {
       if ($debug_flag != "0") echo "File select upload attempting... <br>";
       include "includes/upload/file-select.php";
     }
      ?>
     <div class="body">
          <form action="Upload.php" name="image_upload" method="post" enctype="multipart/form-data">
            <input type="hidden" name="file" id="base64_file_form" />
              <div id="picture" ></div>
              Select file <a class="hiddenlink" href="LFMF.php">to</a> upload (Max: 10MB)<br><br>
              <select name="type">
                <option value="image-evidence" <?php if (@$_GET['type'] == "image-evidence") echo "selected"; ?>>Image evidence</option>
                <option value="image" <?php if (@$_GET['type'] == "image") echo "selected"; ?>>Image of cell site</option>
                <option value="photo" <?php if (@$_GET['type'] == "photo") echo "selected"; ?>>High Quality Image of cell site</option>
                <option value="misc" <?php if (@$_GET['type'] == "misc") echo "selected"; ?>>Miscellaneous</option>
              </select>
              <input type="file" name="fileToUpload" onchange="form.submit()" id="fileToUpload">
              <?php if (isset($_GET['finishedFilename'])) { ?>
                <br><br> It has been uploaded as <a onclick="copyToClipboard('<?php echo $_GET['finishedFilename']; ?>')" href="#"><?php echo $_GET['finishedFilename']; ?></a>, click to copy.
                <br><br> It has been uploaded as <a target="_blank" onclick="copyToClipboard('<?php echo $_GET['finishedFilename']; ?>')" href="uploads/<?php echo $_GET['finishedFilename']; ?>"><?php echo $_GET['finishedFilename']; ?></a>, click to view in a new tab.
              <?php } ?>

      <?php
      // Get footer
      include  "includes/footer.php";
      ?>
    </form>
   </body>
</html>
