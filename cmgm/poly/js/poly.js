document.getElementById('carrierForm').addEventListener('submit', async function (e) {
  e.preventDefault();

  const formData = new FormData(this);

  try {
    const response = await fetch('web.php', {
      method: 'POST',
      body: formData,
    });

    if (!response.ok) {
      throw new Error('Data for that eNB with those cells were not found');
    }

    const data = await response.json();

    // Check if the response contains an error
    if (data.error) {
      alert(`${data.error}`);
      return; // Stop further execution
    }

    const iframe = document.getElementById('iframe');

    // Update the iframe source and display it
    iframe.src = data.URL + "&hideui=true";
    iframe.style.display = 'block';

    // Update the map link, make it visible, and set its href
    const mapLink = document.getElementById('mapLink');
    mapLink.href = data.URL;
    mapLink.style.display = 'inline';

  } catch (error) {
    alert(`Error: ${error.message}`);
  }
});
