(function () {
   const mobileMenuBtn = document.querySelector('#mobile-menu');
   

   if(mobileMenuBtn){
    const barraMobile = document.querySelector('.barra-mobile');
    const contenido = barraMobile.querySelector('.contenido');
    mobileMenuBtn.addEventListener('click',() =>{
        barraMobile.classList.toggle('activo');
        contenido.classList.toggle('mostrar');  
    })
   }
})();