<?php
// Generate Links for File Attaches
$foreachList = array('photo_a', 'photo_b', 'photo_c', 'photo_d', 'photo_e', 'photo_f', 'extra_a', 'extra_b', 'extra_c', 'evidence_a', 'evidence_b', 'evidence_c', 'street_view_a', 'street_view_b', 'street_view_c', 'street_view_d');

foreach ($foreachList as &$value) {

if (strpos($value, 'street_view') !== false && !empty($$value)) ${$value} = "https://" . $$value;

$val = $value . "_label";
$link_suffix = ucfirst(substr($value,-1));

if (!empty($$value)) {
    if(substr($$value,0,4)=="http") {$$val = '<a class="pad-small-link pad-small-link-mobile" target="_blank" href="' . $$value . '">' . $link_suffix . '</a>';}
    elseif (substr($$value, 0, 1) === '#') {$$val = '<a class="pad-small-link pad-small-link-mobile" target="_blank" href="Edit.php?id=' . substr($$value, 1) . '">' . $link_suffix . '</a>';}
    elseif (file_exists("uploads/" . ($$value))) {$$val = '<a class="pad-small-link pad-small-link-mobile" target="_blank" href="uploads/' . $$value . '">' . $link_suffix . '</a>';}
    else {$$val = '<a class="pad-small-link error" title="' . $value . ' is missing." target="_blank" href="#">' . $link_suffix . '</a>';}
    } else { $$val = null; }

if (strpos($value, 'photo') !== false && !empty($$value)) {
  if($isMobile == "false") {
    $photo_link_linklabel_a = '<span style="float: right">'.@$photo_a_label.@$photo_b_label.@$photo_c_label.'</span>';
    $photo_link_linklabel_b = '<span style="float: right">'.@$photo_d_label.@$photo_e_label.@$photo_f_label.'</span>';
  } else {
    $photo_link_linklabel_a = '<span style="float: right">'.@$photo_a_label.@$photo_b_label.@$photo_c_label.@$photo_d_label.@$photo_e_label.@$photo_f_label.'</span>';
  }
}



}



if (empty($street_view_a) && empty($street_view_b) && empty($street_view_c) && empty($street_view_d)) $street_view_a_label = '<a class="pad-small-link error" target="_blank" href="https://www.google.com/maps?layer=c&cbll=' . @$latitude. ',' . @$longitude . '">A</a>';
if (isset($_GET['new'])) { echo '<title>CMGM - New</title>'; } elseif(!empty($LTE_1)) { echo '<title>CMGM - Edit (' . $LTE_1 . ')</title>'; } else { echo '<title>CMGM - Edit (Unknown)</title>'; }
?>
