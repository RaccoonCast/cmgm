<textarea class="custominput" rows="5" cols="30" maxlength="500" placeholder="Street View URL" name="street_view_url"></textarea><br>
<textarea class="custominput" rows="5" cols="30" maxlength="500" placeholder="Tags" name="tags"></textarea><br>
<?php
if (isMobile()) echo '<textarea style="height: 250px;" class="custominput" rows="10" cols="35" id="bio" name="bio"></textarea>';
if (!isMobile()) echo '<textarea rows="5" cols="101" id="bio" name="bio"></textarea>'; ?>