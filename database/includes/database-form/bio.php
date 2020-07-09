<?php
if (isMobile()) {
    ?>
<p>Extra notes: </p>
<textarea style="height: 250px;" class="fakeinput" rows="15" cols="35" id="bio" name="bio"></textarea>
<?php
} else {
?>
<textarea rows="5" cols="90" id="bio" name="bio"></textarea>
<?php
}
?>
