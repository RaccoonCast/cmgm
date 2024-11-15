
<!doctype html>
<html lang="en-us">
   <head>
    <style>
       .hiddenlink,.hiddenlink:hover {
       text-decoration: none; cursor: text; color: #EFEFEF!important;
      }
    </style>
     <script src="https://code.jquery.com/jquery-latest.min.js"></script>
     <script src="../js/copyToClipboard.js"></script>
     <script src="../js/pasteimages.js"></script>
     <?php
     $allowGuests = "true"; 
     error_reporting(E_ERROR | E_PARSE);
     include '../functions.php';?>
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
          <form id="fileForm" name="image_upload" method="post" enctype="multipart/form-data">
          
            <input type="hidden" name="file" id="base64_file_form" />
              <div id="picture" ></div>
              Select a file, <a class="hiddenlink" href="https://alpha.cmgm.us/database/LFMF.php">or</a> paste an image on your clipboard (Max: 250MB)<br>
               <br>

              <br/>
              <!-- <br/> -->
              <div id="dropzone" class="dropzone"></div>
              <br/>
              <!-- <br/> -->

              <?php if (isset($_GET['finishedFilename'])) { ?>
                <br><br>Great! You can copy & paste <a onclick="copyToClipboard('<?php echo $_GET['finishedFilename']; ?>')" href="#"><?php echo $_GET['finishedFilename']; ?></a> into an attachments field on the edit page now.
                <br><br> You can also view the file first, <a target="_blank" onclick="copyToClipboard('<?php echo $_GET['finishedFilename']; ?>')" href="https://files.cmgm.us/<?php echo $_GET['finishedFilename']; ?>"><?php echo $_GET['finishedFilename']; ?></a>.
              <?php } ?>

      <?php
      // Get footer
      // include  "includes/footer.php";
      ?>
    </form>

    <!-- Include dropzone file -->
    <?php include "../js/drag-and-drop.js.php"; ?>

   </body>
</html>
