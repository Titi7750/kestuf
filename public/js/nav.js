document.addEventListener('DOMContentLoaded', function() {
    const burgerMenu = document.getElementById('burger-menu');
    const navLinks = document.getElementById('nav-links');

    if (burgerMenu && navLinks) {
        burgerMenu.addEventListener('click', function () {
            navLinks.classList.toggle('show');
        });
    } else {
        console.error("Burger menu or nav links not found");
    }
});
