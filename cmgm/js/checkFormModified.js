function formWasModified() {
    let modified = false;
    [...document.querySelector('body > form').elements].forEach((a) => {
        if ((a.type == 'text' || a.type == 'textarea') && a.value !== a.defaultValue) {
            modified = true;
        } else if (a.type == 'checkbox' && a.checked !== a.defaultChecked) {
            modified = true;
        }
    });

    return modified;
}

// Add listener for page close
window.onbeforeunload = () => {
    if (formWasModified()) {
        return 'Your changes were not saved - are you sure you want to close?';
    }
};

// Disable listener for form submission
$(document).on('submit', 'form', function (event) {
    window.onbeforeunload = null;
});
