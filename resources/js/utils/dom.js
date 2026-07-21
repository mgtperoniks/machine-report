/**
 * DOM Utility Module for MRM System
 * Provides HTML sanitization, text setting, and DOM helpers.
 */

/**
 * Escapes special HTML characters to prevent XSS attacks when constructing dynamic HTML strings.
 * @param {string|number|null|undefined} str
 * @returns {string}
 */
export function escapeHtml(str) {
    if (str === null || str === undefined) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

/**
 * Safely sets plain text content of an element or element ID (preventing HTML injection).
 * @param {HTMLElement|string} target - DOM element or element ID string
 * @param {string} text - Plain text to insert
 */
export function setText(target, text) {
    const el = typeof target === 'string' ? document.getElementById(target) : target;
    if (el) {
        el.textContent = text ?? '';
    }
}

/**
 * Retrieves the CSRF token from meta tag or input field.
 * @returns {string}
 */
export function getCsrfToken() {
    const metaTag = document.querySelector('meta[name="csrf-token"]');
    if (metaTag) {
        return metaTag.getAttribute('content') || '';
    }
    const inputTag = document.querySelector('input[name="_token"]');
    return inputTag ? inputTag.value : '';
}
