<?php
$url_for_cmgm = "'https://cmgm.us/$id'";
$url_for_maprad = "https://maprad.io/us/search/coordinates/1000/$latitude,$longitude?source=US&coordStr=$latitude,$longitude&radius=1000";
if (!empty($latitude) && !empty($longitude)) $db_map_link = "Map.php?latitude=" . $latitude . "&longitude=" . $longitude . "&zoom=18&carrier=" . @$carrier . "&back=Edit.php?id=" . $id;
if (@$no_reader != 'true') echo '<a class="widget widget_emote" title="View all info" href="Reader.php?back_url='. $_SERVER["SCRIPT_NAME"] .'&id='.$id.'">🔍</a>';
if (@$no_delete != 'true') echo '<a class="widget widget_emote" title="Delete" href="Edit.php?id='.$id.'&delete=false&redirPage='.@$redirPage.'">🗑️</a>';
if (@$no_map != 'true' && isset($db_map_link)) echo '<a target="_blank" class="widget widget_em ote" title="View on Database Map" href="' . $db_map_link . '">🌎</a>';
if (@$no_antennasearch != 'true') echo '<a class="widget widget_emote" target="_blank" title="AnntennaSearch" href="https://www.antennasearch.com/HTML/search/search.php?address=' . $latitude . ',' . $longitude . '">📡</a>';
if (@$no_maprad != 'true') echo '<a class="widget widget_emote" target="_blank" title="Maprad" href="'.$url_for_maprad.'">🌐</a>';
if (@$no_edit != 'true') echo '<a class="widget widget_emote" target="_blank" title="Edit" href="Edit.php?id='.$id.'">🔧</a>';
if (@$no_copy != 'true') echo '<a title="Copy CMGM URL" class="widget widget_emote" onclick="copyToClipboard('.$url_for_cmgm.')" href="javascript:;">📋</a>';
if (basename($_SERVER['SCRIPT_NAME']) == "Edit.php") {
  if (empty($edit_lock)) {
    echo '<a class="widget widget_emote" title="Lock" href="Edit.php?lock_status=lock&id='.$id.'">🔒</a>';
  } else {
    echo '<a class="widget widget_emote" title="Unlock" href="Edit.php?lock_status=unlock&id='.$id.'">🔓</a>';
  }
}
?>
