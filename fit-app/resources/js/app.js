import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

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
