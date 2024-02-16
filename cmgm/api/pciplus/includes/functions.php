<?php
//  cody and alps' purple iphones (CAAPI)
header("Access-Control-Allow-Origin: *");
header('Content-type: application/json');

header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
header('Pragma: no-cache');

function error($error,$id) {
  http_response_code(500);

  $a = array("error","value");
  $b = array($error,$id);
  $c = array_combine($a, $b);

  echo json_encode($c);
  die();
}
function error_handler($errno, $errstr, $errfile, $errline) {
    // your custom error handling logic here
    $id = isset($_GET['id']) ? $_GET['id'] : (isset($_POST['id']) ? $_POST['id'] : null);
    error($errstr." on line ".$errline,$id);
}
function report($msg,$id,$content) {
  $a = array("changed","id","content");
  $b = array($msg,$id,$content);
  $c = array_combine($a, $b);
  echo json_encode($c);

  http_response_code(200);
  die();
}
function respond($http_code, $msg) { 
  $arr_keys = array("msg");
  $arr_vals = array($msg);
  $combined = array_combine($arr_keys, $arr_vals);
  http_response_code($http_code);
  echo json_encode($combined);

  die();
}

@define ('SITE_ROOT', $_SERVER['DOCUMENT_ROOT']);
include '../../includes/functions/sqlpw.php'; // doesn't call native
?>
