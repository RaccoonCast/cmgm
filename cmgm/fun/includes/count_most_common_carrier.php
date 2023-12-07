<?php
$carriers = ["T-Mobile", "Sprint", "Verizon", "ATT", "Dish"];
$carrierCounts = [];
  foreach ($carriers as $carrier) {
    $sql = "SELECT COUNT(*) as count FROM db WHERE $db_vars_unamended AND carrier = '$carrier'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $carrierCounts[$carrier] = $row['count'];
    $result->close();
  }
  foreach ($carrierCounts as $carrier => $count) {
    $url = $domain_with_http . removeParameterFromURL($current_url, "carrier");
    echo $carrier .  ': <a href="' . $url . '&carrier=' . $carrier . '">' . $count  . '</a><br>';
  }

  echo "<br>";
  ?>
