<html>
  <head>
    <script src="https://code.jquery.com/jquery-latest.min.js"></script>
    <script src="script.js"></script>
  </head>
   <body>
      <form action="index.php" name="image_upload" method="post">
      <input type="hidden" name="file" id="base64_file_form" />
        <div id="picture" ></div>
      </div>
    </form>
  </body>

  <style>
    #picture {
      background-size: contain;
      background-repeat: no-repeat;
      width: 40%;
      height: 40%;
      position:absolute;
      top:0;
      right:0;
    }
  </style>
</html>
<?php
$row_id = 5;
if (isset($_POST['base64_file'])) {
    define('UPLOAD_DIR', 'uploads/');
	$img = $_POST['base64_file'];
	$img = str_replace('data:image/png;base64,', '', $img);
	$img = str_replace(' ', '+', $img);
	$data = base64_decode($img);
	$file = UPLOAD_DIR . $row_id . '.png';
	$success = file_put_contents($file, $data);
}
?>