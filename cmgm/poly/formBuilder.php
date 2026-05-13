<?php
$parentFile = $currentPage = basename($_SERVER['SCRIPT_FILENAME']);
$onGui  = ($parentFile == "gui.php");
$onMap  = ($parentFile == "Map.php");
?>

<div class="header <?= $onMap ? 'headerFloating' : '' ?>">
   <div class="formsContainerContainer">
      <div id="formsContainer">
         <?php
            $standard_plmns = ["310410", "310120", "310260", "311480", "313340", "311580", "0"];
            ?>
            <select name="plmn" id="Plmn">
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
            <select name="rat" id="Rat">
                <option <?php if($rat == "LTE") echo "selected"; ?> value="LTE">LTE</option>
                <option <?php if($rat == "NR") echo "selected"; ?> value="NR">NR</option>
                <option <?php if (is_null($rat)) echo "selected"; ?> value="">All RATs</option>
            </select>

            <?php if ($onMap): ?>
         <select class="misc_cw" title="Set batch size" name="requestBatchSize" id="requestBatchSize">
            
            <option style="display:none" value="<?php if ($limit !== 0)
               echo $limit; ?>" selected>
               Batch size: <?php echo $limit; ?>
            </option>
            <?php if ($limit == 0) { ?>
            <option style="display:none" value="0" selected>
               Batch size: Unlimited
            </option>
            <?php } ?>
            <option value="50">50</option>
            <option value="125">125</option>
            <option value="250">250</option>
            <option value="450">450</option>
            <option value="800">800</option>
            <option value="1500">1500</option>
            <option value="3000">3000</option>
            <option value="7500">7500</option>
            <option value="15000">15000</option>
            <option value="40000">40000</option>
            <option value="0">Unlimited (Slow)</option>
            <option value="" disabled>--</option>
            <option value="_custom_">Custom batch size</option>
         </select>
         <?php endif; ?>
         <select class="misc_cw" title="Set view mode" name="viewMode" id="viewMode">
            <?php 
               if ($viewMode == 'enbs') $viewModeName = "View Mode: eNB"; 
               if ($viewMode == 'cells') $viewModeName = "View Mode: Cell";
               ?>
            <option style="display:none" value="<?= $viewMode; ?>" selected>
               <?= $viewModeName; ?>
            </option>
            <option value="enbs">eNB</option>
            <option value="cells">Cell</option>
         </select>
         <?php if ($onMap): ?> <button class="poly-btn" id="hamburger-menu">▼</button>
         <div id="hamburger-area" <?= $onMap ? 'hidden' : '' ?>>
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
               <option value="1">1</option>
               <option value="3">3</option>
               <option value="5">5</option>
               <option value="8">8</option>
               <option value="10">10</option>
               <option value="15">15</option>
               <option value="25">25</option>
               <option value="" disabled>--</option>
               <option value="_custom_">Custom...</option>
            </select>
            </label>
            <?php endif; ?>
            <?= $onMap ? '<br>' : '' ?>
            <label>Date Filters</label>
            <input class="adv-filter" type="text" id="oldest_date" name="oldest_date" placeholder="First Seen<?= !$onGui ? ' >YYYY-MM-DD' : '' ?>" value="<?= !empty($oldest_date) ? $oldest_date : ''; ?>">
            <input class="adv-filter" type="text" id="newest_date" name="newest_date" placeholder="Last Seen<?= !$onGui ? ' (<YYYY-MM-DD)' : '' ?>" value="<?= !empty($newest_date) ? $newest_date : ''; ?>" <?php if ($viewMode == 'cells') echo 'disabled' ?>>
            <?= $onMap ? '<br>' : '' ?>
            <label>Cell Filters</label>
            <input class="adv-filter" type="text" id="cellsAllowList" name="cellsAllowList" placeholder="Whitelist Cells<?= !$onGui ? ' (1,2,3)' : '' ?>" value="<?= !empty($cellsAllowList) ? $cellsAllowList : ''; ?>">
            <input class="adv-filter" type="text" id="cellsBlockList" name="cellsBlockList" placeholder="Blacklist Cells<?= !$onGui ? ' (7,8,9)' : '' ?>" value="<?= !empty($cellsBlockList) ? $cellsBlockList : ''; ?>">
            <?= $onMap ? '<br>' : '' ?>
            <label>eNB Filters</label>
            <input class="adv-filter" type="text" id="enbAllowList" name="enbAllowList" placeholder="Whitelist eNBs<?= !$onGui ? ' (80000-81000)' : '' ?>" value="<?= !empty($enbAllowList) ? $enbAllowList : ''; ?>">
            <input class="adv-filter" type="text" id="enbBlockList" name="enbBlockList" placeholder="Blacklist eNBs<?= !$onGui ? ' (1-10,5-10)' : '' ?>" value="<?= !empty($enbBlockList) ? $enbBlockList : ''; ?>">
            <?= $onMap ? '<br>' : '' ?>
            <label>TAC Filters</label>
            <input class="adv-filter" type="text" id="tacsAllowList" name="tacsAllowList" placeholder="Whitelist TACs<?= !$onGui ? ' (15279,15301)' : '' ?>" value="<?= !empty($tacsAllowList) ? $tacsAllowList : ''; ?>">
            <input class="adv-filter" type="text" id="tacsBlockList" name="tacsBlockList" placeholder="Blacklist TACs<?= !$onGui ? ' (1024-1048)' : '' ?>" value="<?= !empty($tacsBlockList) ? $tacsBlockList : ''; ?>">
            <?= $onMap ? '<br>' : '' ?>
            <label>Miscellaneous</label>
            <input class="adv-filter" type="text" id="score" name="score" placeholder="Score<?= !$onGui ? ' (>30, 1-250, 940)' : '' ?>" value="<?= !empty($score) ? $score : ''; ?>">
            <input class="adv-filter" type="text" id="cellQuantity" name="cellQuantity" placeholder="Cells Quantity<?= !$onGui ? ' (>3, <20, 3)' : '' ?>" value="<?= !empty($cellQuantity) ? $cellQuantity : ''; ?>" <?php if ($viewMode == 'cells') echo 'disabled' ?>>
            <?= $onMap ? '<br>' : '' ?>
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
            <?php } if (!$onMap) { ?>
                <input type="text" name="latitude" placeholder="Latitude" value="<?= @$_GET['latitude'] ?>">
                <input type="text" name="longitude" placeholder="Longitude" value="<?= @$_GET['longitude'] ?>">
                <input type="number" step="0.1" name="radius" placeholder="Radius" value="<?= @$_GET['radius'] ?>">
                <input type="number" step="1" name="limit" placeholder="Limit" value="<?= $limit ?>">
                <input class="poly-btn colorized" id="submitButton" type="submit" value="View">
                <button type="button" class="poly-btn" onclick="location.href=location.href+'&download'">CSV</button>
                <?php } ?>
                <button type="button" class="poly-btn" id="guiMapToggle"><?= $onMap ? 'View on GUI' : 'Map' ?></button>
         </div>
      </div>
   </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const setupCustomDropdown = (id, promptText) => {
        const el = document.getElementById(id);
        if (!el) return;

        el.onchange = () => {
            const val = el.value;
            if (val !== '_custom_') return;

            const customVal = prompt(promptText);

            if (!customVal) {
                el.selectedIndex = 0; // Reset to top option if cancelled
                return;
            }

            // Check if this value is already in the list
            const existing = [...el.options].find(o => o.value === customVal);

            if (existing) {
                el.value = customVal;
            } else {
                // Create a new option so the custom value can be submitted
                const newOpt = new Option(customVal, customVal, true, true);
                el.add(newOpt, el.options[1]); // Insert near the top
                el.value = customVal;
            }
        };
    };

    // Apply to both of your dropdowns
    setupCustomDropdown('Plmn', 'Enter Custom PLMN:');
    setupCustomDropdown('iconSize', 'Enter Custom Icon Size:');
});
<?php $destination = $onMap ? 'gui.php' : 'Map.php';?>
document.addEventListener('DOMContentLoaded', () => {
    const button = document.getElementById('guiMapToggle');
    if (!button) return;

    button.addEventListener('click', () => {
        let currentUrl = new URL(window.location.href);

        // Inject the PHP-determined destination directly
        const newPage = '<?= $destination ?>';

        // Swap the filename while keeping all query parameters (?lat=... etc)
        currentUrl.pathname = currentUrl.pathname.replace(/[^/]+$/, newPage);

        window.location.href = currentUrl.toString();
    });
});
</script>