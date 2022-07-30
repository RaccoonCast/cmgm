<?php
$type = $_POST['type'];
$siteroot = $_SERVER['DOCUMENT_ROOT'];
if ($siteroot == "/home/spane2003/" . $domain) {
  define('UPLOAD_DIR', $siteroot . '/database/uploads/');
} else {
  define('UPLOAD_DIR', $siteroot . '\database\uploads\\');
}
$img = $_POST['base64_file'];
$img = str_replace('data:image/png;base64,', '', $img);
$img = str_replace(' ', '+', $img);
$data = base64_decode($img);
$randomString = substr(str_shuffle(md5(time())),0,25);
$file = UPLOAD_DIR . $type . "-" . $randomString;
$success = file_put_contents($file, $data);

$image = imagecreatefrompng($file);
$bg = imagecreatetruecolor(imagesx($image), imagesy($image));
imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));
imagealphablending($bg, TRUE);
imagecopy($bg, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
imagedestroy($image);
imagejpeg($bg, $file . ".jpg", "80");
imagedestroy($bg);
unlink($file);
$evidence_a = $type . "-" . $randomString . '.jpg';
$finishedFilename = $evidence_a;
redir ("Upload.php?finishedFilename=$finishedFilename&type=$type",0);
 ?>
