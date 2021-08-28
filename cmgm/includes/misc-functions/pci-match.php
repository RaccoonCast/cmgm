<?php
if (!empty($_POST['data'])) {
  $cellmapper_data = $_POST['data'];
  $cellmapper_data = substr($cellmapper_data, strpos($cellmapper_data, "eNB ") + 4);
  $cellmapper_data = str_replace("Click a tower above in order to centre on it", "", $cellmapper_data);
  $cellmapper_data = str_replace("eNB", "", $cellmapper_data);
  $cellmapper_data = preg_replace('#\s+#',', ',trim($cellmapper_data));
  $cellmapper_dataArr = explode(', ' , $cellmapper_data);

  include "../../database/includes/DB-filter.php";
  include '../functions/sqlpw.php';

  $sql = "SELECT LTE_1,LTE_2,LTE_3,LTE_4,LTE_5,LTE_6,NR_1,NR_2 FROM database_db $db_vars";
  $result = mysqli_query($conn, $sql);
  while ($row = mysqli_fetch_assoc($result)) foreach ($row as $field => $value) if (strlen($value) <= 7 && !empty($value)) $cmgm_data = @$cmgm_data . ", " . $value;
  $cmgm_data = substr($cmgm_data, 2);
  $cmgm_dataArr = explode(', ', $cmgm_data);
  $result = array_intersect($cellmapper_dataArr, $cmgm_dataArr);
  asort($result);
  foreach($result as $value) echo $value . "<br />\n";
} else {
// get latitude from URL
if (!empty($_GET['carrier'])) { $carrier = $_GET['carrier']; }
?>

 <form action="pci-match.php?carrier=<?php echo $carrier; ?>" method="post">
   <textarea name="data" rows="50" cols="150"></textarea>
   <br>
   <input type="submit">
 </form>
<?php } ?>
