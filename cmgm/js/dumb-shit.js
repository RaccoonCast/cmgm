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
