$(function(){
  // Evidence
  $('#ev_a').keyup(function() {  if ($(this).val().length == 0) {$('#ev_b').hide();} else {$('#ev_b').show();}}).keyup();
  $('#ev_b').keyup(function() {  if ($(this).val().length == 0) {$('#ev_c').hide();} else {$('#ev_c').show();}}).keyup();

  // Extras
  $('#ex_a').keyup(function() {  if ($(this).val().length == 0) {$('#ex_b').hide();} else {$('#ex_b').show();}}).keyup();
  $('#ex_b').keyup(function() {  if ($(this).val().length == 0) {$('#ex_c').hide();} else {$('#ex_c').show();}}).keyup();
  $('#ex_c').keyup(function() {  if ($(this).val().length == 0) {$('#ex_d').hide();} else {$('#ex_d').show();}}).keyup();
  $('#ex_d').keyup(function() {  if ($(this).val().length == 0) {$('#ex_e').hide();} else {$('#ex_e').show();}}).keyup();
  $('#ex_e').keyup(function() {  if ($(this).val().length == 0) {$('#ex_f').hide();} else {$('#ex_f').show();}}).keyup();

  // Extras
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
});
