<?php ?> <script>
$(function(){
  <?php if(isMobile()) {?>
  // Evidence
  $('#ev_a').keyup(function() {  if ($(this).val().length == 0) {$('#ev_b').hide();} else {$('#ev_b').show();}}).keyup();
  $('#ev_b').keyup(function() {  if ($(this).val().length == 0) {$('#ev_c').hide();} else {$('#ev_c').show();}}).keyup();

  // Extras
  $('#ex_a').keyup(function() {  if ($(this).val().length == 0) {$('#ex_b').hide();} else {$('#ex_b').show();}}).keyup();
  $('#ex_b').keyup(function() {  if ($(this).val().length == 0) {$('#ex_c').hide();} else {$('#ex_c').show();}}).keyup();
  $('#ex_c').keyup(function() {  if ($(this).val().length == 0) {$('#ex_d').hide();} else {$('#ex_d').show();}}).keyup();
  $('#ex_d').keyup(function() {  if ($(this).val().length == 0) {$('#ex_e').hide();} else {$('#ex_e').show();}}).keyup();
  $('#ex_e').keyup(function() {  if ($(this).val().length == 0) {$('#ex_f').hide();} else {$('#ex_f').show();}}).keyup();

  // Photos
  $('#ph_a').keyup(function() {  if ($(this).val().length == 0) {$('#ph_b').hide();} else {$('#ph_b').show();}}).keyup();
  $('#ph_b').keyup(function() {  if ($(this).val().length == 0) {$('#ph_c').hide();} else {$('#ph_c').show();}}).keyup();
  $('#ph_c').keyup(function() {  if ($(this).val().length == 0) {$('#ph_d').hide();} else {$('#ph_d').show();}}).keyup();
  $('#ph_d').keyup(function() {  if ($(this).val().length == 0) {$('#ph_e').hide();} else {$('#ph_e').show();}}).keyup();
  $('#ph_e').keyup(function() {  if ($(this).val().length == 0) {$('#ph_f').hide();} else {$('#ph_f').show();}}).keyup();

  // Street View URLs
  $('#sv_a').keyup(function() {  if ($(this).val().length == 0) {$('.sv_b').hide();} else {$('.sv_b').show();}}).keyup();
  $('#sv_b').keyup(function() {  if ($(this).val().length == 0) {$('.sv_c').hide();} else {$('.sv_c').show();}}).keyup();
  $('#sv_c').keyup(function() {  if ($(this).val().length == 0) {$('.sv_d').hide();} else {$('.sv_d').show();}}).keyup();
  $('#sv_d').keyup(function() {  if ($(this).val().length == 0) {$('.sv_e').hide();} else {$('.sv_e').show();}}).keyup();
  $('#sv_e').keyup(function() {  if ($(this).val().length == 0) {$('.sv_f').hide();} else {$('.sv_f').show();}}).keyup();
  <?php } ?>

  $(document).ready(function() { $('#NR_1, #NR_2, #NR_3').keyup(); });

  $('#NR_1, #NR_2, #NR_3').keyup(function() {
    if ($('#NR_1').val().length > 0 || $('#NR_2').val().length > 0 || $('#NR_3').val().length > 0) {
      $('#region_nr').attr('required', '');
    } else {
      $('#region_nr').removeAttr('required');
    }
  });

  $(document).ready(function() { $("#carrier").change()});

    $("#carrier").change(function() {
      var carrier = $(this).val();
      if (carrier === "T-Mobile") {
        $("#LTE_1").attr("placeholder", "2/66");
        $("#LTE_2").attr("placeholder", "12/71");
        $("#LTE_3").attr("placeholder", "41");
        $("#NR_1").attr("placeholder", "n71");
        $("#NR_2").attr("placeholder", "n41");
        $("#NR_3").attr("placeholder", "n25");
      } else {
        $("#LTE_1").attr("placeholder", "LTE_1");
        $("#LTE_2").attr("placeholder", "LTE_2");
        $("#LTE_3").attr("placeholder", "LTE_3");
        $("#NR_1").attr("placeholder", "NR_1");
        $("#NR_2").attr("placeholder", "NR_2");
        $("#NR_3").attr("placeholder", "NR_3");
      }
    });


  $('#sv_a').keyup(function() {  if ($(this).val().length == 0) {document.getElementsByName("sv_a_date")[0].removeAttribute('required');} else {document.getElementsByName("sv_a_date")[0].setAttribute('required', "");}}).keyup();
  $('#sv_b').keyup(function() {  if ($(this).val().length == 0) {document.getElementsByName("sv_b_date")[0].removeAttribute('required');} else {document.getElementsByName("sv_b_date")[0].setAttribute('required', "");}}).keyup();
  $('#sv_c').keyup(function() {  if ($(this).val().length == 0) {document.getElementsByName("sv_c_date")[0].removeAttribute('required');} else {document.getElementsByName("sv_c_date")[0].setAttribute('required', "");}}).keyup();
  $('#sv_d').keyup(function() {  if ($(this).val().length == 0) {document.getElementsByName("sv_d_date")[0].removeAttribute('required');} else {document.getElementsByName("sv_d_date")[0].setAttribute('required', "");}}).keyup();
  $('#sv_e').keyup(function() {  if ($(this).val().length == 0) {document.getElementsByName("sv_e_date")[0].removeAttribute('required');} else {document.getElementsByName("sv_e_date")[0].setAttribute('required', "");}}).keyup();
  $('#sv_f').keyup(function() {  if ($(this).val().length == 0) {document.getElementsByName("sv_f_date")[0].removeAttribute('required');} else {document.getElementsByName("sv_f_date")[0].setAttribute('required', "");}}).keyup();

   /*
  // Street View URLs
  var lte_2 = $('#lte_2').val();
  var lte_3 = $('#lte_3').val();
  var lte_4 = $('#lte_4').val();
  var lte_5 = $('#lte_5').val();
  var lte_6 = $('#lte_6').val();


  $('#lte_2').keyup(function() {  if ($('#lte_2').val() != content) {document.getElementsByName("")[0].setAttribute('required', "");
  $('#lte_3').keyup(function() {  if ($('#lte_3').val() != content) {document.getElementsByName("")[0].setAttribute('required', "");
  $('#lte_4').keyup(function() {  if ($('#lte_4').val() != content) {document.getElementsByName("")[0].setAttribute('required', "");
  $('#lte_5').keyup(function() {  if ($('#lte_5').val() != content) {document.getElementsByName("")[0].setAttribute('required', "");
  $('#lte_6').keyup(function() {  if ($('#lte_6').val() != content) {document.getElementsByName("")[0].setAttribute('required', "");

  $('#nr_1').keyup(function() {  if ($(this).val().length == 0) {$('.sv_f').hide();} else {$('.sv_f').show();}}).keyup();
  $('#nr_2').keyup(function() {  if ($(this).val().length == 0) {$('.sv_f').hide();} else {$('.sv_f').show();}}).keyup(); */

});
</script>
