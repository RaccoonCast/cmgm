<?php
if (isset($_POST['url'])) {
$url = $_POST['url'];
$disable_UI = "true";
$randomChar = substr(str_shuffle(md5(time())),0,5);
$filename = $randomChar . ".php";

$myfile = fopen($filename, "w") or die("Unable to open file!");
$txt = '<meta http-equiv="refresh" content="0; URL=' . $url . '" />';
fwrite($myfile, $txt);
fclose($myfile);
echo '<a href="https://cmgm.gq/u/' . $filename . '">https://cmgm.gq/u/' . $filename . '';
}

if (!isset($disable_UI)) {
  ?>
<form action="index.php" method="post" autocomplete="off">
   <p>Enter URL</p>
    <input type="text" name="url">
    <input type="submit" value="Submit">
</form> <?php
}
?>
