<?php
if ($_SERVER['DOCUMENT_ROOT'] == "/home/spane2003/cmgm.us") {
  header('Location: https://upload.cmgm.us');
}
?>
<!doctype html>
<html lang="en-us">
   <head>
     <script src="https://code.jquery.com/jquery-latest.min.js"></script>
     <script src="../js/pasteimages.js"></script>
     <script src="../js/copyToClipboard.js"></script>
     <?php $allowGuests = "true"; error_reporting(E_ERROR | E_PARSE); include '../functions.php';?>
   </head>
   <body>
     <?php
     @$type = $_POST['type'];

     if (isset($_POST['base64_file'])) {
       if ($debug_flag != "0") echo "Ctrl+V upload attempting... <br>";
       include "includes/upload/ctrlv.php";
     } else {
       if ($debug_flag != "0") echo "File select upload attempting... <br>";
       include "includes/upload/file-select.php";
     }
      ?>
     <div class="body">
          <form name="image_upload" method="post" enctype="multipart/form-data">
            <input type="hidden" name="file" id="base64_file_form" />
              <div id="picture" ></div>
              Select file <a class="hiddenlink" href="https://cmgm.us/database/LFMF.php">to</a> upload (Max: 25MB)<br>
              You can also paste an image if you have one on your clipboard. <br><br>
              <input type="file" name="fileToUpload" onchange="form.submit()" id="fileToUpload">
              <?php if (isset($_GET['finishedFilename'])) { ?>
                <br><br>Great! You can copy & paste <a onclick="copyToClipboard('<?php echo $_GET['finishedFilename']; ?>')" href="#"><?php echo $_GET['finishedFilename']; ?></a> into an attachments field on the edit page now.
                <br><br> You can also view the file first, <a target="_blank" onclick="copyToClipboard('<?php echo $_GET['finishedFilename']; ?>')" href="uploads/<?php echo $_GET['finishedFilename']; ?>"><?php echo $_GET['finishedFilename']; ?></a>.
              <?php } ?>

      <?php
      // Get footer
      // include  "includes/footer.php";
      ?>
    </form>
   </body>
</html>
