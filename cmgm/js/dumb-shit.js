function showDisplayValue(e) {
  var options = e.target.options,
      option = e.target.selectedOptions[0],
      i;

  // reset options
  for (i = 0; i < options.length; ++i) {
    options[i].innerText = options[i].value;
  }

  // change the selected option's text to its `data-display` attribute value
  option.innerText = option.getAttribute('data-display');
}

document.getElementById('pcismatch').addEventListener('change', showDisplayValue, false);
document.getElementById('idpatternmatch').addEventListener('change', showDisplayValue, false);
document.getElementById('sectorsmatch').addEventListener('change', showDisplayValue, false);
document.getElementById('primaryalreadylocated').addEventListener('change', showDisplayValue, false);

$(document).ready(function() {
  // Store the original placeholder text
  var originalPlaceholder = $('input[name="sv_a_date"]').attr('placeholder');

  $('input[name^="sv_"][name$="_date"]').on('click', function() {
    var name = $(this).attr('name');
    if (name.match(/^sv_[a-f]_date$/)) {
      $(this).attr('placeholder', 'MM/YY');
    }
  });

  $('input[name^="sv_"][name$="_date"]').on('focusout', function() {
    $(this).attr('placeholder', originalPlaceholder);
  });
});

// Don't allow double submission of form
if (new URLSearchParams(window.location.search).get('new') != null) {
  document.querySelector('form').addEventListener('submit', () => {
    document.querySelector("body > form > input").onclick = (e) => {
    e.preventDefault();
    return false;
    }
  })
}