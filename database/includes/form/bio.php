<?php
if (isMobile()) {
    ?>
<p>Extra notes: </p>
<textarea style="height: 250px;" class="custominput" rows="10" cols="35" id="bio" name="bio"></textarea>
<?php
} else {
?>
<textarea rows="5" cols="101" id="bio" name="bio"></textarea>
<?php
}
?>
