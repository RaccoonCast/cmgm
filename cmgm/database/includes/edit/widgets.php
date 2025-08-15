<?php
echo '<div id="edit_utilitiy_holder">';
if ($carrier == 'ATT') $plmn = 310410;
elseif ($carrier == 'Verizon') $plmn = 311480;
elseif ($carrier == 'T-Mobile') $plmn = 310260;
else $plmn = 0;

// prev/next links for cmgm edit page
echo '<div id="widgets">';
if (!isset($delete) && !isset($new) && !isset($_GET['lock_status']) && $padlock == "false" && isset($id)) {
$url_for_cmgm = "'https://cmgm.us/$id'";
$url_for_maprad = "https://maprad.io/us/search/coordinates/1000/$latitude,$longitude?source=US&coordStr=$latitude,$longitude&radius=1000";
$url_for_look_around = "https://lookmap.eu.pythonanywhere.com/#c=20/$latitude/$longitude&p=$latitude/$longitude&a=360/34.00";
if (!empty($latitude) && !empty($longitude)) $db_map_link = "Map.php?latitude=" . $latitude . "&longitude=" . $longitude . "&zoom=18&carrier=" . @$carrier;
echo '<a class="widget widget_emote" title="View all info" href="Reader.php?id='.$id.'">🔍</a>';
echo '<a class="widget widget_emote" title="Delete" href="Edit.php?id='.$id.'&delete=false&redirPage='.@$redirPage.'">🗑️</a>';
if (isset($db_map_link)) echo '<a target="_blank" class="widget widget_em ote" title="View on Database Map" href="' . $db_map_link . '">🌎</a>';
echo '<a class="widget widget_emote" target="_blank" title="AntennaSearch" href="https://www.antennasearch.com/HTML/search/search.php?address=' . $latitude . ',' . $longitude . '">📡</a>';
echo '<a class="widget widget_emote" target="_blank" title="Poly Map" href="https://cmgm.us/poly/map.php?latitude='.$latitude.'&longitude='.$longitude.'&zoom=16&plmn='.$plmn.'">🔷</a>';
echo '<a class="widget widget_emote" target="_blank" title="Maprad" href="'.$url_for_maprad.'">🌐</a>';
echo '<a title="Apple Look Around" target="_blank" class="widget widget_emote" href="'.$url_for_look_around.'">🚘</a>';
echo '<a title="Copy CMGM URL" class="widget widget_emote" onclick="copyToClipboard('.$url_for_cmgm.')" href="javascript:;">📋</a>';
if (basename($_SERVER['SCRIPT_NAME']) == "Edit.php") {
  if (empty($edit_lock)) {
    echo '<a class="widget widget_emote" title="Lock" href="Edit.php?lock_status=lock&id='.$id.'">🔒</a>';
  } else {
    echo '<a class="widget widget_emote" title="Unlock" href="Edit.php?lock_status=unlock&id='.$id.'">🔓</a>';
  }
}
}
echo '</div>';
?>
<a class="prevnext_link" href="?id=<?php echo $id-1; ?>&back=1">Prev</a>
<a class="prevnext_link" href="?id=<?php echo $id+1; ?>&next=1">Next</a><span style="font-size: 0.85em;">(1)</span><br>

<a class="prevnext_link" href="?id=<?php echo $id-10; ?>&back=1">Prev</a>
<a class="prevnext_link" href="?id=<?php echo $id+10; ?>&next=1">Next</a><span style="font-size: 0.85em;">(10)</span><br>
<a class="prevnext_link" href="?id=<?php echo $id-100; ?>&back=1">Prev</a>
<a class="prevnext_link" href="?id=<?php echo $id+100; ?>&next=1">Next</a><span style="font-size: 0.85em;">(100)</span><br>
<a class="prevnext_link" href="?id=<?php echo $id-1000; ?>&back=1">Prev</a>
<a class="prevnext_link" href="?id=<?php echo $id+1000; ?>&next=1">Next</a><span style="font-size: 0.85em;">(1000)</span><br>
<div class="oredit" style="margin: 0.2em;">
Or edit
<form style="display:inline" action="Edit.php" autocomplete="off" method="get">
<input type="text" style="width: 155px; height: 36px" value="" placeholder="" name="q" required><input type="submit" class="cmgm-btn" style="height: 36px"><!--<input type="submit" style="height: 36px" name="q" class="cmgm-btn" value="Location Search">-->
</form>
</div>
</div>