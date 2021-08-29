<?php ?>
<script>
function changeFormAction() {
  document.getElementById("link01").removeAttribute("onclick");
  document.getElementById("link01").setAttribute('type', 'submit');

  document.getElementById("link02").removeAttribute("onclick");
  document.getElementById("link02").setAttribute('type', 'submit');

  document.getElementById("link03").removeAttribute("onclick");
  document.getElementById("link03").setAttribute('type', 'submit');

  document.getElementById("link04").removeAttribute("onclick");
  document.getElementById("link04").setAttribute('type', 'submit');

  document.getElementById("link06").removeAttribute("onclick");
  document.getElementById("link06").setAttribute('type', 'submit');

  document.getElementById("link07").removeAttribute("onclick");
  document.getElementById("link07").setAttribute('type', 'submit');

  document.getElementById("link08").removeAttribute("onclick");
  document.getElementById("link08").setAttribute('type', 'submit');

  document.getElementById("link09").removeAttribute("onclick");
  document.getElementById("link09").setAttribute('type', 'submit');

  document.getElementById("link10").removeAttribute("onclick");
  document.getElementById("link10").setAttribute('type', 'submit');
  
  document.getElementById("link11").removeAttribute("onclick");
  document.getElementById("link11").setAttribute('type', 'submit');
}
function changeF(redirect) {
  document.getElementById("form").setAttribute('action', redirect);
}

function redir(page) {
  window.location.href = page;
}
</script>
