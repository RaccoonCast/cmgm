<?php ?>
<script>
function changeFormAction() {
  // Select all buttons with the common class, e.g., "cmgm-btn"
  const buttons = document.querySelectorAll('.usr_btns');

  buttons.forEach(button => {
    button.removeAttribute('onclick');  // remove the inline onclick handler
    button.setAttribute('type', 'submit');  // change type to submit
  });
}

function redir(page) {
  window.location.href = page;
}
</script>
