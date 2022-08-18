// js menu burger
const burger = document.getElementById('burger');
console.log(burger);
const menu = document.querySelector('.menu-burg');
burger.addEventListener('click',function () {
    if(menu.classList.contains('d-none')){
        menu.classList.remove('d-none');
    }else{
        menu.classList.add('d-none');
    }
});
// js afficher/cacher commentaires forum
var acc = document.getElementsByClassName("accordion");

for (i = 0; i < acc.length; i++) {
    acc[i].addEventListener("click", function() {
        /* Toggle between adding and removing the "active" class,
        to highlight the button that controls the panel */
        this.classList.toggle("active");

        /* Toggle between hiding and showing the active panel */
        const divAccordion = this.closest("#accordion");
        const panel = divAccordion.querySelector(".panel");
        if (panel.classList.contains("d-none")) {
            panel.classList.remove('d-none');
        } else {
            panel.classList.add('d-none');
        }
    });
}

function comment(a)
{
    if(a==1)
        document.getElementById("comment").classList.add('d-none');
    else
        document.getElementById("comment").classList.remove('d-none');
}