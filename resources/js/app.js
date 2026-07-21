/**
 * Main Frontend Entrypoint for MRM System (Vite)
 */

import { escapeHtml, setText, getCsrfToken } from './utils/dom.js';
import { fetchJson } from './utils/http.js';
import { showToast, openModal, closeModal } from './utils/ui.js';
import { initMachinePassport } from './modules/machine-passport.js';

// Expose minimal utilities to global window for inline Blade event compatibility
window.escapeHtml = escapeHtml;
window.setText = setText;
window.showToast = showToast;
window.openModal = openModal;
window.closeModal = closeModal;
window.fetchJson = fetchJson;

// Initialize domain modules on DOMContentLoaded
document.addEventListener('DOMContentLoaded', () => {
    // Machine Passport Module initialization (if passport container exists)
    if (document.getElementById('panel-spareparts') || document.body.getAttribute('data-machine-code')) {
        initMachinePassport();
    }
});
