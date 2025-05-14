const surro = require('surro');

// Mobile Country Code (MCC), Mobile Network Code (MNC), Cell ID (CID), and Location Area Code (LAC)
const mcc = process.argv[2]; // Example MCC
const mnc = process.argv[3]; // Example MNC
const cid = process.argv[4]; // Example CID
const lac = process.argv[5]; // Example LAC

// Get surrounding cells
let output = [];
surro.getSurroundingCells(mcc, mnc, cid, lac)
  .then(cells => {
	cells.data.forEach((cell) => {
		output.push(
			{ lat: cell.latitude, lon: cell.longitude, accuracy: cell.accuracy, eNB: cell.eNB, mcc: cell.mmc, mnc: cell.mnc, lac: cell.tacId }
	)})
    console.log(JSON.stringify(output));
  })
  .catch(error => {
    console.error('Error getting surrounding cells:', error);
  });
