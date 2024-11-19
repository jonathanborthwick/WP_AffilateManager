(function(global) {
    // Namespace
    var am = global.am || (global.am = {});

    am.validation = (function() {
        
        function isValidUrl(url) {
            return url.match(/^(https?:\/\/)?([a-zA-Z0-9-]+\.)+[a-zA-Z]{2,6}(\/.*)*$/);
        }

        // SETTINGS form Validation
        function validateSettingsForm(event) {
            const pageTypeSelect = document.getElementById('offer_not_found_page_type');
            const urlInput = document.getElementById('offer_not_found_url');
            const warningMessage = document.getElementById('offer-not-found-warning');

            if (pageTypeSelect.value === 'remote') {
                const url = urlInput.value.trim();
                if (!isValidUrl(url)) {
                    warningMessage.style.display = 'block';
                    urlInput.style.borderColor = 'red';
                    event.preventDefault(); // Prevent the form from being submitted
                } else {
                    warningMessage.style.display = 'none';
                    urlInput.style.borderColor = ''; // Reset border color
                }
            }
        }

        // SETTINGS form event listeners
        function setupSettingsListeners() {
            const pageTypeSelect = document.getElementById('offer_not_found_page_type');
            const urlInput = document.getElementById('offer_not_found_url');
            const form = document.getElementById('affiliate-settings-form');

            if (form) {
                // Validate before form submission
                form.addEventListener('submit', validateSettingsForm);

                // Hide warning when switching from 'remote' to 'local'
                pageTypeSelect.addEventListener('change', function () {
                    if (pageTypeSelect.value === 'local') {
                        const warningMessage = document.getElementById('offer-not-found-warning');
                        warningMessage.style.display = 'none';
                        urlInput.style.borderColor = ''; // Reset border color
                    }
                });
            }
        }

        // Public validation API
        return {
            settings: function(event) {
                validateSettingsForm(event);
            },
            categories: function(event) {
                // Placeholder for categories validation logic
                console.log("Validation for categories is not implemented yet.");
            },
            campaigns: function(event) {
                // Placeholder for campaigns validation logic
                console.log("Validation for campaigns is not implemented yet.");
            },
            init: function() {
                document.addEventListener('DOMContentLoaded', function() {
                    setupSettingsListeners();
                    // Do other section listeners when implemented
                });
            }
        };
    })();

})(this); // Pass 'this' for global scope reference 

// Eg use:
// am.validation.settings(someEvent);
