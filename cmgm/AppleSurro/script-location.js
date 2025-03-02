const surro = require('surro');

// Mobile Country Code (MCC), Mobile Network Code (MNC), Cell ID (CID), and Location Area Code (LAC)
const mcc = process.argv[2]; // Example MCC
const mnc = process.argv[3]; // Example MNC
const cid = process.argv[4]; // Example CID
const lac = process.argv[5]; // Example LAC

// Get surrounding cells
let output = [];
surro.getCellLocation(mcc, mnc, cid, lac)
  .then(cellResponse => {
    if (cellResponse.success) {
      const cell = cellResponse.data;
      console.log(
        // Return data in the same format as Google Geolocation API
        // This ensures conformity across both providers
        JSON.stringify(
          {
            location: {
              lat: cell.lat,
              lng: cell.lon,
            },
            accuracy: cell.range
          }
        )
      )
    } else {
      console.error('API returned error:', cellResponse.error);
    }
  })
  .catch(error => {
    console.error('Error getting surrounding cells:', error);
  });
