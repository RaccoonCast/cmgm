<?php
function redir($page,$time) {
echo '<meta http-equiv="Refresh" content="' . $time . '; url=' . $page . '"';
}
?>
