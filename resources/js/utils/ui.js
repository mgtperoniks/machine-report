/**
 * UI Utility Module for MRM System
 * Provides standardized Toast Notifications and Modal Controls.
 */

import { setText } from './dom.js';

/**
 * Shows a toast notification.
 * @param {string} message 
 * @param {'success'|'error'|'info'} type 
 * @param {number} duration 
 */
export function showToast(message, type = 'success', duration = 3000) {
    let toast = document.getElementById('mrm-toast-notification');
    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'mrm-toast-notification';
        toast.className = 'fixed bottom-5 right-5 z-[9999] px-4 py-2.5 rounded-xl shadow-xl border text-xs font-bold transition-all duration-300 transform opacity-0 translate-y-2 flex items-center gap-2 pointer-events-none';
        document.body.appendChild(toast);
    }

    const isSuccess = type === 'success';
    const isError = type === 'error';

    toast.className = `fixed bottom-5 right-5 z-[9999] px-4 py-2.5 rounded-xl shadow-xl border text-xs font-bold transition-all duration-300 transform flex items-center gap-2 ${
        isSuccess ? 'bg-surface-container-lowest text-on-surface border-emerald-500/40' :
        isError ? 'bg-error-container text-on-error-container border-error/40' :
        'bg-surface-container text-on-surface border-outline-variant'
    }`;

    const icon = isSuccess ? 'check_circle' : isError ? 'error' : 'info';
    const iconColor = isSuccess ? 'text-emerald-500' : isError ? 'text-error' : 'text-primary';

    toast.innerHTML = `<span class="material-symbols-outlined ${iconColor} text-[18px]">${icon}</span><span>${message}</span>`;

    // Show toast
    requestAnimationFrame(() => {
        toast.classList.remove('opacity-0', 'translate-y-2', 'pointer-events-none');
        toast.classList.add('opacity-100', 'translate-y-0');
    });

    // Hide toast after duration
    if (toast.timeoutId) clearTimeout(toast.timeoutId);
    toast.timeoutId = setTimeout(() => {
        toast.classList.remove('opacity-100', 'translate-y-0');
        toast.classList.add('opacity-0', 'translate-y-2', 'pointer-events-none');
    }, duration);
}

/**
 * Opens a modal dialog by ID.
 * @param {string} modalId 
 */
export function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('hidden');
    }
}

/**
 * Closes a modal dialog by ID.
 * @param {string} modalId 
 */
export function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('hidden');
    }
}
