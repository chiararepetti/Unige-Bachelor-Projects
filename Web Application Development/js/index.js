const burger = document.querySelector('#nav-burger');
const navbar = document.querySelector('#nav-links');
burger.addEventListener('click', () => {
    navbar.classList.toggle('is-active');
    })
