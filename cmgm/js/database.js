function lte_1Reqd()
{
var ddl = document.getElementById("status");
var selectedValue = ddl.options[ddl.selectedIndex].value;
   if (selectedValue == "verified")
  {
   document.getElementById("LTE_1").setAttribute('required', "");
 } else {
   document.getElementById("LTE_1").removeAttribute('required');
 }
}
