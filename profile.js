function toggleEdit(fieldId) {
    const field = document.getElementById(fieldId);
    if (field.hasAttribute('readonly')) {
        field.removeAttribute('readonly');
        field.style.backgroundColor = "var(--input-focus)";
        field.focus();
    } else {
        field.setAttribute('readonly', true);
        field.style.backgroundColor = "var(--input-bg)";
    }
}

// Theme toggle functionality (should match your main.js)
// function toggleTheme() {
//     const current = document.documentElement.getAttribute('data-theme') || 'dark';
//     const newTheme = current === 'dark' ? 'light' : 'dark';
//     document.documentElement.setAttribute('data-theme', newTheme);
//     localStorage.setItem('theme', newTheme);
// }

// // Load saved theme
// (function () {
//     const saved = localStorage.getItem('theme') || 'dark';
//     document.documentElement.setAttribute('data-theme', saved);
// })();