(function(global) {
    // Namespace
    var am = global.am || (global.am = {});
    am.utils = (function(){
        var context = {};

        context.showTab = function (tabId) {
            // Hide all tab contents
            const tabContents = document.querySelectorAll('.tab-content');
            tabContents.forEach(content => content.classList.remove('active'));
        
            // Remove active class from all tabs
            const tabs = document.querySelectorAll('.tab');
            tabs.forEach(tab => tab.classList.remove('active'));
        
            // Show selected tab content
            const tabContent = document.getElementById(tabId);
            if (tabContent) {
                tabContent.classList.add('active');
            } else {
                console.error(`Tab content with ID "${tabId}" not found`);
            }
        
            // Add active class to the correct tab
            const activeTab = document.querySelector(`.tab[data-tab="${tabId}"]`);
            if (activeTab) {
                activeTab.classList.add('active');
            } else {
                console.error(`Tab with data-tab "${tabId}" not found`);
            }
        };

        return context;
    })();
})(this);