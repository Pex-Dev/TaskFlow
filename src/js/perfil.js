(function (){
    class Perfil{

        constructor(){
            this.btnCambiarPassword = document.querySelector('#cambiar-contraseña');
            this.btnEditar = document.querySelector('#editar');
            this.btnGuardar = document.querySelector('#guardar');
            this.btnCancelar = document.querySelector('#cancelar');

            this.nombre = document.querySelector('#nombre');
            this.email = document.querySelector('#email');

            this.nombreOriginal = this.nombre.value;
            this.emailOriginal = this.email.value;
        }

        editar(){
            this.obtenerValoresOriginales();
            this.btnCambiarPassword.classList.remove('ocultar');
            this.btnGuardar.classList.remove('ocultar');
            this.btnCancelar.classList.remove('ocultar');
            this.btnEditar.classList.add('ocultar');
            this.nombre.disabled = false;
            this.email.disabled = false;
        }

        cancelar(){
            this.btnCambiarPassword.classList.add('ocultar');
            this.btnGuardar.classList.add('ocultar');
            this.btnCancelar.classList.add('ocultar');
            this.btnEditar.classList.remove('ocultar');
            this.email.disabled = true;
            this.nombre.disabled = true;

            this.nombre.value = this.nombreOriginal;
            this.email.value = this.emailOriginal;
        }

        obtenerValoresOriginales(){
            this.nombreOriginal = this.nombre.value;
            this.emailOriginal = this.email.value;
        }
    }

    perfil = new Perfil();

    const btnEditar = document.querySelector('#editar');
    const btnCancelar = document.querySelector('#cancelar');

    btnEditar.addEventListener('click',(e) =>{
        e.preventDefault();
        perfil.editar();
    })

    btnCancelar.addEventListener('click',(e) =>{
        e.preventDefault();
        perfil.cancelar();
    })
})();