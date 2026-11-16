<?php
include __DIR__ . '/../../includes/functions/tower_types.php';

$filterFieldSuggestions = [
  'id', 'date_added', 'status', 'concealed', 'carrier', 'cellsite_type', 'old_cellsite_type',
  'region_lte', 'region_nr', 'id_pattern_match', 'sector_match',
  'other_user_map_primary', 'latitude', 'longitude', 'address', 'city', 'county', 'state', 'zip',
  'tags', 'site_id', 'notes', 'evidence_score', 'evidence_a', 'evidence_b', 'evidence_c',
  'permit_score', 'trails_match', 'equipment_matches_carrier', 'cellmapper_triangulation',
  'image_evidence', 'verified_by_visit', 'sector_split_match', 'only_reasonable_location',
  'archival_antenna_addition', 'carriers_ruled_out', 'alt_carriers_here', 'edit_date',
  'edit_username', 'edit_userid', 'cm_pin_distance', 'cm_pin_inverted', 'split_sector',
  'sector_configuration', 'special_setup', 'tac_check_date', 'created_by'
];
for ($index = 2; $index <= 9; $index++) $filterFieldSuggestions[] = "LTE_$index";
for ($index = 2; $index <= 6; $index++) $filterFieldSuggestions[] = "NR_$index";
for ($index = 1; $index <= 30; $index++) $filterFieldSuggestions[] = "PCI_$index";
foreach (['extra', 'photo'] as $prefix) {
  foreach (range('a', 'f') as $suffix) $filterFieldSuggestions[] = "{$prefix}_{$suffix}";
}
foreach (range('a', 'c') as $suffix) $filterFieldSuggestions[] = "aerial_{$suffix}";

$batchSizeValue = isset($_GET['limit']) && is_numeric($_GET['limit'])
  ? (int) $_GET['limit']
  : (int) $viewport_batch_size;
