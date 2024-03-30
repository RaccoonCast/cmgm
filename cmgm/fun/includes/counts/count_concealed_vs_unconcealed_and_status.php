<?php
if (!isset($_GET['concealed'])) {
	// Unverified
	$sql = 'SELECT
				SUM(CASE WHEN concealed = "true" THEN 1 ELSE 0 END) AS concealed_true_count,
				SUM(CASE WHEN concealed = "false" THEN 1 ELSE 0 END) AS concealed_false_count
			FROM db WHERE '.$db_vars.' ';
	
	$result = $conn->query($sql);
	
	// Output data
	while ($row = $result->fetch_assoc()) {
	
		if (isset($_GET['percents_view'])) {
    echo "<h3>Unconcealed Vs Concealed</h3>";
		echo "Unconcealed sites: " .  '<a href="' . $current_url . '&concealed=false">' . getPercent($row["concealed_false_count"])  . '</a><br>';
		echo "Concealed sites: " .  '<a href="' . $current_url . '&concealed=true">' . getPercent($row["concealed_true_count"])  . '</a><br><br>';
		} elseif (isset($json_flag)) {
			$json_array["unconcealed"] = $row["concealed_false_count"];
			$json_array["concealed"] = $row["concealed_true_count"];
		} else {
      echo "<h3>Unconcealed Vs Concealed</h3>";
			echo "Unconcealed sites: " .  '<a href="' . $current_url . '&concealed=false">' . $row["concealed_false_count"]  . '</a><br>';
			echo "Concealed sites: " .  '<a href="' . $current_url . '&concealed=true">' . $row["concealed_true_count"]  . '</a><br><br>';
		}
	}
}


// Verified Vs Unverified
if (!isset($_GET['status'])) {
  $sql = 'SELECT
  SUM(CASE WHEN status = "verified" THEN 1 ELSE 0 END) AS verified_true_count,
  SUM(CASE WHEN status = "unverified" THEN 1 ELSE 0 END) AS verified_false_count
  FROM db WHERE '.$db_vars.' ';

  $result = $conn->query($sql);

  // Output data
  while ($row = $result->fetch_assoc()) {

  if (isset($_GET['percents_view'])) {
    echo "<h3>Verified vs Unverified Sites</h3>";
    echo "Verified sites: " .  '<a href="' . $current_url . '&status=verified">' . getPercent($row["verified_true_count"])  . '</a><br>';
    echo "Unverified sites: " .  '<a href="' . $current_url . '&status=unverified">' . getPercent($row["verified_false_count"])  . '</a><br>';
  } elseif (isset($json_flag)) {
    $json_array["verified"] = $row["verified_true_count"];
    $json_array["unverified"] = $row["verified_false_count"];
  } else {
    echo "<h3>Verified vs Unverified Sites</h3>";
    echo "Verified sites: " .  '<a href="' . $current_url . '&status=verified">' . $row["verified_true_count"]  . '</a><br>';
    echo "Unverified sites: " .  '<a href="' . $current_url . '&status=unverified">' . $row["verified_false_count"]  . '</a><br>';
  }
  }
}


echo (isset($_GET['json_flag'])) ? "<br>" : "";
?>
