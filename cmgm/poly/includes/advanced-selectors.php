
<?php
$onGui = basename($_SERVER['SCRIPT_FILENAME']) === 'gui.php';
if (!$onGui) {
?>
<label>Appearance</label>
    <?php
    $labelMap = [
    0 => "Never",
    1 => "at Very High Zoom",
    2 => "at High Zoom",
    3 => "Normal",
    4 => "at Low Zoom",
    5 => "at Very Low Zoom",
    6 => "Always"
    ];
    $labelSettingsName = $labelMap[$labelSettings];
    
    ?>
    <select class="misc_cw adv-filter" title="Customize label visibility" name="labelSettings" id="labelSettings">
        <option style="display:none" value="<?php echo $labelSettings; ?>" selected>
            Labels: <?php echo $labelSettingsName; ?>
        </option>
        <option value="0">Never</option>
        <option value="1">at Very High Zoom</option>
        <option value="2">at High Zoom</option>
        <option value="3">Normal</option>
        <option value="4">at Low Zoom</option>
        <option value="5">at Very Low Zoom</option>
        <option value="6">Always</option>
    </select>
    <select class="misc_cw adv-filter" title="Set icon size" name="iconSize" id="iconSize">
         <option style="display:none" value="<?php echo $iconSize; ?>" selected>
             Icon size: <?php echo $iconSize; ?>
         </option>
         <option value="3">3</option>
         <option value="5">5</option>
         <option value="8">8</option>
         <option value="10">10</option>
         <option value="15">15</option>
         <option value="20">20</option>
         <option value="25">30</option>
         <option value="50">50</option>
         <option value="100">100</option>
         <option value="" disabled>--</option>
         <option value="_custom_">Custom...</option>
    </select>
</label>
<br>
<?php } ?>
<label>Date Filters</label>
<input class="adv-filter" type="text" id="oldest_date" name="oldest_date" placeholder="First Seen<?= !$onGui ? ' >YYYY-MM-DD' : '' ?>" value="<?= !empty($oldest_date) ? $oldest_date : ''; ?>">
<input class="adv-filter" type="text" id="newest_date" name="newest_date" placeholder="Last Seen<?= !$onGui ? ' (<YYYY-MM-DD)' : '' ?>" value="<?= !empty($newest_date) ? $newest_date : ''; ?>" <?php if ($viewMode == 'cells') echo 'disabled' ?>>
<br>
<label>Cell Filters</label>
<input class="adv-filter" type="text" id="cellsAllowList" name="cellsAllowList" placeholder="Whitelist Cells<?= !$onGui ? ' (1,2,3)' : '' ?>" value="<?= !empty($cellsAllowList) ? $cellsAllowList : ''; ?>">
<input class="adv-filter" type="text" id="cellsBlockList" name="cellsBlockList" placeholder="Blacklist Cells<?= !$onGui ? ' (7,8,9)' : '' ?>" value="<?= !empty($cellsBlockList) ? $cellsBlockList : ''; ?>">
<br>
<label>eNB Filters</label>
<input class="adv-filter" type="text" id="enbAllowList" name="enbAllowList" placeholder="Whitelist eNBs<?= !$onGui ? ' (80000-81000)' : '' ?>" value="<?= !empty($enbAllowList) ? $enbAllowList : ''; ?>">
<input class="adv-filter" type="text" id="enbBlockList" name="enbBlockList" placeholder="Blacklist eNBs<?= !$onGui ? ' (1-10,5-10)' : '' ?>" value="<?= !empty($enbBlockList) ? $enbBlockList : ''; ?>">
<br>
<label>TAC Filters</label>
<input class="adv-filter" type="text" id="tacsAllowList" name="tacsAllowList" placeholder="Whitelist TACs<?= !$onGui ? ' (15279,15301)' : '' ?>" value="<?= !empty($tacsAllowList) ? $tacsAllowList : ''; ?>">
<input class="adv-filter" type="text" id="tacsBlockList" name="tacsBlockList" placeholder="Blacklist TACs<?= !$onGui ? ' (1024-1048)' : '' ?>" value="<?= !empty($tacsBlockList) ? $tacsBlockList : ''; ?>">
<br>
<label>Miscellaneous</label>
<input class="adv-filter" type="text" id="score" name="score" placeholder="Score<?= !$onGui ? ' (>30, 1-250, 940)' : '' ?>" value="<?= !empty($score) ? $score : ''; ?>">
<input class="adv-filter" type="text" id="cellQuantity" name="cellQuantity" placeholder="Cells Quantity<?= !$onGui ? ' (>3, <20, 3)' : '' ?>" value="<?= !empty($cellQuantity) ? $cellQuantity : ''; ?>" <?php if ($viewMode == 'cells') echo 'disabled' ?>>
<br>

<?php if (!$onGui) { ?>
<div class="checkbox-container">
    <!--
    <label class="checkbox-group">
        <input type="checkbox" id="labels" <?php echo $labels; ?>> Show Labels
    </label> 
    <label id="forceLabelVisibilityArea" class="checkbox-group">
        <input type="checkbox" id="forceLabelVisibility" <?php echo $forceLabelVisibility; ?>> Always show  labels
    </label>
    --> 
    <label id="dontUnloadCheckboxArea" class="checkbox-group">
        <input type="checkbox" id="dontUnload" <?php echo $unload; ?>> Disable Unload
    </label>
    <label id="randomColorCheckboxArea" class="checkbox-group">
        <input type="checkbox" id="randomColor" <?php echo $randomColor; ?>> Randomize Colors
    </label>
    <label class="checkbox-group">
        <input type="checkbox" id="perfectSurroOnly" <?php echo $perfectSurroOnly; ?>> Exact Location Only
    </label>
</div>
<button style="float: left; margin-left: -5px;" class="poly-btn" id="gui-button">View on GUI</button>
<?php } ?>