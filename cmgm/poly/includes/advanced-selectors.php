
<label>Appearance</label>
    <?php
    $labelMap = [
    0 => "Never",
    1 => "at High Zoom",
    2 => "Normal",
    3 => "at Low Zoom",
    4 => "at Very Low Zoom",
    5 => "Always"
    ];
    $labelSettingsName = $labelMap[$labelSettings];
    
    ?>
    <select class="misc_cw adv-filter" title="Customize label visibility" name="labelSettings" id="labelSettings">
        <option style="display:none" value="<?php echo $labelSettings; ?>" selected>
            Show Labels: <?php echo $labelSettingsName; ?>
        </option>
        <option value="0">Never</option>
        <option value="1">at High Zoom</option>
        <option value="2">Normal</option>
        <option value="3">at Low Zoom</option>
        <option value="4">at Very Low Zoom</option>
        <option value="5">Always</option>
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
<label>Date Filters</label>
<input class="adv-filter" type="text" id="oldest_date" name="oldest_date" placeholder="First Seen (>YYYY-MM-DD)" value="<?= !empty($oldest_date) ? $oldest_date : ''; ?>">
<input class="adv-filter" type="text" id="newest_date" name="newest_date" placeholder="Last Seen (<YYYY-MM-DD)" value="<?= !empty($newest_date) ? $newest_date : ''; ?>" <?php if ($viewMode == 'cells') echo 'disabled' ?>>
<br>
<label>Cell Filters</label>
<input class="adv-filter" type="text" id="cellsAllowList" name="cellsAllowList" placeholder="Whitelist Cells (1,2,3)" value="<?= !empty($cellsAllowList) ? $cellsAllowList : ''; ?>">
<input class="adv-filter" type="text" id="cellsBlockList" name="cellsBlockList" placeholder="Blacklist Cells (7,8,9)" value="<?= !empty($cellsBlockList) ? $cellsBlockList : ''; ?>">
<br>
<label>eNB Filters</label>
<input class="adv-filter" type="text" id="enbAllowList" name="enbAllowList" placeholder="Whitelist eNBs (80000-81000)" value="<?= !empty($enbAllowList) ? $enbAllowList : ''; ?>">
<input class="adv-filter" type="text" id="enbBlockList" name="enbBlockList" placeholder="Blacklist eNBs (1-10,5-10)" value="<?= !empty($enbBlockList) ? $enbBlockList : ''; ?>">
<br>
<label>TAC Filters</label>
<input class="adv-filter" type="text" id="tacsAllowList" name="tacsAllowList" placeholder="Whitelist TACs (15279,15301)" value="<?= !empty($tacsAllowList) ? $tacsAllowList : ''; ?>">
<input class="adv-filter" type="text" id="tacsBlockList" name="tacsBlockList" placeholder="Blacklist TACs (15174)" value="<?= !empty($tacsBlockList) ? $tacsBlockList : ''; ?>">
<br>

<?php if (basename($_SERVER['SCRIPT_FILENAME']) !== 'gui.php') { ?>
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
    <label class="checkbox-group">
        <input type="checkbox" id="perfectSurroOnly" <?php echo $perfectSurroOnly; ?>> Exact Location Only
    </label>
</div>
<?php } ?>