(() => {
  const form = document.getElementById('mapFilterForm');
  if (!form) return;

  const hamburgerButton = document.getElementById('hamburger-menu');
  const hamburgerArea = document.getElementById('hamburger-area');
  const advancedRows = document.getElementById('mapAdvancedFilterRows');
  const addRowButton = document.getElementById('addMapFilterRow');
  const resetButton = document.getElementById('resetMapFilters');
  const fileMode = document.getElementById('mapFileSearchMode');
  const fileValue = document.getElementById('mapFileSearchValue');
  const batchSize = document.getElementById('request_batch_size');
  const cmgmIdInput = form.elements.cmgm_id;
  const stateChips = [...form.querySelectorAll('[data-filter-name]')];
  let currentParams = new URLSearchParams(window.location.search);

  const contextParameters = new Set([
    'latitude', 'longitude', 'zoom', 'polygon', 'polygonlabels', 'skipPolyZoom',
    'marker_latitude', 'marker_longitude', 'showPolyLink', 'hidePolyForm', 'hideui',
    'title', 'back', 'next', 'q', 'cachebuster', 'useCoordCenter'
  ]);
  const fileParameters = [
    'filesearch', 'filesearchexclude', 'filesearchprefix',
    'filesearchprefixexclude', 'filesearchsuffix', 'filesearchsuffixexclude'
  ];
  const representedParameters = new Set(
    [...form.elements].map(element => element.name).filter(Boolean)
  );
  fileParameters.forEach(parameter => representedParameters.add(parameter));
  representedParameters.add('idlist');
  stateChips.forEach(chip => representedParameters.add(chip.dataset.filterName));

  function ensureSelectValue(select, value) {
    if (!select || value === null || value === '') return;
    if (![...select.options].some(option => option.value === value)) {
      select.add(new Option(value, value, true, true), select.options[select.options.length - 1]);
    }
    select.value = value;
  }

  function updateBatchLabel(value) {
    const normalized = String(value || batchSize.dataset.defaultValue);
    batchSize.options[0].value = normalized;
    batchSize.options[0].textContent = `Batch size: ${normalized}`;
    batchSize.selectedIndex = 0;
  }

  for (const element of form.elements) {
    if (!element.name || !currentParams.has(element.name)) continue;
    const value = currentParams.get(element.name) ?? '';
    if (element instanceof HTMLSelectElement) ensureSelectValue(element, value);
    else element.value = value;
  }
  if (!currentParams.has('cmgm_id') && currentParams.has('idlist')) {
    cmgmIdInput.value = currentParams.get('idlist') || '';
  }

  stateChips.forEach(chip => {
    const value = currentParams.get(chip.dataset.filterName);
    const includeValue = chip.dataset.includeValue || 'true';
    const excludeValue = chip.dataset.excludeValue || 'false';
    chip.dataset.state = value === includeValue ? 'include' : value === excludeValue ? 'exclude' : 'neutral';
  });
  updateBatchLabel(currentParams.get('limit') || batchSize.dataset.defaultValue);

  const configuredFileParameter = fileParameters.find(parameter => currentParams.has(parameter));
  if (configuredFileParameter) {
    fileMode.value = configuredFileParameter;
    fileValue.value = currentParams.get(configuredFileParameter) || '';
  }

  function decodeOperator(rawValue) {
    if (rawValue === 'NULL') return { operator: 'empty', value: '' };
    if (rawValue === '!NULL') return { operator: 'not-empty', value: '' };
    if (rawValue.startsWith('!')) return { operator: 'not', value: rawValue.slice(1) };
    if (rawValue.startsWith('>')) return { operator: 'min', value: rawValue.slice(1) };
    if (rawValue.startsWith('<')) return { operator: 'max', value: rawValue.slice(1) };
    return { operator: 'equals', value: rawValue };
  }

  function addAdvancedRow(field = '', rawValue = '') {
    const decoded = decodeOperator(String(rawValue));
    const row = document.createElement('div');
    row.className = 'map-advanced-filter-row';
    row.innerHTML = `
      <input class="map-advanced-field" type="text" list="mapDbFilterFields" placeholder="DB field" aria-label="Database field">
      <select class="map-advanced-operator" aria-label="Filter operator">
        <option value="equals">=</option>
        <option value="not">≠</option>
        <option value="min">≥</option>
        <option value="max">≤</option>
        <option value="empty">Empty</option>
        <option value="not-empty">Not empty</option>
      </select>
      <input class="map-advanced-value" type="text" placeholder="Value" aria-label="Filter value">
      <button class="poly-btn map-filter-remove" type="button" title="Remove filter" aria-label="Remove filter">×</button>`;
    row.querySelector('.map-advanced-field').value = field;
    row.querySelector('.map-advanced-operator').value = decoded.operator;
    row.querySelector('.map-advanced-value').value = decoded.value;
    const syncValueState = () => {
      const noValue = ['empty', 'not-empty'].includes(row.querySelector('.map-advanced-operator').value);
      row.querySelector('.map-advanced-value').disabled = noValue;
    };
    row.querySelector('.map-advanced-operator').addEventListener('change', syncValueState);
    row.querySelector('.map-filter-remove').addEventListener('click', () => {
      row.remove();
      applyFilters();
    });
    syncValueState();
    advancedRows.appendChild(row);
  }

  for (const [key, value] of currentParams) {
    if (contextParameters.has(key) || representedParameters.has(key)) continue;
    addAdvancedRow(key, value);
  }

  function setMoreOpen(open) {
    hamburgerArea.hidden = !open;
    hamburgerButton.setAttribute('aria-expanded', String(open));
    hamburgerButton.textContent = open ? '▲' : '▼';
    try { localStorage.setItem('cmgm-map-filters-open', open ? '1' : '0'); } catch (_) {}
  }

  setMoreOpen(false);
  hamburgerButton.addEventListener('click', () => setMoreOpen(hamburgerArea.hidden));
  addRowButton.addEventListener('click', () => addAdvancedRow());

  function baseParameters() {
    const next = new URLSearchParams();
    for (const [key, value] of currentParams) {
      if (contextParameters.has(key)) next.append(key, value);
    }
    if (typeof map !== 'undefined') {
      const center = map.getCenter();
      next.set('latitude', center.lat);
      next.set('longitude', normalizeLongitude(center.lng));
      next.set('zoom', map.getZoom());
      next.delete('useCoordCenter');
    }
    return next;
  }

  function encodeAdvancedValue(operator, value) {
    if (operator === 'empty') return 'NULL';
    if (operator === 'not-empty') return '!NULL';
    if (operator === 'not') return `!${value}`;
    if (operator === 'min') return `>${value}`;
    if (operator === 'max') return `<${value}`;
    return value;
  }

  async function applyFilters() {
    const next = baseParameters();
    for (const element of form.elements) {
      if (!element.name || element.disabled || element.closest('.map-advanced-filter-row')) continue;
      const value = element.value.trim();
      if (value && value !== '_custom_') next.set(element.name, value);
    }
    stateChips.forEach(chip => {
      if (chip.dataset.state === 'include') {
        next.set(chip.dataset.filterName, chip.dataset.includeValue || 'true');
      }
      if (chip.dataset.state === 'exclude') {
        next.set(chip.dataset.filterName, chip.dataset.excludeValue || 'false');
      }
    });
    if (fileValue.value.trim()) next.set(fileMode.value, fileValue.value.trim());
    for (const row of advancedRows.children) {
      const field = row.querySelector('.map-advanced-field').value.trim();
      const operator = row.querySelector('.map-advanced-operator').value;
      const value = row.querySelector('.map-advanced-value').value.trim();
      if (!field || (!value && !['empty', 'not-empty'].includes(operator))) continue;
      next.set(field, encodeAdvancedValue(operator, value));
    }
    if (!next.has('pin_style')) next.set('pin_style', 'carrier');
    currentParams = new URLSearchParams(next);

    if (typeof window.refreshMapFilters === 'function') {
      await window.refreshMapFilters(next);
    } else {
      history.replaceState({}, '', `${window.location.pathname}?${next.toString()}${window.location.hash}`);
    }
  }

  form.addEventListener('submit', event => {
    event.preventDefault();
    applyFilters();
  });

  form.querySelectorAll('[data-auto-apply]').forEach(element => {
    element.addEventListener('change', async () => {
      if (element.value === '_custom_') {
        const promptLabel = element.id === 'request_batch_size'
          ? 'Enter Custom Batch Size:'
          : 'Enter a custom carrier:';
        const custom = prompt(promptLabel);
        if (!custom) {
          element.selectedIndex = 0;
          return;
        }
        ensureSelectValue(element, custom);
      }
      if (element === batchSize) updateBatchLabel(element.value);
      await applyFilters();
    });
  });

  hamburgerArea.addEventListener('change', event => {
    const element = event.target;
    if (!(element instanceof HTMLInputElement || element instanceof HTMLSelectElement)) return;
    if (element.matches('[data-auto-apply]')) return;
    applyFilters();
  });

  let filterInputTimer = null;
  hamburgerArea.addEventListener('input', event => {
    const element = event.target;
    if (!(element instanceof HTMLInputElement)) return;
    if (element.closest('.map-advanced-filter-row')) return;
    clearTimeout(filterInputTimer);
    filterInputTimer = setTimeout(applyFilters, 450);
  });

  stateChips.forEach(chip => {
    chip.addEventListener('click', async () => {
      const currentState = chip.dataset.state;
      chip.dataset.state = currentState === 'neutral'
        ? 'include'
        : currentState === 'include' ? 'exclude' : 'neutral';
      await applyFilters();
    });
  });

  resetButton.addEventListener('click', async () => {
    for (const element of form.elements) {
      if (!element.name) continue;
      if (element instanceof HTMLSelectElement) element.selectedIndex = 0;
      else element.value = '';
    }
    stateChips.forEach(chip => { chip.dataset.state = 'neutral'; });
    advancedRows.replaceChildren();
    fileMode.value = 'filesearch';
    fileValue.value = '';
    updateBatchLabel(batchSize.dataset.defaultValue);
    const next = baseParameters();
    next.set('pin_style', 'carrier');
    currentParams = new URLSearchParams(next);
    if (typeof window.refreshMapFilters === 'function') await window.refreshMapFilters(next);
    else history.replaceState({}, '', `${window.location.pathname}?${next.toString()}${window.location.hash}`);
  });
})();
