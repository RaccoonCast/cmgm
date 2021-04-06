<?php
if(empty($zip)) $zip = null;
if(empty($address)) $address = null;
if(empty($city)) $city = null;
if(empty($state)) $state = null;
$url = "Form.php?latitude=$latitude&longitude=$longitude&address=$address&zip=$zip&city=$city&state=$state&permit_redirect=true";

 ?>
<form action="<?php $url?>" name="image_upload" method="post">
<input type="hidden" name="file" id="base64_file_form" />
  <div id="picture" ></div>
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
  $fileNoExtension = UPLOAD_DIR . 'image-evidence-' . $randomString;
  $link = 'image-evidence-' . $randomString . '.png';
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
  $link = 'image-evidence-' . $randomString . '.jpg';
}
?>
