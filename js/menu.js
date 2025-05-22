// Mobile menu toggle (hamburger menu)
const hamburgerBtn = document.querySelector('.hamburger-btn');
const closeBtn = document.querySelector('.close-btn');
const navbarLinks = document.querySelector('.navbar .links');

// Toggle mobile menu
hamburgerBtn.addEventListener('click', () => {
    navbarLinks.classList.add('show-menu');
});

closeBtn.addEventListener('click', () => {
    navbarLinks.classList.remove('show-menu');
});

// User dropdown menu toggle (for logged-in users)
const userMenu = document.querySelector('.user-menu');
const userOptions = document.querySelector('.user-options');

// Show/hide user options when logged in
if (userMenu) {
    userMenu.addEventListener('click', () => {
        userOptions.classList.toggle('show');
    });
}
