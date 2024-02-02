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

    if (!isset($_GET['percents_view'])) {
      echo $carrier .  ': <a href="' . $current_url . '&carrier=' . $carrier . '">' . $count  . '</a><br>';
    } else {
      echo $carrier .  ': <a href="' . $current_url . '&carrier=' . $carrier . '">' . getPercent($count)  . '</a><br>';
    }
  }

  echo "<br>";
  ?>
