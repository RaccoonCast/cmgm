<?php ?>
<script>
function changeFormAction() {
  document.getElementById("rerunData").setAttribute('value', 'true');
  document.getElementById("DB-Form").removeAttribute("onclick");
  document.getElementById("DB-Edit").removeAttribute("onclick");
  document.getElementById("DB-Map").removeAttribute("onclick");
  document.getElementById("Search").removeAttribute("onclick");
  document.getElementById("CellMapper").removeAttribute("onclick");
  document.getElementById("Beta").removeAttribute("onclick");
  document.getElementById("GoogleMaps").removeAttribute("onclick");
  document.getElementById("StreetView").removeAttribute("onclick");
  document.getElementById("Permits").removeAttribute("onclick");
  document.getElementById("AntennaSearch").removeAttribute("onclick");
}
function changeF(redirect,page) {
  document.getElementById("form").setAttribute('action', redirect);
}

function redir(page) {
  window.location.href = page;
}
</script>
