<?php
$get_ip = $_SERVER["REMOTE_ADDR"];
$siteroot = $_SERVER['DOCUMENT_ROOT'];

if (isset($_POST['pass']) & isset($_POST['ip'])) {
  $the_password = file_get_contents($siteroot . "/secret-ip-whitelist-pass.hiddenpass", true);
  $password = $_POST['pass'];
  $add_ip = $_POST['ip'];
  if ($password == $the_password) {
    include '../functions/basic-functions.php';
    $sql = "INSERT INTO userID (`userIP`) VALUES ('".mysqli_real_escape_string($conn, $get_ip)."');  ";
      mysqli_query($conn, $sql);
      mysqli_close($conn);
      echo '<meta http-equiv="refresh" content="0;URL=/?refresh=true" /> ';
  } else {
    echo "BONG!";
    echo '<meta http-equiv="refresh" content="0;URL=../?refresh=true" /> ';
  }
} else {
?>
<form id="form" action="addIP.php" method="post" autocomplete="off">
<input type="textbox" name="pass" class="textbox"><br>
<input type="hidden" name="ip" value="<?php echo $get_ip?>" class="textbox"><br>
<input type="submit" value="Submit" /><br>
</form>
<?php }
