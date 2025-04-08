import './bootstrap';

// Initialize Bootstrap components
import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

// Add event listeners after DOM load
document.addEventListener('DOMContentLoaded', () => {
    // Enable tooltips
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    const tooltipList = [...tooltipTriggerList].map(el => new bootstrap.Tooltip(el));

    // Enable popovers
    const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]');
    const popoverList = [...popoverTriggerList].map(el => new bootstrap.Popover(el));
});

// Add any custom JS here
