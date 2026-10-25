import axios from 'axios';
import {syncCsrfToken} from '../utils/csrfToken.js';

// todo: location of this file?

const csrfTokenEndpoint = '/api/csrf-token';
const apiBaseUrl = import.meta.env.VITE_API_BASE_URL ?? import.meta.env.VITE_WEB_APP_URL ?? null;

function isNativeWebViewOrigin() {
    return !['http:', 'https:'].includes(window.location.protocol);
}

function csrfTokenEndpointUrl() {
    if (isNativeWebViewOrigin() && apiBaseUrl) {
        return new URL(csrfTokenEndpoint, apiBaseUrl).toString();
    }

    return csrfTokenEndpoint;
}

async function fetchCsrfToken() {
    const response = await fetch(csrfTokenEndpointUrl(), {
        headers: {
            Accept: 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        },
        credentials: isNativeWebViewOrigin() ? 'include' : 'same-origin',
    });

    if (!response.ok) {
        throw new Error(`CSRF token bootstrap failed with status ${response.status}.`);
    }

    const data = await response.json();

    return data.csrfToken || null;
}

async function resolveCsrfToken() {
    const bladeToken = document.querySelector('meta[name="csrf-token"]')?.content || null;

    if (bladeToken) {
        return bladeToken;
    }

    try {
        return await fetchCsrfToken();

    } catch (error) {
        console.error('Failed to fetch CSRF token:', error);

        return null;
    }
}

function bootstrapAxios() {
    window.axios = axios;

    if (isNativeWebViewOrigin() && apiBaseUrl) {
        axios.defaults.baseURL = apiBaseUrl;
    }

    axios.defaults.headers.common['Accept'] = 'application/json';
    axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

    axios.interceptors.response.use((response) => {
        const csrfToken = response?.data?.csrf_token;

        if (csrfToken) {
            syncCsrfToken(csrfToken);
        }

        return response;
    });
}

export async function bootstrapHttp() {
    const csrfToken = await resolveCsrfToken();

    bootstrapAxios();
    syncCsrfToken(csrfToken);

    return csrfToken;
}
