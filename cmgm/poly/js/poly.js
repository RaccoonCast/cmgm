// Handle URL update after request made
function handleUpdateUrl(formData) {

      // initialize new URLSearchParams
    let params = new URLSearchParams();

    let urlValues = {};
    formData.forEach((value, key) => {
        params.append(key, value);
    })

    const newQueryString = params.toString();

    const newURL = `${window.location.origin}${window.location.pathname}?${newQueryString}`;
    // Replace the current history state with the new URL
    history.replaceState(null, '', newURL);
}

function addRatChangeEventListener(ratSelect) {

    const eNBInput = ratSelect.parentElement.querySelector('.eNB');
    
    ratSelect.addEventListener('change', () => {
        if (ratSelect.value === 'NR') {
            eNBInput.placeholder = 'gNB';
        } else if (ratSelect.value === 'LTE') {
            eNBInput.placeholder = 'eNB';
        }
    });
}


// Handle RAT selection changes at startup
document.addEventListener('DOMContentLoaded', () => {

    const ratSelects = document.getElementsByClassName('rat');
    // const eNBInput = document.getELementsByClassName('eNB');

    [...ratSelects].forEach(el => {
        addRatChangeEventListener(el);
    });

    
});

// Add new forms dynamically
document.getElementById("addFormButton").addEventListener("click", function () {
    // Get the forms container
    const formsContainer = document.getElementById("formsContainer");

    // Get the first form as a template
    const originalForm = document.querySelector(".carrierForm");

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
        // Don't clear button text
        if (input.type == 'button') {
            return;
        }
        input.value = "";
    });

    // Keep carrier from previous form
    const prevForm = formsContainer.children[formsContainer.children.length-1];
    const prevCarrier = prevForm.querySelector('.plmn').value;

    newForm.querySelector('.plmn').value = prevCarrier;

    // Add event listener for rat change
    addRatChangeEventListener(newForm.querySelector('.rat'));

    // Append the new form to the container
    formsContainer.appendChild(newForm);
});

// Handle data submission to server
async function handleMakeRequest(_event) {
    // e.preventDefault();

    // console.log('called in submitButton')

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

    const forceNewResults = document.querySelector('#forceNewResults')?.value === "on";

    try {
        const response = await fetch('web.php' + (forceNewResults ? '?forceNewResults' : ''), {
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

        // Update URL with form info
        handleUpdateUrl(allData);

        const iframe = document.getElementById('iframe');
        iframe.src = data.URL + "&hideui=true";
        iframe.style.display = 'block';

        // const mapLink = document.getElementById('mapLink');
        // mapLink.href = data.URL;
        // mapLink.style.display = 'inline';

    } catch (error) {
        alert(`Error: ${error.message}`);
    }
};

// Listen for submit button pressed
document.getElementById("submitButton").addEventListener("click", async function (e) {
    e.preventDefault();
    await handleMakeRequest(e);
});

// Listen for enter button pressed
document.addEventListener('keypress', async (e) => {
    if (e.key == 'Enter' || e.keyCode == 13) {
        e.preventDefault();
        await handleMakeRequest(e);
    }
})