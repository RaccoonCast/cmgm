<!DOCTYPE html>
<html lang="en">
   <head>
      <?php
      include "../../functions.php";
      ?>
   </head>
   <body>
      <form action="submit.php" method="post" enctype="multipart/form-data">
        Select file to upload (Max: 25MB) <br><br>
        <select name="type" required>
          <option style="display:none" disabled selected="selected"></option>
          <option value="image-evidence">Image evidence</option>
          <option value="image">Image of cell site</option>
          <option value="photo">High Quality Image of cell site</option>
          <option value="misc">Miscellaneous</option>
        </select>
        <input type="file" name="fileToUpload" id="fileToUpload" required>
        <input type="submit" value="Upload Image" name="submit">
        <script> if ( window.history.replaceState ) { window.history.replaceState( null, null, window.location.href );}</script>
      </form>
   </body>
</html>
