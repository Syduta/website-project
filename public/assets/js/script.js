const connexion = document.querySelector('.connexion');
console.log(connexion);
const lico = document.querySelector('.li-connexion');
console.log(lico);
connexion.addEventListener('click',function(event){
    if(lico.classList.contains('d-none')){
        lico.classList.remove('d-none');
        connexion.classList.add('d-none');
        event.preventDefault()
    }else{
        lico.classList.add('d-none');
        connexion.classList.remove('d-none');
        event.preventDefault()
    }
});

const newSub = document.querySelector('.pop-sub');
console.log(newSub);
const popForm = document.querySelector('.pop-form');
console.log(popForm);
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