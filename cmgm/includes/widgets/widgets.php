<?php
$db_map_link = "Map.php?latitude=" . $latitude . "&longitude=" . $longitude . "&zoom=18&carrier=" . @$carrier . "&back=Edit.php?id=" . $id;
if (@$no_reader != 'true') echo '<a class="widget widget_emote" title="View all info" href="Reader.php?back_url='. $_SERVER["SCRIPT_NAME"] .'&mp-id='.$id.'">🔍</a>';
if (@$no_delete != 'true') echo '<a class="widget widget_emote" title="Delete" href="Delete.php?id='.$id.'">✂️</a>';
if (@$no_map != 'true') echo '<a class="widget widget_emote" title="View on Database Map" class="widget" href="' . $db_map_link . '">🌎</a>';
if (@$no_edit != 'true') echo '<a class="widget widget_emote" target="_blank" title="Edit" class="widget" href="Edit.php?id='.$id.'">🔧</a>';
if (!empty($street_view_url_a) && @$no_street_view != 'true') echo '<a class="widget" target="_blank" title="Street View URL" class="widget" href="' . $street_view_url_a . '">🚗</a>';
?>
