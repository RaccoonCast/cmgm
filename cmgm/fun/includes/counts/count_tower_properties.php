<?php

if (!isset($json_flag)) echo "<h3>Tower Properties</h3>";

// Verified Vs Unverified
if (!isset($_GET['status'])) {
  $sql = 'SELECT
  SUM(CASE WHEN status = "verified" THEN 1 ELSE 0 END) AS verified_true_count,
  SUM(CASE WHEN status = "unverified" THEN 1 ELSE 0 END) AS verified_false_count
  FROM db WHERE '.$db_vars.' ';

  $result = $conn->query($sql);

  if (!isset($json_flag)) echo "<b>Verification:</b><br>";

  // Output data
  while ($row = $result->fetch_assoc()) {

  if (isset($_GET['percents_view'])) {
    echo "Verified sites: " .  '<a href="' . $current_url . '&status=verified">' . getPercent($row["verified_true_count"])  . '</a><br>';
    echo "Unverified sites: " .  '<a href="' . $current_url . '&status=unverified">' . getPercent($row["verified_false_count"])  . '</a><br>';
  } elseif (isset($json_flag)) {
    $json_array["verified"] = $row["verified_true_count"];
    $json_array["unverified"] = $row["verified_false_count"];
  } else {
    echo "Verified sites: " .  '<a href="' . $current_url . '&status=verified">' . $row["verified_true_count"]  . '</a><br>';
    echo "Unverified sites: " .  '<a href="' . $current_url . '&status=unverified">' . $row["verified_false_count"]  . '</a><br>';
  }
  }
  echo (!isset($_GET['json_flag'])) ? "<br>" : "";
}

// Carrier
if (!isset($_GET['carrier'])) {
  $carriers = ["T-Mobile", "Sprint", "Verizon", "ATT", "Dish"];
  $carrierCounts = [];

  if (!isset($json_flag)) echo "<b>Carrier:</b><br>";
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

    echo (!isset($_GET['json_flag'])) ? "<br>" : "";
}

if (!isset($_GET['concealed'])) {
	// Unverified
	$sql = 'SELECT
				SUM(CASE WHEN concealed = "true" THEN 1 ELSE 0 END) AS concealed_true_count,
				SUM(CASE WHEN concealed = "false" THEN 1 ELSE 0 END) AS concealed_false_count
			FROM db WHERE '.$db_vars.' ';
	
	$result = $conn->query($sql);

  if (!isset($json_flag)) echo "<b>Visibility:</b><br>";

	// Output data
	while ($row = $result->fetch_assoc()) {
	
		if (isset($_GET['percents_view'])) {
    echo "Concealed sites: " .  '<a href="' . $current_url . '&concealed=true">' . getPercent($row["concealed_true_count"])  . '</a><br>';
		echo "Unconcealed sites: " .  '<a href="' . $current_url . '&concealed=false">' . getPercent($row["concealed_false_count"])  . '</a><br><br>';
		} elseif (isset($json_flag)) {
			$json_array["concealed"] = $row["concealed_true_count"];
			$json_array["unconcealed"] = $row["concealed_false_count"];
		} else {
			echo "Concealed sites: " .  '<a href="' . $current_url . '&concealed=true">' . $row["concealed_true_count"]  . '</a><br>';
			echo "Unconcealed sites: " .  '<a href="' . $current_url . '&concealed=false">' . $row["concealed_false_count"]  . '</a><br><br>';
		}
	}
}

if (!isset($_GET['cellsite_type'])) {
  $limitForCellsiteType = ($limit == 15) ? "20" : $limit;
  $sql = "SELECT cellsite_type, COUNT(cellsite_type) AS count FROM db WHERE ($db_vars) AND cellsite_type <> '' GROUP BY cellsite_type ORDER BY count DESC LIMIT $limitForCellsiteType;";

  $result = $conn->query($sql);

  if (!isset($json_flag)) echo "<b>Cellsite types</b><br>";

  while ($row = $result->fetch_assoc()) {

    include SITE_ROOT . "/includes/functions/tower_types.php";
    $category = ucfirst(explode('_', $row['cellsite_type'])[0]);
    $cellsite_type = $options[$category][$row['cellsite_type']];
    $cellsite_type_normalized = $cellsite_type . @$category_suffix;

    if (isset($_GET['percents_view'])) {
      $url = '<a href="' . $current_url . '&cellsite_type='.$row["cellsite_type"].'">'. getPercent($row["count"]).'</a>';
      echo $cellsite_type_normalized . ": " . $url . "<br>";
    } elseif (isset($json_flag)) {
      $json_array[$cellsite_type_normalized] = $row["count"];
    } else {
      $url = '<a href="' . $current_url . '&cellsite_type='.$row["cellsite_type"].'">'. $row["count"].'</a>';
      echo $cellsite_type_normalized . ": " . $url . "<br>";
    }

  }
}

echo (isset($_GET['json_flag'])) ? "<br>" : "";
?>
