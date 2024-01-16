<?php
// Generate Links for File Attaches

function file_exists_on_local($file) { 
$headers = @get_headers("https://files.cmgm.us/" . $file); 
return ($headers && strpos($headers[0], '200')) ? true : false;
}

$foreachList = array('photo_a', 'photo_b', 'photo_c', 'photo_d', 'photo_e', 'photo_f', 'extra_a', 'extra_b', 'extra_c', 'extra_d', 'extra_e', 'extra_f', 'evidence_a', 'evidence_b', 'evidence_c', 'sv_a', 'sv_b', 'sv_c', 'sv_d', 'sv_e', 'sv_f', 'bingmaps_a', 'bingmaps_b', 'bingmaps_c');

foreach ($foreachList as &$value) {

if (strpos($value, 'sv') !== false && !empty($$value)) ${$value} = "https://" . $$value;

$val = $value . "_label";
$link_suffix = ucfirst(substr($value,-1));

if (!empty($$value)) {
    if(substr($$value,0,4)=="http") {$$val = '<a class="pad-small-link pad-small-link-mobile" target="_blank" href="' . $$value . '">' . $link_suffix . '</a>';}
    elseif (substr($$value, 0, 1) === '#') {$$val = '<a class="pad-small-link pad-small-link-mobile" target="_blank" href="Edit.php?id=' . substr($$value, 1) . '">' . $link_suffix . '</a>';}
    elseif (preg_match('/^(?:image|canon|misc|photo)/', $$value)) { {$$val = '<a class="pad-small-link pad-small-link-mobile" target="_blank" href="https://files.cmgm.us/' . $$value . '">' . $link_suffix . '</a>';}
    } else { $$val = null; }
}

if (empty($sv_a) && empty($sv_b) && empty($sv_c) && empty($sv_d) && empty($sv_e) && empty($sv_f)) $sv_a_label = '<a class="pad-small-link error" target="_blank" href="https://www.google.com/maps?layer=c&cbll=' . @$latitude. ',' . @$longitude . '">A</a>';
if (empty($bingmaps_a) && empty($bingmaps_b) && empty($bingmaps_c)) $bingmaps_a_label = '<a class="pad-small-link error" target="_blank" href="https://www.bing.com/maps?dir=0&lvl=22&style=b&cp=' . @$latitude. '~' . @$longitude . '">A</a>';

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

if (isset($new)) {
   echo '<title>New</title>';
   } elseif(!empty($LTE_1)) {
      echo '<title>#'.$id.' - ' . $LTE_1 . ' (Edit)</title>';
      } elseif(!empty($NR_1)) {
         echo '<title>#'.$id.' - ' . $NR_1 . ' (Edit)</title>';
         } else {
           echo '<title>#'.$id.' - Unknown (Edit)</title>';
           }

// if ($carrier == "T-Mobile") {
//   echo '<link rel="icon" type="image/png" href="/images/logo-magenta.png">';
//   }
// if ($carrier == "Sprint") {
//   echo '<link rel="icon" type="image/png" href="/images/logo-yellow.png">';
//   }
// if ($carrier == "Dish") {
//   echo '<link rel="icon" type="image/png" href="/images/logo-orange.png">';
//   }
// if ($carrier == "ATT") {
//   echo '<link rel="icon" type="image/png" href="/images/logo-blue.png">';
//   }
// if ($carrier == "Verizon") {
//   echo '<link rel="icon" type="image/png" href="/images/logo-red.png">';
//   }
}
?>