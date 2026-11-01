document.addEventListener('DOMContentLoaded', () => {
    const chipBtns = document.querySelectorAll('.chip-btn');
    const includeInput = document.getElementById('cm_includes');
    const hideInput = document.getElementById('cm_excludes');

    if (!includeInput || !hideInput) return;

    // Handle clicks
    chipBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            if (this.disabled) return;

            const currentState = this.getAttribute('data-state');
            
            // Cycle through the 3 states
            if (currentState === 'neutral') {
                this.setAttribute('data-state', 'include');
            } else if (currentState === 'include') {
                this.setAttribute('data-state', 'exclude');
            } else {
                this.setAttribute('data-state', 'neutral');
            }

            updateHiddenInputs();
            
            // Force the map and URL to update immediately since programmatic 
            // value changes do not trigger native change events
            clearAllMarkers();
            updateData(true); 
        });
    });

    function updateHiddenInputs() {
        const includes = [];
        const excludes = [];

        chipBtns.forEach(btn => {
            const state = btn.getAttribute('data-state');
            const val = btn.getAttribute('data-value');

            if (state === 'include') includes.push(val);
            if (state === 'exclude') excludes.push(val);
        });

        includeInput.value = includes.join(',');
        hideInput.value = excludes.join(',');
    }
});