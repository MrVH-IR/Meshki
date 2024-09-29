function toggleMenu() {
    const menu = document.querySelector('.menu');
    menu.classList.toggle('active');
}

document.addEventListener('click', function(event) {
    const menuToggle = document.querySelector('.menu-toggle');
    const menu = document.querySelector('.menu');

    if (menuToggle.contains(event.target)) {
        toggleMenu();
    } else if (!menu.contains(event.target)) {
        menu.classList.remove('active');
    }
});
