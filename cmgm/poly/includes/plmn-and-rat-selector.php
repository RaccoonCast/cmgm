<?php
$standard_plmns = ["310410", "310120", "310260", "311480", "313340", "311580", "0"];
?>
<select name="plmn" id="filterPlmn">
    <option <?php if($plmn == "310410") echo "selected"; ?> value="310410">AT&T</option>
    <option <?php if($plmn == "310120") echo "selected"; ?> value="310120">Sprint</option>
    <option <?php if($plmn == "310260") echo "selected"; ?> value="310260">T-Mobile</option>
    <option <?php if($plmn == "311480") echo "selected"; ?> value="311480">Verizon</option>
    <option <?php if($plmn == "313340") echo "selected"; ?> value="313340">Dish Wireless</option>
    <option <?php if($plmn == "311580") echo "selected"; ?> value="311580">US Cellular</option>
    <option value="" disabled>--</option>
    <?php if (!in_array($plmn, $standard_plmns)): ?><option value="<?php echo $plmn; ?>" selected><?php echo $plmn; ?></option><?php endif; ?>
    <option value="_custom_">Custom PLMN</option>
    <option <?php if (is_null($plmn)) echo "selected"; ?> value="">&#8203;All PLMNs</option>
</select>
<select name="rat" id="filterRat">
    <option <?php if($rat == "LTE") echo "selected"; ?> value="LTE">LTE</option>
    <option <?php if($rat == "NR") echo "selected"; ?> value="NR">NR</option>
    <option <?php if (is_null($rat)) echo "selected"; ?> value="">All RATs</option>
</select>