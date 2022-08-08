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
        newSub.classList.add('d-none');
        event.preventDefault()
    }else{
        popForm.classList.add('d-none');
        newSub.classList.remove('d-none');
        event.preventDefault()
    }
});