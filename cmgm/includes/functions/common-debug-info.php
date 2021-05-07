<?php
if ($debug_flag == "high") {
  echo "<br>";
  echo basename(__FILE__) . ": " . "Included files: " . "<br>";
  $included_files = get_included_files();
  foreach ($included_files as $filename) {
    echo basename(__FILE__) . ": " . "$filename";
    echo "<br>";
}
}
?>
