import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

window.fitApi = async function (url, { method = 'GET', body = null } = {}) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    const headers = {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
    };

    if (csrfToken && method.toUpperCase() !== 'GET') {
        headers['X-CSRF-TOKEN'] = csrfToken;
    }

    const response = await fetch(url, {
        method,
        headers,
        body,
        credentials: 'same-origin',
    });

    const contentType = response.headers.get('content-type') || '';
    const data = contentType.includes('application/json') ? await response.json() : null;

    if (!response.ok) {
        const firstError = data?.errors ? Object.values(data.errors).flat()[0] : null;
        throw new Error(firstError || data?.message || 'Request failed. Please try again.');
    }

    return data;
};

Alpine.start();
// Toggle Button
const toggleMode = document.getElementById('toggle-mode');

if (toggleMode) {
    toggleMode.addEventListener('change', () => {
        document.documentElement.classList.toggle('dark');
    });
}

const sunIcon = document.querySelector(".sun");
const moonIcon = document.querySelector(".moon");

function showMoreDesc(id) {
    $('#' + id).toggle(400)
}
