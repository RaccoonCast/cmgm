<?php
$mcc = substr($_POST['carrier'], 0, 3);
$mnc = substr($_POST['carrier'], 3, 3);
$cid = $_POST['cid'];
$tac = $_POST['tac'];

// For default, assume it is in $PATH
$NODE_PATH = 'node';

// If on dreamhost, we need to use nvm
if ($_SERVER['SERVER_NAME'] == "cmgm.us") {
  $NODE_PATH = "/home/spane2003/.nvm/versions/node/v22.13.1/bin/node";
} else {
  $NODE_PATH = "node";
}

$command = "$NODE_PATH script-surro.js $mcc $mnc $cid $tac";

$response = shell_exec($command);
if (empty($response)) {
  http_response_code(500);
  echo "Empty Response";
} else {
  echo $response;
}
?>