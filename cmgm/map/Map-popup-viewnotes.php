<?php 
$allowGuests = true;
include __DIR__ . '/../functions.php'; ?>
<!doctype html>
<html lang="en">
<head>
  <?php
 include __DIR__ . "/../includes/functions/headhtml.php";
  $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
  ?>
</head>
<body class="body">
<?php
$database_get_list = "notes";

$sql = "SELECT $database_get_list FROM db WHERE id = $id;";
$result = mysqli_query($conn, $sql);

$sql_read_result = mysqli_query($conn,$sql);
while($row = $sql_read_result->fetch_assoc()) foreach ($row as $key => $value) $$key = $value;
                    if (empty($notes)) $notes = "No notes.";
                    echo nl2br($notes);
                    ?>
                    <br><br>
<button onclick="history.back()">Back</button>
</body>
</html>

