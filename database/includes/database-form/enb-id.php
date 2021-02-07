<div id="enbID">
  <p>eNB ID:  <a style="white-space: nowrap; font-size: 14px;" href="#" id="extraENBID" name="id" onclick="myFunction();">Add Multiple</a></p>
</div>
<textarea class="custominput inlineBlock" id="enbIDbox1" rows="1" cols="30" maxlength="7" placeholder="" name="LTE_1"></textarea>
<span style="display: none;" id="multipleIds">
<textarea class="custominput inlineBlock" id="enbIDbox2" rows="1" cols="30" maxlength="7" placeholder="" name="LTE_2"></textarea><br>
<textarea class="custominput inlineBlock" id="enbIDbox3" rows="1" cols="30" maxlength="7" placeholder="" name="LTE_3"></textarea>
<textarea class="custominput inlineBlock" id="enbIDbox4" rows="1" cols="30" maxlength="7" placeholder="" name="LTE_4"></textarea><br>
<textarea class="custominput inlineBlock" id="enbIDbox5" rows="1" cols="30" maxlength="7" placeholder="" name="LTE_5"></textarea>
<textarea class="custominput inlineBlock" id="enbIDbox6" rows="1" cols="30" maxlength="7" placeholder="" name="LTE_6"></textarea>
</span>
<?php if(isMobile()){
  ?>
  <script>
  function myFunction() {
  var foo = document.getElementById("multipleIds");
  foo.removeAttribute('style');

  var doo1 = document.getElementById("enbIDbox1");
  doo1.setAttribute('style', 'width: 50%!important;');
  var doo2 = document.getElementById("enbIDbox2");
  doo2.setAttribute('style', 'width: 50%!important;');
  var doo3 = document.getElementById("enbIDbox3");
  doo3.setAttribute('style', 'width: 50%!important;');
  var doo4 = document.getElementById("enbIDbox4");
  doo4.setAttribute('style', 'width: 50%!important;');
  var doo5 = document.getElementById("enbIDbox5");
  doo5.setAttribute('style', 'width: 50%!important;');
  var doo6 = document.getElementById("enbIDbox6");
  doo6.setAttribute('style', 'width: 50%!important;');
  }
  </script>
  <?php
} else {
  ?>
  <script>
  function myFunction() {
  var foo = document.getElementById("multipleIds");
  foo.removeAttribute('style');

  var doo1 = document.getElementById("enbIDbox1");
  doo1.setAttribute('style', 'width: 15.75%!important;');
  var doo2 = document.getElementById("enbIDbox2");
  doo2.setAttribute('style', 'width: 15.75%!important;');
  var doo3 = document.getElementById("enbIDbox3");
  doo3.setAttribute('style', 'width: 15.75%!important;');
  var doo4 = document.getElementById("enbIDbox4");
  doo4.setAttribute('style', 'width: 15.75%!important;');
  var doo5 = document.getElementById("enbIDbox5");
  doo5.setAttribute('style', 'width: 15.75%!important;');
  var doo6 = document.getElementById("enbIDbox6");
  doo6.setAttribute('style', 'width: 15.75%!important;');
  }
  </script> <?php
}
