<!DOCTYPE html>
<head>
<?php include "../functions.php"; ?>
</head>
<?php if (!isset($_POST['opt'])) { ?>
<form action="LFMF.php" method="post" autocomplete="off">
<input type="submit" style="width: 145px;" name="opt" value="Missing File Search">
<input type="submit" style="width: 145px;" name="opt" value="Remove unused files">
<input type="submit" style="width: 145px;" name="opt" value="Show unused DB IDs">
<input type="submit" style="width: 145px;" name="opt" value="Show uploads filesize">
<input type="submit" style="width: 145px;" name="opt" value="PCI matcher">
</form>
<?php
die();
}
if ($_POST['opt'] == 'Missing File Search'){
// Let's Find Missing Files (LFMF)
echo "The following IDs have missing EV: <br>";
$sql = "SELECT id,evidence_a,evidence_b,evidence_c,extra_a,extra_b,extra_c,photo_a,photo_b,photo_c,photo_d,photo_e,photo_f FROM database_db";
$result = mysqli_query($conn,$sql);

while($row = $result->fetch_assoc()) {
    foreach ($row as $key => $value) {
        $$key = $value;

        if (!empty($evidence_a) && substr($evidence_a, 0, 4) != "http" && !file_exists("uploads/" . $evidence_a)) echo " $id - EVIDENCE_A<br>";
        if (!empty($evidence_b) && substr($evidence_b, 0, 4) != "http" && !file_exists("uploads/" . $evidence_b)) echo " $id - EVIDENCE_B<br>";
        if (!empty($evidence_c) && substr($evidence_c, 0, 4) != "http" && !file_exists("uploads/" . $evidence_c)) echo " $id - EVIDENCE_C<br>";

        if (!empty($photo_a) && substr($photo_a, 0, 4) != "http" && !file_exists("uploads/" . $photo_a)) echo " $id - PHOTO_A<br>";
        if (!empty($photo_b) && substr($photo_b, 0, 4) != "http" && !file_exists("uploads/" . $photo_b)) echo " $id - PHOTO_B<br>";
        if (!empty($photo_c) && substr($photo_c, 0, 4) != "http" && !file_exists("uploads/" . $photo_c)) echo " $id - PHOTO_C<br>";
        if (!empty($photo_d) && substr($photo_d, 0, 4) != "http" && !file_exists("uploads/" . $photo_d)) echo " $id - PHOTO_D<br>";
        if (!empty($photo_e) && substr($photo_e, 0, 4) != "http" && !file_exists("uploads/" . $photo_e)) echo " $id - PHOTO_E<br>";
        if (!empty($photo_f) && substr($photo_f, 0, 4) != "http" && !file_exists("uploads/" . $photo_f)) echo " $id - PHOTO_F<br>";

        if (!empty($extra_a) && substr($extra_a, 0, 4) != "http" && !file_exists("uploads/" . $extra_a)) echo " $id - EXTRA_A<br>";
        if (!empty($extra_b) && substr($extra_b, 0, 4) != "http" && !file_exists("uploads/" . $extra_b)) echo " $id - EXTRA_B<br>";
        if (!empty($extra_c) && substr($extra_c, 0, 4) != "http" && !file_exists("uploads/" . $extra_c)) echo " $id - EXTRA_C<br>";
        if ($key != "id")$$key = null;
} }
$result->close(); $conn->close();
} elseif ($_POST['opt'] == 'Remove unused files') {
// Let's Find Missing Files (LFMF) -- except it deletes the files that aren't in use.
$list = null;
$sql = "SELECT evidence_a,evidence_b,evidence_c,photo_a,photo_b,photo_c,photo_d,photo_e,photo_f,extra_a,extra_b,extra_c FROM database_db";
$result = mysqli_query($conn,$sql);

while($row = $result->fetch_assoc()) {
    foreach ($row as $key => $value)
    $list = $value . " " . $list;
}

//Get a list of file paths using the glob function.
$fileList = glob('uploads/*.*');

echo "The following files have been deleted: <br>";
foreach($fileList as $filename){
  $output = str_replace("uploads/", "", $filename);

  // If X was not found in list remove it.
  if (!strpos($list, $output)) {
    unlink('uploads/' . $output . '');
    echo $output . "<br>";
  }
}
} elseif ($_POST['opt'] == 'Show unused DB IDs') {
  $sql = "SELECT t1.id + 1 FROM database_db t1 WHERE NOT EXISTS (SELECT * FROM database_db t2 WHERE t2.id = t1.id + 1)";
  $result = mysqli_query($conn,$sql);

  while($row = $result->fetch_assoc()) {
      foreach ($row as $key => $value) echo $value . "<br>";
  }
} elseif ($_POST['opt'] == 'Show uploads filesize') {
        $size=array_sum(array_map('filesize', glob("uploads/*.*")));
        function formatBytes($size, $precision = 2)
        {
        $base = log($size, 1000);
        $suffixes = array('', 'KB', 'MB', 'GG', 'TB');

        return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
        }

        echo "Upload folder filesize: " . formatBytes($size);
        // 23.81M
  } elseif ($_POST['opt'] == 'PCI matcher') {
        redir("..\includes\misc-functions\PCI-match.php?carrier=$default_carrier","0");
    }
?>
<br><br><form action="LFMF.php" method="post" autocomplete="off">
<input type="submit" style="width: 145px;" name="back" value="Back">
</form>
</body>
</html>
