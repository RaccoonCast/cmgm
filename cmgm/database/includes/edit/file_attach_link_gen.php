<?php
// Generate Links for File Attaches
$foreachList = array('photo_a', 'photo_b', 'photo_c', 'photo_d', 'photo_e', 'photo_f', 'extra_a', 'extra_b', 'extra_c', 'extra_d', 'extra_e', 'extra_f', 'evidence_a', 'evidence_b', 'evidence_c', 'sv_a', 'sv_b', 'sv_c', 'sv_d', 'sv_e', 'sv_f');

foreach ($foreachList as &$value) {

if (strpos($value, 'sv') !== false && !empty($$value)) ${$value} = "https://" . $$value;

$val = $value . "_label";
$link_suffix = ucfirst(substr($value,-1));

if (!empty($$value)) {
    if(substr($$value,0,4)=="http") {$$val = '<a class="pad-small-link pad-small-link-mobile" target="_blank" href="' . $$value . '">' . $link_suffix . '</a>';}
    elseif (substr($$value, 0, 1) === '#') {$$val = '<a class="pad-small-link pad-small-link-mobile" target="_blank" href="Edit.php?id=' . substr($$value, 1) . '">' . $link_suffix . '</a>';}
    elseif (file_exists("uploads/" . ($$value))) {$$val = '<a class="pad-small-link pad-small-link-mobile" target="_blank" href="uploads/' . $$value . '">' . $link_suffix . '</a>';}
    else {$$val = '<a class="pad-small-link error" title="' . $value . ' is missing." target="_blank" href="#">' . $link_suffix . '</a>';}
    } else { $$val = null; }
}

if (empty($sv_a) && empty($sv_b) && empty($sv_c) && empty($sv_d)) $sv_a_label = '<a class="pad-small-link error" target="_blank" href="https://www.google.com/maps?layer=c&cbll=' . @$latitude. ',' . @$longitude . '">A</a>';

if($isMobile == "false") {
  $photo_link_linklabel_a = @$photo_a_label.@$photo_b_label.@$photo_c_label;
  $photo_link_linklabel_b = @$photo_d_label.@$photo_e_label.@$photo_f_label;

  $extra_linklabel_a = @$extra_a_label.@$extra_b_label.@$extra_c_label;
  $extra_linklabel_b = @$extra_d_label.@$extra_e_label.@$extra_f_label;

  $sv_linklabel_a = @$sv_a_label.@$sv_b_label.@$sv_c_label;
  $sv_linklabel_b = @$sv_d_label.@$sv_e_label.@$sv_f_label;
} else {
  $photo_link_linklabel_a = @$photo_a_label.@$photo_b_label.@$photo_c_label.@$photo_d_label.@$photo_e_label.@$photo_f_label;
  $sv_linklabel_a = @$sv_a_label.@$sv_b_label.@$sv_c_label.@$sv_d_label.@$sv_e_label.@$sv_f_label;
  $extra_linklabel_a = @$extra_a_label.@$extra_b_label.@$extra_c_label.@$extra_d_label.@$extra_e_label.@$extra_f_label;
}

if (isset($_GET['new'])) { echo '<title>CMGM - New</title>'; } elseif(!empty($LTE_1)) { echo '<title>CMGM - Edit (' . $LTE_1 . ')</title>'; } else { echo '<title>CMGM - Edit (Unknown)</title>'; }
?>
