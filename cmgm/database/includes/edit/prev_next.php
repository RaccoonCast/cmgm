<?php
echo '<div style="display: inline-block; float: right;"">';
if (!isset($delete) && !isset($_GET['new']) && !isset($_GET['lock_status']) && $padlock == "false" && isset($id)) include "../includes/widgets/widgets.php";
echo '</div>';
?>
<a href="?id=<?php echo $id-1; ?>&back=1">Prev</a>
<a href="?id=<?php echo $id+1; ?>&next=1">Next</a><span style="font-size: 0.85em;">(1)</span><br>

<a href="?id=<?php echo $id-10; ?>&back=1">Prev</a>
<a href="?id=<?php echo $id+10; ?>&next=1">Next</a><span style="font-size: 0.85em;">(10)</span><br>
<a href="?id=<?php echo $id-100; ?>&back=1">Prev</a>
<a href="?id=<?php echo $id+100; ?>&next=1">Next</a><span style="font-size: 0.85em;">(100)</span><br>
<a href="?id=<?php echo $id-1000; ?>&back=1">Prev</a>
<a href="?id=<?php echo $id+1000; ?>&next=1">Next</a><span style="font-size: 0.85em;">(1000)</span><br>
