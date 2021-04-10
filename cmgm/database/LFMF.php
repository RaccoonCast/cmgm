<!DOCTYPE html>
<head>
<?php
include "../functions.php";
$missing = "";
?>
</head>
<?php
$sql = "SELECT id,evidence_link,photo_link_a,photo_link_b,photo_link_c,photo_link_d,attached_file_link FROM database_db";
$result = mysqli_query($conn,$sql);

while($row = $result->fetch_assoc()) {
    foreach ($row as $key => $value)
        $$key = $value;
        if(substr($evidence_link, 0, 14) == "image-evidence") {
            if (file_exists("uploads/" . $evidence_link)) {$evidence_link_label = '<a target="_blank" href=uploads/' . "$evidence_link" . '>Evidence Link</a>';
            } else {$missing = $missing . " $id";}
            } elseif(!empty($evidence_link)) {$evidence_link_label = '<a target="_blank" href=' . "$evidence_link" . '>Evidence Link</a>';}
            else {$evidence_link_label = "Evidence Link";}

        if(substr($photo_link_a, 0, 6) == "image-") {
            if (file_exists("uploads/" . $photo_link_a)) {$photo_link_a_label = 'Photo Link <a  class="photo_link_link" target="_blank" href=uploads/' . "$photo_link_a" . '>1</a>';
            } else {$missing = $missing . " $id";}
            } elseif(!empty($photo_link_a)) {$photo_link_a_label = 'Photo Link <a class="photo_link_link" target="_blank" href=' . "$photo_link_a" . '>1</a>';}
            else {$photo_link_a_label = "Photo Link(s)";}

        if(substr($photo_link_b, 0, 6) == "image-") {
            if (file_exists("uploads/" . $photo_link_b)) {$photo_link_b_label = '<a class="photo_link_link" target="_blank" href=uploads/' . "$photo_link_b" . '>2</a>';
            } else {$missing = $missing . " $id";}
            } elseif(!empty($photo_link_b)) {$photo_link_b_label = '<a class="photo_link_link" target="_blank" href=' . "$photo_link_b" . '>2</a>';}
            else {$photo_link_b_label = null;}

          if(substr($photo_link_c, 0, 6) == "image-") {
            if (file_exists("uploads/" . $photo_link_c)) {$photo_link_c_label = '<a class="photo_link_link" target="_blank" href=uploads/' . "$photo_link_c" . '>3</a>';
            } else {$missing = $missing . " $id";}
            } elseif(!empty($photo_link_c)) {$photo_link_c_label = '<a class="photo_link_link" target="_blank" href=' . "$photo_link_c" . '>3</a>';}
            else {$photo_link_c_label = null;}

          if(substr($photo_link_d, 0, 6) == "image-") {
            if (file_exists("uploads/" . $photo_link_d)) {$photo_link_d_label = '<a class="photo_link_link" target="_blank" href=uploads/' . "$photo_link_d" . '>4</a>';
            } else {$missing = $missing . " $id";}
            } elseif(!empty($photo_link_d)) {$photo_link_d_label = '<a class="photo_link_link" target="_blank" href=' . "$photo_link_d" . '>4</a>';}
            else {$photo_link_d_label = null;}

        if(substr($attached_file_link, 0, 5) == "misc-") {
            if (file_exists("uploads/" . $attached_file_link)) {$attached_file_link_label = '<a target="_blank" href=uploads/' . "$attached_file_link" . '>Attached file link</a>';
            } else {$missing = $missing . " $id";}
            } elseif(!empty($attached_file_link)) {$attached_file_link_label = '<a target="_blank" href=' . "$attached_file_link" . '>Attached file Link</a>';}
            else {$attached_file_link = null;}
}
echo "The following IDs have missing EV: " . $missing;
$result->close(); $conn->close();
?>
</body>
</html>
