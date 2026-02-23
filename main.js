
document.addEventListener("DOMContentLoaded", function () {

    document.getElementById('theme-toggle')?.addEventListener('click', function() {
        const currentTheme = document.documentElement.getAttribute('data-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        document.documentElement.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        this.textContent = newTheme === 'dark' ? '🌞' : '🌙';
    });

    let navbar = document.querySelector('.navbar');
    let search = document.querySelector('.search-form');
    let userIcon = document.getElementById('user-icon');
    let dropdown = document.getElementById('dropdown-menu');

    document.querySelector('#menu-btn').onclick = () => {
        navbar.classList.toggle('active');
    };

    document.querySelector('#search-btn').onclick = () => {
        search.classList.toggle('active');
    };

    userIcon.onclick = (event) => {
        event.stopPropagation(); // prevent window.onclick from firing
        dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
    };

    window.onclick = function (event) {
        if (!event.target.closest('.user-dropdown')) {
            dropdown.style.display = 'none';
        }
    };

    const incrementBtn = document.querySelector('.increment');
    const decrementBtn = document.querySelector('.decrement');
    const inputField = document.querySelector('#counter-value');

    incrementBtn?.addEventListener('click', () => {
        let currentValue = parseInt(inputField.value);
        inputField.value = currentValue + 1;
    });

    decrementBtn?.addEventListener('click', () => {
        let currentValue = parseInt(inputField.value);
        if (currentValue > 1) {
            inputField.value = currentValue - 1;
        }
    });
});
