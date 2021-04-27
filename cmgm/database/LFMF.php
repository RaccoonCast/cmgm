<!DOCTYPE html>
<head>
<?php
include "../functions.php";
$missing = "";
?>
</head>
<?php
$sql = "SELECT id,evidence_a,evidence_b,attached_a,attached_b,photo_a,photo_b,photo_c,photo_d,photo_e,photo_f FROM database_db";
$result = mysqli_query($conn,$sql);

while($row = $result->fetch_assoc()) {
    foreach ($row as $key => $value)
        $$key = $value;
        if(substr($evidence_a, 0, 14) == "image-evidence") {
            if (file_exists("uploads/" . $evidence_a)) {$evidence_a_label = '<a target="_blank" href=uploads/' . "$evidence_a" . '>Evidence Link</a>';
            } else {$missing = $missing . " $id";}
            } elseif(!empty($evidence_a)) {$evidence_a_label = '<a target="_blank" href=' . "$evidence_a" . '>Evidence Link</a>';}
            else {$evidence_a_label = "Evidence Link";}

        if(substr($evidence_b, 0, 14) == "image-evidence") {
            if (file_exists("uploads/" . $evidence_b)) {$evidence_b_label = '<a target="_blank" href=uploads/' . "$evidence_b" . '>Evidence Link</a>';
            } else {$missing = $missing . " $id";}
            } elseif(!empty($evidence_b)) {$evidence_b_label = '<a target="_blank" href=' . "$evidence_b" . '>Evidence Link</a>';}
            else {$evidence_b_label = "Evidence Link";}

        if(substr($photo_a, 0, 6) == "image-") {
            if (file_exists("uploads/" . $photo_a)) {$photo_a_label = 'Photo Link <a  class="photo_link" target="_blank" href=uploads/' . "$photo_a" . '>1</a>';
            } else {$missing = $missing . " $id";}
            } elseif(!empty($photo_a)) {$photo_a_label = 'Photo Link <a class="photo_link" target="_blank" href=' . "$photo_a" . '>1</a>';}
            else {$photo_a_label = "Photo Link(s)";}
        if(substr($photo_b, 0, 6) == "image-") {
            if (file_exists("uploads/" . $photo_b)) {$photo_b_label = '<a class="photo_link" target="_blank" href=uploads/' . "$photo_b" . '>2</a>';
            } else {$missing = $missing . " $id";}
            } elseif(!empty($photo_b)) {$photo_b_label = '<a class="photo_link" target="_blank" href=' . "$photo_b" . '>2</a>';}
            else {$photo_b_label = null;}
          if(substr($photo_c, 0, 6) == "image-") {
            if (file_exists("uploads/" . $photo_c)) {$photo_c_label = '<a class="photo_link" target="_blank" href=uploads/' . "$photo_c" . '>3</a>';
            } else {$missing = $missing . " $id";}
            } elseif(!empty($photo_c)) {$photo_c_label = '<a class="photo_link" target="_blank" href=' . "$photo_c" . '>3</a>';}
            else {$photo_c_label = null;}
          if(substr($photo_d, 0, 6) == "image-") {
            if (file_exists("uploads/" . $photo_d)) {$photo_d_label = '<a class="photo_link" target="_blank" href=uploads/' . "$photo_d" . '>4</a>';
            } else {$missing = $missing . " $id";}
            } elseif(!empty($photo_d)) {$photo_d_label = '<a class="photo_link" target="_blank" href=' . "$photo_d" . '>4</a>';}
            else {$photo_d_label = null;}
          if(substr($photo_e, 0, 6) == "image-") {
            if (file_exists("uploads/" . $photo_e)) {$photo_e_label = '<a class="photo_link" target="_blank" href=uploads/' . "$photo_e" . '>4</a>';
            } else {$missing = $missing . " $id";}
            } elseif(!empty($photo_e)) {$photo_e_label = '<a class="photo_link" target="_blank" href=' . "$photo_e" . '>4</a>';}
            else {$photo_e_label = null;}
          if(substr($photo_f, 0, 6) == "image-") {
            if (file_exists("uploads/" . $photo_f)) {$photo_f_label = '<a class="photo_link" target="_blank" href=uploads/' . "$photo_f" . '>4</a>';
            } else {$missing = $missing . " $id";}
            } elseif(!empty($photo_f)) {$photo_f_label = '<a class="photo_link" target="_blank" href=' . "$photo_f" . '>4</a>';}
            else {$photo_f_label = null;}
            
        if(substr($attached_a, 0, 5) == "misc-") {
            if (file_exists("uploads/" . $attached_a)) {$attached_a_label = '<a target="_blank" href=uploads/' . "$attached_a" . '>Attached file link</a>';
            } else {$missing = $missing . " $id";}
            } elseif(!empty($attached_a)) {$attached_a_label = '<a target="_blank" href=' . "$attached_a" . '>Attached file Link</a>';}
            else {$attached_a = null;}
        if(substr($attached_b, 0, 5) == "misc-") {
            if (file_exists("uploads/" . $attached_b)) {$attached_b_label = '<a target="_blank" href=uploads/' . "$attached_b" . '>Attached file link</a>';
            } else {$missing = $missing . " $id";}
            } elseif(!empty($attached_b)) {$attached_b_label = '<a target="_blank" href=' . "$attached_b" . '>Attached file Link</a>';}
            else {$attached_b = null;}
}
echo "The following IDs have missing EV: " . $missing;
$result->close(); $conn->close();
?>
</body>
</html>
