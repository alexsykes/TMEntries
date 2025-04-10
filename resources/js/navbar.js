document.addEventListener('DOMContentLoaded', function () {
    const button = document.querySelector('button[aria-controls="mobile-menu"]');
    const menu = document.getElementById('mobile-menu');

    button.addEventListener('click', function () {
        menu.classList.toggle('hidden');
    });
});