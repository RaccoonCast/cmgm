<?php
$db_map_link = "Map.php?latitude=" . $latitude . "&longitude=" . $longitude . "&zoom=18&carrier=" . @$carrier . "&back=Edit.php?id=" . $id;
echo '<a class="widget" title="View all info" href="Reader.php?back_url=Edit&mp-id='.$id.'">🔍</a>';
echo '<a class="widget" title="Delete" href="Delete.php?id='.$id.'">✂️</a>';
echo '<a class="widget" title="View on Database Map" class="widget" href="' . $db_map_link . '">🌎</a>';
if (!empty($street_view_url_a)) echo '<a class="widget" target="_blank" title="Street View URL" class="widget" href="' . $street_view_url_a . '">🚗</a>';
echo '<a class="widget" title="Edit" class="widget" href="Edit.php?id='.$id.'">🔧</a>';
?>
