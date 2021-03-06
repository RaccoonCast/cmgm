<?php
$id = $_GET['id'];
include "../includes/functions/sqlpw.php";

$sql = "SELECT * FROM database_db WHERE id = $id;";
$result = mysqli_query($conn,$sql);

while($row = $result->fetch_assoc()) {
    foreach ($row as $key => $value) {
        if (!empty($value)) {
          echo "$key" . ": " . $value;
          echo "<br>";
        }
        $$key = $value;
        }
      }
?>
<a href="Map-popup.php?id=<?php echo $id; ?>">Back</a>
