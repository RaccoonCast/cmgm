const express = require('express');
const { getCellLocation } = require('surro');

const app = express();
app.use(express.json());

app.post('/geolocate', async (req, res) => {
    

    if (!req.body) {
        res.status(400).send('Bad request - missing body!');
        return;
    }

    const { cellTowers } = req.body;

    if (!cellTowers || !cellTowers[0]) {
        res.status(400).send('Bad request - missing tower!');
        return;
    }

    const firstTower = cellTowers[0];

    const { locationAreaCode: lac, cellId, mobileCountryCode: mcc, mobileNetworkCode: mnc, rat } = firstTower;

    if (!lac || !cellId || !mcc || !mcc || !rat) {
        console.log('Request missing essential item!');
        res.status(400).send('Request improperly formed! cellTowers does not contain required information');
        return;
    }

    console.log(`Processing request for ${mcc}-${mnc}, ${rat}, CID ${cellId}, TAC ${lac}`)

    // Send request to apple
    try {
        const cellInfo = await getCellLocation(parseInt(mcc), parseInt(mnc), parseInt(cellId), parseInt(lac), rat);

        if (cellInfo.success == false) {
            res.status(500).send({ ok: false, error: cellInfo.error });
            return;
        }

        // Format response data in ichanea/google format
        const responseData = {
            location: {
                lat: cellInfo.data.lat,
                lng: cellInfo.data.lon,
            },
            accuracy: cellInfo.data.range,
        };

        // we know it's real now so just send the data
        res.set('Content-Type', 'application/json');

        res.status(200).send(responseData);
        
        return;
    } catch (err) {
        res.status(500).send(`An unknown error occured: ${err.message}`);
        return;
    }
});

(async () => {
    app.listen(1234, '127.0.0.1', () => {
        console.log('Listening!');
    });
})();
