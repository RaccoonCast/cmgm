<?php
if (isMobile()) {
    ?>
<p>Extra notes: </p>
<textarea style="height: 250px;" class="custominput" rows="15" cols="35" id="bio" name="bio"></textarea>
<?php
} else {
?>
<textarea rows="4" cols="60" id="bio" name="bio"></textarea>
<?php
}
?>
