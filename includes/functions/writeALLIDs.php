<?php
if (file_exists("id_list.txt")) {
  unlink("id_list.txt");
}
include 'sqlpw.php';
include 'redir.php';

$sql = "SELECT LTE_1,LTE_2,LTE_3,LTE_4,LTE_5 FROM database_db;";
$conn = mysqli_connect($servername, $username, $password, $dbname);
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
 case 5:  $LTE_5 = $value;

file_put_contents('id_list.txt', $LTE_1."\n",FILE_APPEND);
file_put_contents('id_list.txt', $LTE_2."\n",FILE_APPEND);
file_put_contents('id_list.txt', $LTE_3."\n",FILE_APPEND);
file_put_contents('id_list.txt', $LTE_4."\n",FILE_APPEND);
file_put_contents('id_list.txt', $LTE_5."\n",FILE_APPEND);

 break;
           }
   }
}
?>
