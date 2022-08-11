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

// const connexion = document.querySelector('.connexion');
// const lico = document.querySelector('.li-connexion');
// connexion.addEventListener('click',function(event){
//     if(lico.classList.contains('d-none')){
//         lico.classList.remove('d-none');
//         connexion.classList.add('d-none');
//         event.preventDefault()
//     }else{
//         lico.classList.add('d-none');
//         connexion.classList.remove('d-none');
//         event.preventDefault()
//     }
// });

const newSub = document.querySelector('.pop-sub');
const popForm = document.querySelector('.pop-form');
newSub.addEventListener('click',function(event){
    if(popForm.classList.contains('d-none')){
        popForm.classList.remove('d-none');
        event.preventDefault()
    }else{
        popForm.classList.add('d-none');
        event.preventDefault()
    }
});

const displayComments = document.querySelectorAll('.sub-title');
console.log(displayComments);
const comments = document.querySelectorAll('.comments');
console.log(comments);
Array.from(displayComments).forEach(c=>{
    c.addEventListener('click',function(event){
        for (i=0; i<comments.length; i++){
            if (comments[i].classList.contains('d-none')) {
                comments[i].classList.remove('d-none');
                event.preventDefault();
            } else {
                comments[i].classList.add('d-none');
                event.preventDefault();
            }
        }})
});