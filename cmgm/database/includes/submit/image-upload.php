<?php
// Image upload
if (isset($_POST['base64_file'])) {
  $siteroot = $_SERVER['DOCUMENT_ROOT'];
  if ($siteroot == "/home/spane2003/cmgm.gq") {
    define('UPLOAD_DIR', $siteroot . '/database/uploads/');
  } else {
    define('UPLOAD_DIR', $siteroot . '\database\uploads\\');
  }
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
  $evidence_link = 'image-evidence-' . $randomString . '.jpg';
}
?>