$generalTowerTypes = [
  'tower' => 'Tower',
  'rooftop' => 'Rooftop',
  'nature' => 'Nature',
  'stealth' => 'Stealth',
  'utility' => 'Utility',
  'other' => 'Other'
];
$stateButtons = [
  'has_street_view' => 'Street View',
  'has_evidence' => 'Evidence',
  'incomplete' => 'Incomplete',
  'other_user_map_primary' => 'Other map',
  'split_sector' => 'Split sector',
  'special_setup' => 'Special'
];
?>
<form id="mapFilterForm" class="header headerFloating map-filter-toolbar" autocomplete="off">
  <div class="formsContainerContainer">
    <div id="formsContainer">
      <button type="button" id="status_filter" class="chip-btn map-parent-chip" data-filter-name="status" data-include-value="verified" data-exclude-value="unverified" data-state="neutral" title="Verified / unverified">
        <span class="btn-text">Verified</span>
      </button>

      <select name="carrier" id="carrier" aria-label="Carrier" title="Carrier" data-auto-apply>
        <option value="">All carriers</option>
        <option value="T-Mobile">T-Mobile</option>
        <option value="Verizon">Verizon</option>
        <option value="ATT">AT&amp;T</option>
        <option value="Sprint">Sprint</option>
        <option value="Dish">Dish</option>
        <option value="!T-Mobile">Not T-Mobile</option>
        <option value="!Verizon">Not Verizon</option>
        <option value="!ATT">Not AT&amp;T</option>
        <option value="!Sprint">Not Sprint</option>
        <option value="!Dish">Not Dish</option>
        <option value="_custom_">Custom…</option>
      </select>

      <button type="button" id="concealed" class="chip-btn map-parent-chip" data-filter-name="concealed" data-state="neutral" title="Concealed / unconcealed">
        <span class="btn-text">Concealed</span>
      </button>

      <select name="cellsite_type" id="cellsite_type" aria-label="Tower or site type" title="Tower or site type" data-auto-apply>
        <option value="">All tower types</option>
        <?php foreach ($options as $category => $subcategories): ?>
          <optgroup label="<?= htmlspecialchars($category, ENT_QUOTES) ?>">
            <?php foreach ($subcategories as $towerType => $towerLabel): ?>
              <option value="<?= htmlspecialchars($towerType, ENT_QUOTES) ?>"><?= htmlspecialchars($towerLabel) ?></option>
            <?php endforeach; ?>
          </optgroup>
        <?php endforeach; ?>
        <optgroup label="General">
          <?php foreach ($generalTowerTypes as $towerType => $towerLabel): ?>
            <option value="<?= htmlspecialchars($towerType, ENT_QUOTES) ?>"><?= htmlspecialchars($towerLabel) ?></option>
          <?php endforeach; ?>
        </optgroup>
      </select>

      <button class="poly-btn" id="hamburger-menu" type="button" aria-expanded="false" aria-controls="hamburger-area">▼</button>

      <div id="hamburger-area" hidden>
        <div class="map-filter-row map-place-layout">
          <label>Place</label>
          <div class="map-place-fields">
            <div class="map-place-fields-one">
              <input name="lat" type="text" placeholder="Latitude (&gt;34)" aria-label="Latitude comparison">
              <input name="lon" type="text" placeholder="Longitude (&lt;-118)" aria-label="Longitude comparison">
              <input name="address" type="text" placeholder="Address" aria-label="Address">
            </div>
            <div class="map-place-fields-two">
              <input name="city" type="text" placeholder="City" aria-label="City">
              <input name="zip" type="text" inputmode="numeric" maxlength="10" placeholder="ZIP" aria-label="ZIP">
              <input name="state" type="text" placeholder="State" aria-label="State">
              <input name="county" type="text" placeholder="County" aria-label="County">
            </div>
          </div>
        </div>

        <div class="map-filter-row">
          <label>Network IDs</label>
          <div class="map-two-column-row">
            <input name="id" type="text" placeholder="LTE/NR/record ID or range" aria-label="LTE, NR, or record ID">
            <input name="cmgm_id" type="text" placeholder="CMGM IDs: 44,51,100-125" aria-label="CMGM ID, list, or range">
          </div>
        </div>
        <div class="map-filter-row">
          <span aria-hidden="true"></span>
          <div class="map-network-regions">
            <input name="region_lte" type="text" placeholder="LTE region" aria-label="LTE region">
            <input name="region_nr" type="text" placeholder="NR region" aria-label="NR region">
            <input name="pci" type="text" placeholder="PCI search" aria-label="PCI search">
            <input name="site_id" type="text" placeholder="Site ID" aria-label="Site ID">
          </div>
        </div>

        <div class="map-filter-row">
          <label>Date Filters</label>
          <div class="map-four-column-row">
            <input name="date" type="text" placeholder="Date Created" aria-label="Date created">
            <input name="edit_date_like" type="text" placeholder="Edit Date" aria-label="Edited date">
            <input name="time" type="text" placeholder="Hour (3, !11)" aria-label="Hour">
            <input name="month" type="text" placeholder="Month (6, !2)" inputmode="numeric" aria-label="Month">
          </div>
        </div>

        <div class="map-filter-row">
          <label>Other</label>
          <div class="map-three-column-row">
            <input name="tags" type="text" placeholder="Tags" aria-label="Tags; prefix ! to exclude">
            <input name="notes_like" type="text" placeholder="Notes" aria-label="Notes; prefix ! to exclude">
            <input name="edit_history_like" type="text" placeholder="Edit history contains…" aria-label="Edit history">
          </div>
        </div>
        <div class="map-file-search-row">
          <span aria-hidden="true"></span>
          <div class="map-file-fields">
            <select id="mapFileSearchMode" aria-label="Attachment search mode" title="Attachment search mode">
              <option value="filesearch">File contains</option>
              <option value="filesearchexclude">File does not contain</option>
              <option value="filesearchprefix">File starts with</option>
              <option value="filesearchprefixexclude">File does not start with</option>
              <option value="filesearchsuffix">File ends with</option>
              <option value="filesearchsuffixexclude">File does not end with</option>
            </select>
            <input id="mapFileSearchValue" type="text" placeholder="image/misc?" aria-label="image/misc?">
            <input name="username" type="text" placeholder="Created by?" aria-label="Created by?">
          </div>
        </div>

        <div class="chip-filter-container" id="chipContainer">
          <?php foreach ($stateButtons as $parameter => $label): ?>
            <button type="button" class="chip-btn map-state-chip" data-filter-name="<?= htmlspecialchars($parameter, ENT_QUOTES) ?>" data-state="neutral">
              <span class="btn-text"><?= htmlspecialchars($label) ?></span>
            </button>
          <?php endforeach; ?>
        </div>

        <div id="mapAdvancedFilterRows"></div>
        <div class="map-filter-actions">
          <select name="pin_style" aria-label="Pin style" title="Pin style" data-auto-apply>
            <option value="carrier" selected>Carrier-pin</option>
            <option value="status">Status-pin</option>
            <option value="concealed">Concealed-pin</option>
          </select>
          <select class="misc_cw" title="Set batch size" name="limit" id="request_batch_size" data-default-value="<?= (int) $viewport_batch_size ?>" data-auto-apply>
            <option style="display:none" value="<?= $batchSizeValue ?>" selected>Batch size: <?= $batchSizeValue ?></option>
            <option value="50">50</option>
            <option value="125">125</option>
            <option value="250">250</option>
            <option value="450">450</option>
            <option value="800">800</option>
            <option value="1500">1500</option>
            <option value="3000">3000</option>
            <option value="7500">7500</option>
            <option value="15000">15000</option>
            <option value="25000">25000</option>
            <option value="" disabled>--</option>
            <option value="_custom_">Custom batch size</option>
          </select>
          <input name="pin_size" type="number" min="8" max="80" inputmode="numeric" placeholder="Pin size (20)" aria-label="Pin size">
          <button class="poly-btn" id="addMapFilterRow" type="button">+ Filter</button>
          <button class="poly-btn" id="resetMapFilters" type="button">Reset filters</button>
        </div>
      </div>
    </div>
  </div>

  <datalist id="mapDbFilterFields">
    <?php foreach ($filterFieldSuggestions as $field): ?>
      <option value="<?= htmlspecialchars($field, ENT_QUOTES) ?>"></option>
    <?php endforeach; ?>
  </datalist>
</form>
