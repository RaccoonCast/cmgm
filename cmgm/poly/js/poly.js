// Handle individual form submission
document.getElementById('carrierForm').addEventListener('submit', async function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    // Create a URLSearchParams object from formData
    const params = new URLSearchParams();
    formData.forEach((value, key) => {
        params.set(key, value); // Add each form value to the URL parameters
    });

    // Update the URL without reloading the page
    const newUrl = `${window.location.pathname}?${params.toString()}`;
    history.pushState({}, '', newUrl);

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


    } catch (error) {
        alert(`Error: ${error.message}`);
    }
});

// Handle RAT selection changes
document.addEventListener('DOMContentLoaded', () => {
    const ratSelect = document.getElementById('rat');
    const eNBInput = document.getElementById('eNB');

    ratSelect.addEventListener('change', () => {
        if (ratSelect.value === 'NR') {
            eNBInput.placeholder = 'gNB';
        } else if (ratSelect.value === 'LTE') {
            eNBInput.placeholder = 'eNB';
        }
    });
});

// Add new forms dynamically
document.getElementById("addFormButton").addEventListener("click", function () {
    // Get the forms container
    const formsContainer = document.getElementById("formsContainer");

    // Get the first form as a template
    const originalForm = document.querySelector("#carrierForm");

    // Clone the form
    const newForm = originalForm.cloneNode(true);

    // Generate a unique identifier
    const formIndex = formsContainer.children.length;

    // Update IDs and names in the cloned form
    Array.from(newForm.elements).forEach((element) => {
        if (element.id) {
            element.id = element.id + "_" + formIndex;
        }
        if (element.name) {
            element.name = element.name + "_" + formIndex;
        }
    });

    // Clear input values (except for selects)
    newForm.querySelectorAll("input").forEach((input) => {
        input.value = "";
    });

    // Append the new form to the container
    formsContainer.appendChild(newForm);
});

// Handle submission for all forms with a single button
document.getElementById("submitButton").addEventListener("click", async function (e) {
    e.preventDefault();

    const forms = document.querySelectorAll("#formsContainer form");
    const allData = new FormData();

    // Combine data from all forms
    forms.forEach((form, index) => {
        Array.from(form.elements).forEach((element) => {
            if (element.name && element.value) {
                allData.append(`${element.name}`, element.value);
            }
        });
    });

    try {
        const response = await fetch('web.php', {
            method: 'POST',
            body: allData,
        });

        if (!response.ok) {
            throw new Error('Failed to submit data');
        }

        const data = await response.json();

        // Handle response as needed
        if (data.error) {
            alert(`${data.error}`);
            return;
        }

        const iframe = document.getElementById('iframe');
        iframe.src = data.URL + "&hideui=true";
        iframe.style.display = 'block';

        // const mapLink = document.getElementById('mapLink');
        // mapLink.href = data.URL;
        // mapLink.style.display = 'inline';

    } catch (error) {
        alert(`Error: ${error.message}`);
    }
});
