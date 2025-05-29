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
      const date = new Date(cells[cellId].date).toLocaleString();
      const label = multipleENBs
        ? `<li title="${date}">${eNB}-${cellId}: ${provider}</li>`
        : `<li title="${date}">Cell ${cellId}: ${provider}</li>`;
      result.push(label);
    }
  }

  return result;
}

function addInfoData(iframe, returnData, requestedByJs) {
  if (window.latestData) {
    returnData = window.latestData;
    console.log("Newer data found, using that instead!");
  }

  // Get response and parse from base64
  const data = JSON.parse(atob(returnData));

  // Parse response into format for popup
  const infoData = formatCellInfo(data);

  const button = iframe.contentDocument.querySelector("#polyInfoButton");
  const content = iframe.contentDocument.querySelector(
    "#polyInfoButton_content"
  );

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
