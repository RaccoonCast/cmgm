<?php ?>
<script>
function changeFormAction() {
  document.getElementById("rerunData").setAttribute('value', 'true');
  document.getElementById("link01").removeAttribute("onclick");
  document.getElementById("link02").removeAttribute("onclick");
  document.getElementById("link03").removeAttribute("onclick");
  document.getElementById("link04").removeAttribute("onclick");
  document.getElementById("link06").removeAttribute("onclick");
  document.getElementById("link07").removeAttribute("onclick");
  document.getElementById("link08").removeAttribute("onclick");
  document.getElementById("link09").removeAttribute("onclick");
  document.getElementById("link10").removeAttribute("onclick");
  document.getElementById("link11").removeAttribute("onclick");
}
function changeF(redirect,page) {
  document.getElementById("form").setAttribute('action', redirect);
}

function redir(page) {
  window.location.href = page;
}
</script>
