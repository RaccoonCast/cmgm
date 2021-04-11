<!DOCTYPE html>
<head>
<?php
include "../functions.php";
$missing = "";
?>
</head>
<?php
$sql = "SELECT id,evidence_a,photo_a,photo_b,photo_c,photo_d,attached_link FROM database_db";
$result = mysqli_query($conn,$sql);

while($row = $result->fetch_assoc()) {
    foreach ($row as $key => $value)
        $$key = $value;
        if(substr($evidence_a, 0, 14) == "image-evidence") {
            if (file_exists("uploads/" . $evidence_a)) {$evidence_a_label = '<a target="_blank" href=uploads/' . "$evidence_a" . '>Evidence Link</a>';
            } else {$missing = $missing . " $id";}
            } elseif(!empty($evidence_a)) {$evidence_a_label = '<a target="_blank" href=' . "$evidence_a" . '>Evidence Link</a>';}
            else {$evidence_a_label = "Evidence Link";}

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

        if(substr($attached_link, 0, 5) == "misc-") {
            if (file_exists("uploads/" . $attached_link)) {$attached_link_label = '<a target="_blank" href=uploads/' . "$attached_link" . '>Attached file link</a>';
            } else {$missing = $missing . " $id";}
            } elseif(!empty($attached_link)) {$attached_link_label = '<a target="_blank" href=' . "$attached_link" . '>Attached file Link</a>';}
            else {$attached_link = null;}
}
echo "The following IDs have missing EV: " . $missing;
$result->close(); $conn->close();
?>
</body>
</html>
