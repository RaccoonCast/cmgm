<?php
// Generate Links for File Attaches
$foreachList = array('photo_a', 'photo_b', 'photo_c', 'photo_d', 'photo_e', 'photo_f', 'attached_a', 'attached_b', 'attached_c', 'evidence_a', 'evidence_b', 'evidence_c', 'street_view_url_a', 'street_view_url_b', 'street_view_url_c', 'street_view_url_d');

foreach ($foreachList as &$value) {
$val = $value . "_label";
$link_suffix = ucfirst(substr($value,-1));

if (!empty($$value)) {
    if(substr($$value,0,4)=="http") {$$val = '<a class="pad-small-link" target="_blank" href="' . $$value . '">' . $link_suffix . '</a>';}
    elseif (file_exists("uploads/" . ($$value))) {$$val = '<a class="pad-small-link" target="_blank" href="uploads/' . $$value . '">' . $link_suffix . '</a>';}
    else {$$val = '<a class="pad-small-link error" title="' . $value . ' is missing." target="_blank" href="#">' . $link_suffix . '</a>';}
    } else { $$val = null; }
}
if (empty($street_view_url_a) && empty($street_view_url_b) && empty($street_view_url_c) && empty($street_view_url_d)) $street_view_url_a_label = '<a class="pad-small-link error" target="_blank" href="https://www.google.com/maps?layer=c&cbll=' . @$latitude. ',' . @$longitude . '">A</a>';
if (isset($_GET['new'])) { echo '<title>EvilCM - New</title>'; } else { echo '<title>EvilCM - Edit (' . $LTE_1 . ')</title>'; }
?>
