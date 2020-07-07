<form action="database-form.php?latitude=<?php echo $latitude?>&longitude=<?php echo $longitude?>&carrier=<?php if (isset($carrier)) echo $carrier?>" name="image_upload" method="post">
<input type="hidden" name="file" id="base64_file_form" />
  <div id="picture" ></div>
</div>
</form>

<?php
// Image upload
if (isset($_POST['base64_file'])) {
    define('UPLOAD_DIR', 'uploads/');
  $img = $_POST['base64_file'];
  $img = str_replace('data:image/png;base64,', '', $img);
  $img = str_replace(' ', '+', $img);
  $data = base64_decode($img);
  $randomString = substr(str_shuffle(md5(time())),0,25);
  $file = UPLOAD_DIR . 'image-evidence-' . $randomString . '.png';
  $link = 'image-evidence-' . $randomString . '.png';
  $success = file_put_contents($file, $data);
}
?>
