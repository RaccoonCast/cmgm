<?php
if (file_exists("id_list.txt")) {
  unlink("id_list.txt");
}
include '../functions/sqlpw.php';
include '../functions/basic-functions.php';
include '../useridsys/native.php';

$sql = "SELECT LTE_1,LTE_2,LTE_3,LTE_4,LTE_5,LTE_6,NR_1,NR_2 FROM database_db;";
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($result)) {

 $colCount = 1;
   foreach ($row as $field => $value) {
     $sepCount = ($colCount++);

switch ($sepCount) {
 case 1:  $LTE_1 = $value; break;
 case 2:  $LTE_2 = $value; break;
 case 3:  $LTE_3 = $value; break;
 case 4:  $LTE_4 = $value; break;
 case 5:  $LTE_5 = $value; break;
 case 6:  $LTE_6 = $value; break;
 case 7:  $NR_1 = $value; break;
 case 8:  $NR_2 = $value;

if (!empty($LTE_1)) file_put_contents('id_list.txt', $LTE_1."\n",FILE_APPEND);
if (!empty($LTE_2)) file_put_contents('id_list.txt', $LTE_2."\n",FILE_APPEND);
if (!empty($LTE_3)) file_put_contents('id_list.txt', $LTE_3."\n",FILE_APPEND);
if (!empty($LTE_4)) file_put_contents('id_list.txt', $LTE_4."\n",FILE_APPEND);
if (!empty($LTE_5)) file_put_contents('id_list.txt', $LTE_5."\n",FILE_APPEND);
if (!empty($LTE_6)) file_put_contents('id_list.txt', $LTE_6."\n",FILE_APPEND);
if (!empty($NR_1)) file_put_contents('id_list.txt', $NR_1."\n",FILE_APPEND);
if (!empty($NR_2)) file_put_contents('id_list.txt', $NR_2."\n",FILE_APPEND);
redir("id_list.txt","0"); break;
           }
   }
}
?>
