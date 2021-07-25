<?php ?>
<script>
function changeFormAction() {
  document.getElementById("rerunData").setAttribute('value', 'true');
  document.getElementById("map").removeAttribute("onclick");
  document.getElementById("permits").removeAttribute("onclick");
  document.getElementById("cellmapper").removeAttribute("onclick");
  document.getElementById("beta").removeAttribute("onclick");
  document.getElementById("search").removeAttribute("onclick");
  document.getElementById("database").removeAttribute("onclick");
  document.getElementById("googlemaps").removeAttribute("onclick");
  document.getElementById("streetview").removeAttribute("onclick");
}
function changeF(redirect,page) {
  document.getElementById("form").setAttribute('action', redirect);
}

function redir(page) {
  window.location.href = page;
}
</script>
