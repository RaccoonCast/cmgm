<?php ?>
<script>
function changeFormAction() {
  document.getElementById("form").removeAttribute("onclick");
  document.getElementById("form").setAttribute('type', 'submit');

  document.getElementById("edit").removeAttribute("onclick");
  document.getElementById("edit").setAttribute('type', 'submit');

  document.getElementById("map").removeAttribute("onclick");
  document.getElementById("map").setAttribute('type', 'submit');

  document.getElementById("cellmapper").removeAttribute("onclick");
  document.getElementById("cellmapper").setAttribute('type', 'submit');

  document.getElementById("streetview").removeAttribute("onclick");
  document.getElementById("streetview").setAttribute('type', 'submit');

  document.getElementById("maps").removeAttribute("onclick");
  document.getElementById("maps").setAttribute('type', 'submit');

  document.getElementById("birdseye").removeAttribute("onclick");
  document.getElementById("birdseye").setAttribute('type', 'submit');

  document.getElementById("search").removeAttribute("onclick");
  document.getElementById("search").setAttribute('type', 'submit');

  document.getElementById("lart2150").removeAttribute("onclick");
  document.getElementById("lart2150").setAttribute('type', 'submit');

  document.getElementById("antennasearch").removeAttribute("onclick");
  document.getElementById("antennasearch").setAttribute('type', 'submit');

  document.getElementById("maprad").removeAttribute("onclick");
  document.getElementById("maprad").setAttribute('type', 'submit');

  document.getElementById("opencid").removeAttribute("onclick");
  document.getElementById("opencid").setAttribute('type', 'submit');

  document.getElementById("lookaround").removeAttribute("onclick");
  document.getElementById("lookaround").setAttribute('type', 'submit');
}

function redir(page) {
  window.location.href = page;
}
</script>
