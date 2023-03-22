document.addEventListener('DOMContentLoaded', function() {
const menu = document.getElementById('menu_burger');
const menu_deroulant = document.getElementById('menu_deroulant');

menu.addEventListener('click', () => {
    menu.classList.toggle('ouvert');
    menu_deroulant.classList.toggle('menu_deroulant');
});
})