/**
 * Format cell information
 * @param {*} data
 * @returns
 */
function formatCellInfo(data, useFullCellId = false) {
  const eNBs = Object.keys(data);
  const multipleENBs = eNBs.length > 1;
  const result = [];

  for (const eNB of eNBs) {
    const cells = data[eNB];
    for (const cellId in cells) {
      const provider = cells[cellId].provider;
      const providerClean = provider.replaceAll('Cache - ', '')
      const providerIsCached = (providerClean !== provider)
      const providerIsExactLocation = cells[cellId].is_exact_location === true;

      let gcid = cells[cellId].cellId;
      const reach = cells[cellId].reach ?? '';
      const score = cells[cellId].score ?? '';

      const dateAndTime = new Date(cells[cellId].date) ?? '';
      const date = dateAndTime.toLocaleDateString();  // e.g. "5/4/2026"
      const time = dateAndTime.toLocaleTimeString();  // e.g. "10:32:15 AM"
      const tacLabel = cells[cellId]?.tac ? ` ${cells[cellId].tac}` : ''
      const perfectSurroLabel = cells[cellId]?.tac ? ` ${cells[cellId].tac}` : ''
      const providerCachedSymbol = providerIsCached ? '<span style="vertical-align: super; font-size: small;">*</span>' : '';
      const scoreOrExactLocationLabel = providerIsExactLocation ? '<span style="font-size: small;">★</span>' : score;
      const reachLabel = providerIsExactLocation ? '' : reach;
      const id = useFullCellId ? gcid : cellId;
      const idContent = multipleENBs ? `${eNB}-${id}` : `${id}`;
      const idLabel = multipleENBs ? `eNBs` : `Cells`;
      const label = `<tr><td class="cell-label">${idContent}</td><td>${providerClean}${providerCachedSymbol}</td><td>${tacLabel}</td><td title="${time}">${date}</td><td>${reachLabel}</td><td>${scoreOrExactLocationLabel}</td></tr>`;
      result.push(label);
    }
  }

  return result;
}

let currentData;

async function addInfoData(iframe, returnData, redraw = false) {
  if (window.latestData) {
    returnData = window.latestData;
    console.log("Newer data found, using that instead!");
  }

  if (returnData == null) {
    console.log("Return data had nothing, likely wasn't called with poly");
    return;
  }

  // Do not use full cell ID by default, but allow changing it later
  let useFullCellId = false;

  // Get response and parse from base64
  const data = JSON.parse(atob(returnData));
  const multipleENBs = Object.keys(data).length > 1;
  const idLabel = multipleENBs ? 'eNBs' : 'Cells';
  
  // Parse response into format for popup
  const infoData = formatCellInfo(data, useFullCellId);

  let content = iframe.contentDocument.querySelector(
    "#polyInfoButton_content"
  );

  // Make sure that iframe basic buttons have loaded, before we try to place anything in them
  let tries = 0;
  if (content == null) {
    while (content === null && tries < 250) {
      // console.log('50ms wait, starting... now!')
      await new Promise(r => setTimeout(r, 50));
      content = iframe.contentDocument.querySelector("#polyInfoButton_content");
      tries++;
    }
  }

  const button = iframe?.contentDocument?.querySelector("#polyInfoButton");

  if ((button == null || button == undefined) || (content == null || content == undefined)) {
    console.log('Poly info button not enabled or not working, skip adding');
    return;
  }

  // console.log('list data:', list);
  content.innerHTML = `<table><thead><th>${idLabel}</th><th>Provider</th><th>TAC</th><th>Date</th><th>Reach</th><th>Score</th></thead><tbody>${infoData.join("")}</tbody></table>`;
  // console.log('wrote html', content.innerHTML);

  button.onclick = () => {
    // Draw popup beneath button
    const rect = button.getBoundingClientRect();
    content.style.left = `${rect.left + window.scrollX}px`;
    content.style.top = `${rect.bottom + window.scrollY}px`;

    content.classList.toggle("show");
  };

  // Close popup when map is clicked elsewhere
  iframe.contentDocument.addEventListener("click", (e) => {
    if (!button.contains(e.target) && !content.contains(e.target)) {
      // content.classList.remove("show");
    }
  });

  // When clicking inside the popup, change Sector ID to Cell ID (and vice versa)
  content.addEventListener("click", (e) => {
    if (!data || !e.target.classList.contains('cell-label')) {
      return;
    }

    // Toggle UFCID
    useFullCellId = !useFullCellId;
    
    const infoData = formatCellInfo(data, useFullCellId);
    content.innerHTML = `<table><thead><th>${idLabel}</th><th>Provider</th><th>TAC</th><th>Date</th><th>Reach</th><th>Score</th></thead><tbody>${infoData.join("")}</tbody></table>`;
  });

}
