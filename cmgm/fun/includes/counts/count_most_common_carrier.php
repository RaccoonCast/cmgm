<?php
$carriers = ["T-Mobile", "Sprint", "Verizon", "ATT", "Dish"];
$carrierCounts = [];
  foreach ($carriers as $carrier) {
    $sql = "SELECT COUNT(*) as count FROM db WHERE $db_vars AND carrier = '$carrier'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $carrierCounts[$carrier] = $row['count'];
    $result->close();
  }
  foreach ($carrierCounts as $carrier => $count) {

    if (isset($_GET['percents_view'])) {
      echo $carrier .  ': <a href="' . $current_url . '&carrier=' . $carrier . '">' . getPercent($count)  . '</a><br>';
    } elseif (isset($json_flag)) {
      $json_array[$carrier] = $count;
    } else {
      echo $carrier .  ': <a href="' . $current_url . '&carrier=' . $carrier . '">' . $count  . '</a><br>';
    }
  }

  echo (isset($_GET['json_flag'])) ? "<br>" : "";
  ?>
