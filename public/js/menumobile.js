document.addEventListener('DOMContentLoaded', function() {
const menu = document.getElementById('menu_burger');
const menu_deroulant = document.getElementById('menu_deroulant');


// Récupérez l'élément body
var body = document.querySelector('body');
var hey = 0;

menu.addEventListener('click', () => {
    if (hey == 1){
        menu.classList.toggle('ouvert');
        menu_deroulant.classList.toggle('menu_deroulant');
        // Rétablissez la propriété CSS overflow à auto pour afficher les barres de défilement
        body.style.overflow = 'auto';
        hey = 0;
    }
    else{
        menu.classList.toggle('ouvert');
        menu_deroulant.classList.toggle('menu_deroulant');
        // Masquez les barres de défilement en définissant la propriété CSS overflow à hidden
        body.style.overflow = 'hidden';
        hey = 1;
    }
    
   
});
})


