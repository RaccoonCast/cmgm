<div id="enbID">
  <p>eNB ID:  <a style="white-space: nowrap; font-size: 14px;" href="#" id="extraENBID" name="id" onclick="myFunction();">Add Multiple</a></p>
</div>
<input
  class="ci inlineblock" maxlength="7" id="LTE_1" placeholder="LTE_1" name="LTE_1"><span style="display: none;" id="multipleIds"><input
  class="ci inlineblock" maxlength="7" id="LTE_2" placeholder="LTE_2" name="LTE_2"><input
  class="ci inlineblock" maxlength="7" id="LTE_3" placeholder="LTE_3" name="LTE_3"><input
  class="ci inlineblock" maxlength="7" id="LTE_4" placeholder="LTE_4" name="LTE_4"><input
  class="ci inlineblock" maxlength="7" id="LTE_5" placeholder="LTE_5" name="LTE_5"><input
  class="ci inlineblock" maxlength="7" id="LTE_6" placeholder="LTE_6" name="LTE_6"><input
  class="ci inlineblock" maxlength="8" id="NR_1"  placeholder="NR_1" name="NR_1"><input
  class="ci inlineblock" maxlength="8" id="NR_2"  placeholder="NR_2" name="NR_2"><br>

<input type="checkbox" id="pci_match" name="pci_match" value="true">
<label for="pci_match">PCIs match on all IDs</label><br>
<input type="checkbox" id="other_user_map_primary" name="other_user_map_primary" value="true">
<label for="other_user_map_primary">Other user located primary ID</label><br>
<input type="checkbox" id="id_pattern_match" name="id_pattern_match" value="true">
<label for="id_pattern_match">ID pattern on all IDs</label><br>
<input style="margin-bottom: 12px;" type="checkbox" id="sector_match" name="sector_match" value="true">
<label for="sector_match">Sector match on all IDs</label>
</span>
<script>
function myFunction() {
document.getElementById("multipleIds").removeAttribute('style');
document.getElementById("extraENBID").setAttribute('style', 'display: none');
document.getElementById("LTE_1").setAttribute('class', 'ci inlineblock trim-id-width');
document.getElementById("LTE_2").setAttribute('class', 'ci inlineblock trim-id-width');
document.getElementById("LTE_3").setAttribute('class', 'ci inlineblock trim-id-width');
document.getElementById("LTE_4").setAttribute('class', 'ci inlineblock trim-id-width');
document.getElementById("LTE_5").setAttribute('class', 'ci inlineblock trim-id-width');
document.getElementById("LTE_6").setAttribute('class', 'ci inlineblock trim-id-width');
document.getElementById("NR_1").setAttribute('class', 'ci inlineblock trim-id-width');
document.getElementById("NR_2").setAttribute('class', 'ci inlineblock trim-id-width');
}
</script>
