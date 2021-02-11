<div id="enbID">
  <p>eNB ID:  <a style="white-space: nowrap; font-size: 14px;" href="#" id="extraENBID" name="id" onclick="myFunction();">Add Multiple</a></p>
</div>
<p style="display: none;" id="LTE_LABEL">LTE IDs: </p>
<textarea class="custominput ids inlineBlock" id="LTE_1" rows="1" cols="30" maxlength="7" placeholder="LTE_1" name="LTE_1"></textarea><span style="display: none;" id="multipleIds"><textarea class="custominput ids inlineBlock" id="LTE_2" rows="1" cols="30" maxlength="7" placeholder="LTE_2" name="LTE_2"></textarea><textarea class="custominput ids inlineBlock" id="LTE_3" rows="1" cols="30" maxlength="7" placeholder="LTE_3" name="LTE_3"></textarea><textarea class="custominput ids inlineBlock" id="LTE_4" rows="1" cols="30" maxlength="7" placeholder="LTE_4" name="LTE_4"></textarea><textarea class="custominput ids inlineBlock" id="LTE_5" rows="1" cols="30" maxlength="7" placeholder="LTE_5" name="LTE_5"></textarea><textarea class="custominput ids inlineBlock" id="LTE_6" rows="1" cols="30" maxlength="7" placeholder="LTE_6" name="LTE_6"></textarea>
<p style="display: none;" id="NR_LABEL">NR IDs: </p>
<textarea class="custominput ids inlineBlock" id="NR_1" rows="1" cols="30" maxlength="7" placeholder="" name="NR_1"></textarea><textarea class="custominput ids inlineBlock" id="NR_2" rows="1" cols="30" maxlength="7" placeholder="" name="NR_2"></textarea><br>
<input type="checkbox" id="pci_match" name="pci_match" value="true">
<label for="pci_match">PCIs match on all IDs</label><br>
<input type="checkbox" id="id_pattern_match" name="id_pattern_match" value="true">
<label for="id_pattern_match">ID pattern on all IDs</label><br>
<input style="margin-bottom: 13px;" type="checkbox" id="sector_match" name="sector_match" value="true">
<label for="sector_match">Sector match on all IDs</label>
</span>
<?php if(isMobile()){
  ?>
  <script>
  function myFunction() {
  document.getElementById("multipleIds").removeAttribute('style');
  document.getElementById("LTE_LABEL").removeAttribute('style');
  document.getElementById("NR_LABEL").removeAttribute('style');

  document.getElementById("enbID").setAttribute('style', 'display: none');
  document.getElementById("LTE_1").setAttribute('style', 'width: 50%!important;');
  document.getElementById("LTE_2").setAttribute('style', 'width: 50%!important;');
  document.getElementById("LTE_3").setAttribute('style', 'width: 50%!important;');
  document.getElementById("LTE_4").setAttribute('style', 'width: 50%!important;');
  document.getElementById("LTE_5").setAttribute('style', 'width: 50%!important;');
  document.getElementById("LTE_6").setAttribute('style', 'width: 50%!important;');
  document.getElementById("NR_1").setAttribute('style', 'width: 50%!important;');
  document.getElementById("NR_2").setAttribute('style', 'width: 50%!important;');
  }
  </script>
  <?php
} else {
  ?>
  <script>
  function myFunction() {
  document.getElementById("multipleIds").removeAttribute('style');
  document.getElementById("LTE_LABEL").removeAttribute('style');
  document.getElementById("NR_LABEL").removeAttribute('style');

  document.getElementById("enbID").setAttribute('style', 'display: none');
  document.getElementById("LTE_1").setAttribute('style', 'width: 5.1%!important;');
  document.getElementById("LTE_2").setAttribute('style', 'width: 5.1%!important;');
  document.getElementById("LTE_3").setAttribute('style', 'width: 5.1%!important;');
  document.getElementById("LTE_4").setAttribute('style', 'width: 5.1%!important;');
  document.getElementById("LTE_5").setAttribute('style', 'width: 5.1%!important;');
  document.getElementById("LTE_6").setAttribute('style', 'width: 5.1%!important;');
  document.getElementById("NR_1").setAttribute('style', 'width: 5.1%!important;');
  document.getElementById("NR_2").setAttribute('style', 'width: 5.1%!important;');
  }
  </script> <?php
}
