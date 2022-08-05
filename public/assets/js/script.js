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