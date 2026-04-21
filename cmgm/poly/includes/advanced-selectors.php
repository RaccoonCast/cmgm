

<label>Date Filters</label>
<input class="adv-filter-textbox" type="text" id="oldest_date" name="oldest_date" placeholder="First Seen (>YYYY-MM-DD)" value="<?= !empty($oldest_date) ? $oldest_date : ''; ?>">
<input class="adv-filter-textbox" type="text" id="newest_date" name="newest_date" placeholder="Last Seen (<YYYY-MM-DD)" value="<?= !empty($newest_date) ? $newest_date : ''; ?>">
<br>
<label>Cell Filters</label>
<input class="adv-filter-textbox" type="text" id="cellsAllowList" name="cellsAllowList" placeholder="Whitelist Cells (1,2,3)" value="<?= !empty($cellsAllowList) ? $cellsAllowList : ''; ?>">
<input class="adv-filter-textbox" type="text" id="cellsBlockList" name="cellsBlockList" placeholder="Blacklist Cells (7,8,9)" value="<?= !empty($cellsBlockList) ? $cellsBlockList : ''; ?>">
<br>
<label>eNB Filters</label>
<input class="adv-filter-textbox" type="text" id="enbAllowList" name="enbAllowList" placeholder="Whitelist eNBs (80000-81000)" value="<?= !empty($enbAllowList) ? $enbAllowList : ''; ?>">
<input class="adv-filter-textbox" type="text" id="enbBlockList" name="enbBlockList" placeholder="Blacklist eNBs (1-10,5-10)" value="<?= !empty($enbBlockList) ? $enbBlockList : ''; ?>">
<br>
<label>TAC Filters</label>
<input class="adv-filter-textbox" type="text" id="tacsAllowList" name="tacsAllowList" placeholder="Whitelist TACs (15279,15301)" value="<?= !empty($tacsAllowList) ? $tacsAllowList : ''; ?>">
<input class="adv-filter-textbox" type="text" id="tacsBlockList" name="tacsBlockList" placeholder="Blacklist TACs (15174)" value="<?= !empty($tacsBlockList) ? $tacsBlockList : ''; ?>">
<br>
<?php if (basename($_SERVER['SCRIPT_FILENAME']) !== 'gui.php') { ?>
<div class="checkbox-container">
    <label class="checkbox-group">
        <input type="checkbox" id="labels" <?php echo $labels; ?>> Labels
    </label>
    <label class="checkbox-group">
        <input type="checkbox" id="dontUnload" <?php echo $unload; ?>> No Unload
    </label>
    <label class="checkbox-group">
        <input type="checkbox" id="perfectSurroOnly" <?php echo $perfectSurroOnly; ?>> Perfect Surro Only
    </label>
</div>
<?php } ?>