/**
 * Standard HTTP/AJAX Fetch Helper Module for MRM System
 * Enforces consistent CSRF handling, JSON parsing, and unified error handling.
 */

import { getCsrfToken } from './dom.js';

/**
 * Sends a standardized fetch request expecting JSON.
 * @param {string} url 
 * @param {Object} options 
 * @returns {Promise<any>}
 */
export async function fetchJson(url, options = {}) {
    const defaultHeaders = {
        'Accept': 'application/json',
        'X-CSRF-TOKEN': getCsrfToken()
    };

    // If sending non-FormData body (object), serialize to JSON
    if (options.body && typeof options.body === 'object' && !(options.body instanceof FormData)) {
        defaultHeaders['Content-Type'] = 'application/json';
        options.body = JSON.stringify(options.body);
    }

    options.headers = {
        ...defaultHeaders,
        ...(options.headers || {})
    };

    const response = await fetch(url, options);
    let data;
    try {
        data = await response.json();
    } catch (e) {
        if (!response.ok) {
            throw new Error(`Server Error (${response.status})`);
        }
        data = {};
    }

    if (!response.ok || (data.success === false)) {
        const errorMsg = data.message || `Request failed with status ${response.status}`;
        const error = new Error(errorMsg);
        error.status = response.status;
        error.data = data;
        throw error;
    }

    return data;
}
