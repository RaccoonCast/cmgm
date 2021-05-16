<br>
    <!-- How many other carriers have equipment here too? -->
    <input name="alt_carriers_here" type="range" value="0" min="0" max="3" oninput="this.nextElementSibling.value = this.value">
    <output>0</output><label> other carrier(s) have antenna(s) here too</label><br>

    <!-- How many recognizable antenna upgrades can be seen -->
    <input name="archival_antenna_addition" type="range" value="0" min="0" max="3" step="1" oninput="this.nextElementSibling.value = this.value">
    <output>0</output><label> expected antennas upgrades can be seen for specified carrier</label><br><p></p>

    <!-- Permit matches carrier -->
    <input name="permit_score" type="range" value="0" min="0" max="100" step="10" oninput="this.nextElementSibling.value = this.value">
    <output>0</output><label>% sure that the permit/document the carrier at this address.</label><br>

    <!-- Trails match? -->
    <input name="trails_match" type="range" value="0" min="0" max="100" step="10" oninput="this.nextElementSibling.value = this.value">
    <output>0</output><label>% sure that trails match suspected location with the suspected carrier</label><br>

    <!-- Trails rule other carriers? -->
    <input name="carriers_dont_trail_match" type="range" value="0" min="0" step="1" max="3" oninput="this.nextElementSibling.value = this.value">
    <output>0</output><label> carrier(s) that CellMapper data/trails rules out.</label><br>

    <!-- Antennas match carrier -->
    <input name="antennas_match_carrier" type="range" value="0" min="0" step="10" max="100" oninput="this.nextElementSibling.value = this.value">
    <output>0</output><label>% sure that antennas look like suspected carrier</label><br>

    <!-- CellMapper triangulates near the location -->
    <input name="cellmapper_triangulation" type="range" value="0" min="0" step="10" max="100" oninput="this.nextElementSibling.value = this.value">
    <output>0</output><label>% sure that cellmapper <abbr title="Higher percent - triangulated cellsite is really close&#013;Lower percent  - triangulated cellsite is somewhat close">triangulates</abbr> close to the suspected location</label><br>

    <!-- A sticker/plate/sign/etc visible on the site has carrier name/contact information -->
    <input name="image_evidence" type="range" value="0" min="0" step="10" max="100" step="20" oninput="this.nextElementSibling.value = this.value">
    <output>0</output><label>% sure that identifier at the site with carrier name/info</label><br>

    <!-- The cellsite was visited, and was verified -->
    <input name="verified_by_visit" type="range" value="0" min="0" step="10" max="100" step="10" oninput="this.nextElementSibling.value = this.value">
    <output>0</output><label>% sure that location is accurate from on-site testing</label><br>

    <!-- Sector's split apart where the site is? -->
    <input name="sector_split_match" type="range" value="0" min="0" step="10" max="100" step="10" oninput="this.nextElementSibling.value = this.value">
    <output>0</output><label>% sure that sectors split apart at cell site location</label><br>

    <!-- Only possible location -->
    <input name="only_reasonable_location" type="range" value="0" min="0" step="10" max="100" step="10" oninput="this.nextElementSibling.value = this.value">
    <output>0</output><label>% sure that this is the only possible location for the cell site</label><br>
