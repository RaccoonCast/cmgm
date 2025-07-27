/**
 * Format cell information
 * @param {*} data
 * @returns
 */
function formatCellInfo(data) {
  const eNBs = Object.keys(data);
  const multipleENBs = eNBs.length > 1;
  const result = [];

  for (const eNB of eNBs) {
    const cells = data[eNB];
    for (const cellId in cells) {
      const provider = cells[cellId].provider;
      const providerClean = provider.replaceAll('Cache - ', '')
      const providerIsCached = (providerClean !== provider)

      const date = new Date(cells[cellId].date)?.toLocaleString() ?? '';
      const tacLabel = cells[cellId]?.tac ? ` (TAC ${cells[cellId].tac})` : ''
      const providerCachedSymbol = providerIsCached ? '<span style="vertical-align: super; font-size: small;">*</span>' : '';
      const label = multipleENBs
        ? `<li title="${date}">${eNB}-${cellId}: ${providerClean}${tacLabel}${providerCachedSymbol}</li>`
        : `<li title="${date}">Cell ${cellId}: ${providerClean}${tacLabel}${providerCachedSymbol}</li>`;
      result.push(label);
    }
  }

  return result;
}

async function addInfoData(iframe, returnData, requestedByJs) {
  if (window.latestData) {
    returnData = window.latestData;
    console.log("Newer data found, using that instead!");
  }

  if (returnData == null) {
    console.log("Return data had nothing, likely wasn't called with poly");
    return;
  }

  // Get response and parse from base64
  const data = JSON.parse(atob(returnData));

  // Parse response into format for popup
  const infoData = formatCellInfo(data);

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
  content.innerHTML = `<ul>${infoData.join("")}</ul>`;
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
      content.classList.remove("show");
    }
  });
}
