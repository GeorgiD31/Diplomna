import './bootstrap';
import Alpine from 'alpinejs';
import Choices from 'choices.js';

window.Alpine = Alpine;
Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    const elements = document.querySelectorAll('select');
    elements.forEach(el => {
        new Choices(el, {
            removeItemButton: true,
        });
    });
});
