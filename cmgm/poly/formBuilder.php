<?php
$parentFile = $currentPage = basename($_SERVER['SCRIPT_FILENAME']);
$onGui  = ($parentFile == "gui.php");
$onMap  = ($parentFile == "Map.php");
?>

<div class="header <?= $onMap ? 'headerFloating' : '' ?>">
   <div class="formsContainerContainer">
      <div id="formsContainer">
         <?php
            $standard_plmns = ["310410", "310120", "310260", "311480", "313340", "311580", "0", "310410,313100,312680,313790"];
            ?>
            <select name="plmn" id="plmn">
                <option <?php if($plmn == "310410") echo "selected"; ?> value="310410">AT&T</option>
                <option <?php if($plmn == "310120") echo "selected"; ?> value="310120">Sprint</option>
                <option <?php if($plmn == "310260") echo "selected"; ?> value="310260">T-Mobile</option>
                <option <?php if($plmn == "311480") echo "selected"; ?> value="311480">Verizon</option>
                <option <?php if($plmn == "313340") echo "selected"; ?> value="313340">Dish Wireless</option>
                <option <?php if($plmn == "311580") echo "selected"; ?> value="311580">US Cellular</option>
                <option value="" disabled>--</option>
                <option <?php if($plmn == "310410,313100,312680,313790") echo "selected"; ?> value="310410,313100,312680,313790">&#8203;All AT&Ts</option>
                <?php if (!empty($plmn) && !in_array($plmn, $standard_plmns)): ?><option value="<?php echo $plmn; ?>" selected><?php echo $plmn; ?></option><?php endif; ?>
                <option value="_custom_">Custom PLMN</option>
                <option <?php if (is_null($plmn)) echo "selected"; ?> value="">&#8203;All PLMNs</option>
            </select>
            <select name="rat" id="rat">
                <option <?php if($rat == "LTE") echo "selected"; ?> value="LTE">LTE</option>
                <option <?php if($rat == "NR") echo "selected"; ?> value="NR">NR</option>
                <option <?php if (is_null($rat)) echo "selected"; ?> value="">All RATs</option>
            </select>
            <?php if ($onMap): ?>
         <select <?=isset($_GET['mini'])?'style="display:none"':''?> class="misc_cw" title="Set batch size" name="request_batch_size" id="request_batch_size">
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
         <select <?=isset($_GET['mini'])?'style="display:none"':''?> class="misc_cw" title="Set view mode" name="view_mode" id="view_mode">
            <?php 
               if ($view_mode == 'enbs') $view_modeName = "View Mode: eNB"; 
               if ($view_mode == 'cells') $view_modeName = "View Mode: Cell";
               if ($view_mode == 'cm') $view_modeName = "View Mode: CM";
               ?>
            <option style="display:none" value="<?= $view_mode; ?>" selected>
               <?= $view_modeName; ?>
            </option>
            <option value="enbs">eNB</option>
            <option value="cm">CM</option>
            <option value="cells">Cell</option>
         </select>
         <?php if ($onMap): ?> <button <?=isset($_GET['mini'])?'style="display:none"':''?> class="poly-btn" id="hamburger-menu">▼</button>
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
               $label_settingsName = $labelMap[$label_settings];
               
               ?>
            <select class="misc_cw adv-filter" title="Customize label visibility" name="label_settings" id="label_settings">
               <option style="display:none" value="<?php echo $label_settings; ?>" selected>
                  Labels: <?php echo $label_settingsName; ?>
               </option>
               <option value="0">Never</option>
               <option value="1">at Very High Zoom</option>
               <option value="2">at High Zoom</option>
               <option value="3">Normal</option>
               <option value="4">at Low Zoom</option>
               <option value="5">at Very Low Zoom</option>
               <option value="6">Always</option>
            </select>
            <select class="misc_cw adv-filter" title="Set icon size" name="icon_size" id="icon_size">
               <option style="display:none" value="<?php echo $icon_size; ?>" selected>
                  Icon size: <?php echo $icon_size; ?>
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
            <input class="adv-filter" type="text" id="newest_date" name="newest_date" placeholder="Last Seen<?= !$onGui ? ' (<YYYY-MM-DD)' : '' ?>" value="<?= !empty($newest_date) ? $newest_date : ''; ?>" <?php if ($view_mode == 'cells') echo 'disabled' ?>>
            <?= $onMap ? '<br>' : '' ?>
            <label>Cell Filters</label>
            <input class="adv-filter" type="text" id="cells_allow_list" name="cells_allow_list" placeholder="Whitelist Cells<?= !$onGui ? ' (1,2,3)' : '' ?>" value="<?= !empty($cells_allow_list) ? $cells_allow_list : ''; ?>">
            <input class="adv-filter" type="text" id="cells_block_list" name="cells_block_list" placeholder="Blacklist Cells<?= !$onGui ? ' (7,8,9)' : '' ?>" value="<?= !empty($cells_block_list) ? $cells_block_list : ''; ?>">
            <?= $onMap ? '<br>' : '' ?>
            <label>eNB Filters</label>
            <input class="adv-filter" type="text" id="enb_allow_list" name="enb_allow_list" placeholder="Whitelist eNBs<?= !$onGui ? ' (80000-81000)' : '' ?>" value="<?= !empty($enb_allow_list) ? $enb_allow_list : ''; ?>">
            <input class="adv-filter" type="text" id="enb_block_list" name="enb_block_list" placeholder="Blacklist eNBs<?= !$onGui ? ' (1-10,5-10)' : '' ?>" value="<?= !empty($enb_block_list) ? $enb_block_list : ''; ?>">
            <?= $onMap ? '<br>' : '' ?>
            <label>TAC Filters</label>
            <input class="adv-filter" type="text" id="tacs_allow_list" name="tacs_allow_list" placeholder="Whitelist TACs<?= !$onGui ? ' (15279,15301)' : '' ?>" value="<?= !empty($tacs_allow_list) ? $tacs_allow_list : ''; ?>">
            <input class="adv-filter" type="text" id="tacs_block_list" name="tacs_block_list" placeholder="Blacklist TACs<?= !$onGui ? ' (1024-1048)' : '' ?>" value="<?= !empty($tacs_block_list) ? $tacs_block_list : ''; ?>">
            <?= $onMap ? '<br>' : '' ?>
            <label>Miscellaneous</label>
            <input class="adv-filter" type="text" id="score" name="score" placeholder="Score<?= !$onGui ? ' (>30, 1-250, 940)' : '' ?>" value="<?= !empty($score) ? $score : ''; ?>">
            <input class="adv-filter" type="text" id="cells_quantity" name="cells_quantity" placeholder="Cells Quantity<?= !$onGui ? ' (>3, <20, 3)' : '' ?>" value="<?= !empty($cells_quantity) ? $cells_quantity : ''; ?>" <?php if ($view_mode == 'cells') echo 'disabled' ?>>
            <?= $onMap ? '<br>' : '' ?>
            <?php
            $includesArray = is_array($cm_includes ?? 0) ? $cm_includes : ($cm_includes ? explode(',', $cm_includes) : []);
            $excludesArray = is_array($cm_excludes ?? 0) ? $cm_excludes : ($cm_excludes ? explode(',', $cm_excludes) : []);

            $chips = [
                'CMGMPINNED'   => "CMGM'd",
                'CMPINNED'     => "CM'd",
                'DAS'          => 'DAS',
                'PICO'         => 'Picos',
                'MACRO'        => 'Macros',
                'MAPPED'       => 'Mapped',
                'PERFECTSURRO' => 'Perfect Surro'
            ];
            ?>

            <span id="cm_filters" <?php if ($view_mode != "cm") echo 'style="display:none"';?>>
                <label>CM Filters</label>
                <input type="hidden" name="cm_includes" id="cm_includes" value="<?= is_array($cm_includes ?? 0) ? implode(',', $cm_includes) : ($cm_includes ?? '') ?>">
                <input type="hidden" name="cm_excludes" id="cm_excludes" value="<?= is_array($cm_excludes ?? 0) ? implode(',', $cm_excludes) : ($cm_excludes ?? '') ?>">

                <!-- The 3-State Chips -->
                <div class="chip-filter-container" id="chipContainer">
                    <?php foreach ($chips as $value => $label): 
                        $state = 'neutral';
                        if (in_array($value, $includesArray)) {
                            $state = 'include';
                        } elseif (in_array($value, $excludesArray)) {
                            $state = 'exclude';
                        }
                        $disabledAttr = ($view_mode !== 'cm') ? 'disabled="true"' : '';
                    ?>
                        <button type="button" class="chip-btn" data-value="<?= $value ?>" id="<?= $value ?>" data-state="<?= $state ?>" <?= $disabledAttr ?>>
                            <span class="btn-text"><?= $label ?></span>
                        </button>
                    <?php endforeach; ?>
                </div>
                  
                <?= $onMap ? '<br>' : '' ?>
            </span>
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
               <label id="dont_unloadCheckboxArea" class="checkbox-group">
               <input <?php echo $unload; ?> type="checkbox" id="dont_unload"> Disable Unload
               </label>
               <label id="hide_cellsCheckboxArea" class="checkbox-group">
               <input <?php echo $hide_cells; ?> type="checkbox" id="hide_cells"> Hide Cells
               </label>
               <label id="random_colorCheckboxArea" class="checkbox-group">
               <input type="checkbox" id="random_color" <?php echo $random_color; ?>> Randomize Colors
               </label>
               <!--
               <label class="checkbox-group">
               <input type="checkbox" id="perfectSurroOnly" <?php echo $perfectSurroOnly; ?>> Exact Location Only
               </label>
                -->
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
    setupCustomDropdown('plmn', 'Enter Custom PLMN:');
    setupCustomDropdown('icon_size', 'Enter Custom Icon Size:');
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