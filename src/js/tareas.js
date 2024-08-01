(function() {

    obtenerTareas();

    //Arreglo de tareas global
    let tareas = [];
    let filtradas = [];

    //Botón para mostrar modal de agregar tarea
    const nuevaTareaBtn = document.querySelector('#agregar-tarea');
    nuevaTareaBtn.addEventListener('click',() =>{
        mostrarFormulario();
    })

    //Filtros de busqueda
    const filtros = document.querySelectorAll('#filtros input[type="radio"]');
    filtros.forEach(radio => {
        radio.addEventListener('input',(e) =>{
            filtrarTareas(e);
        });
    });

    function filtrarTareas(e){
        //Obtiene el valor del input radio
        const filtro = e.target.value;

        //Revisa si se selecciono algun filtro que no sea todas
        if(filtro !== ''){
            //Asigna las tareas que correspongan al filtro al arreglo filtradas
            filtradas = tareas.filter(tarea => tarea.estado === filtro);
        }else{
            filtradas = [];
        }


        mostrarTareas();
    }

    function mostrarFormulario(editar = false, tarea = {}){
        const modal = document.createElement('DIV');
        modal.classList.add('modal');
        modal.innerHTML = `
            <form class="formulario nueva-tarea">
                <legend>${editar ? 'Editar tarea' : 'Añadir nueva tarea'}</legend>
                <div class="campo">
                    <label for="tarea">Tarea</label>
                    <input type="text" name="tarea" id="tarea" placeholder="${editar ? 'Editar nombre de la tarea' : 'Añadir nombre de la tarea'}" value="${tarea.nombre ? tarea.nombre : ''}" >
                </div>
                <div class="opciones">
                    <input type="submit" value="${editar ? 'Editar tarea' : 'Añadir tarea'}" class="submit-nueva-tarea">
                    <button type="button" class="cerrar-modal">Cancelar</button>
                </div>
            </form>
        `;
        setTimeout(() => {
            const formulario = document.querySelector('.formulario');
            formulario.classList.add('animar');
        }, 0);

        modal.addEventListener('click',(e) =>{
            e.preventDefault();
            if(e.target.classList.contains('cerrar-modal')){
                const formulario = document.querySelector('.formulario');
                formulario.classList.add('cerrar');
                setTimeout(() => {
                    modal.remove();  
                }, 500);
                
            }
            if(e.target.classList.contains('submit-nueva-tarea')){
                submitFormularioTarea(editar,tarea);                
            }
        })

        document.querySelector('.dashboard').appendChild(modal);
    }

    function submitFormularioTarea(editar,tarea){
        const tareaNombre = document.querySelector('#tarea').value.trim();
        if(tareaNombre === ''){
            //Mostrar alerta de error
            mostrarAlerta('El nombre de la tarea no puede ir vacio','error',document.querySelector('.formulario legend'));
            return;
        }
        if(editar){
            tarea.nombre = tareaNombre;
            actualizarTarea({...tarea});
            return;
        }
        agregarTarea(tareaNombre);
    }

    //Consultar el servidor para añadir una nueva tarea al proyecto actual
    async function agregarTarea(tarea){
        const datos =  new FormData();
        datos.append('nombre',tarea);
        datos.append('proyectoId',obtenerProyecto());

        try{            
            const url = `${location.origin}/api/tarea`;
            const respuesta = await fetch(url,{
                method: 'POST',
                body: datos
            })
            const resultado = await respuesta.json();
            mostrarAlerta(resultado.mensaje, resultado.tipo, document.querySelector('.formulario legend'));
            if(resultado.tipo==='exito'){
                
                //Agregar el objeto de tareas al global de tareas
                const tareaObj = {
                    id: String(resultado.id),
                    nombre: tarea,
                    estado: "0",
                    proyectoId: resultado.proyectoId
                }

                //Agregar la nueva tarea al arreglo de tareas globla
                tareas = [...tareas, tareaObj];

                const modal = document.querySelector('.modal');

                if(modal){
                    modal.remove();
                    Swal.fire('Agregado!',resultado.mensaje,'success');
                }

                mostrarTareas();
            }
        }catch(error){
            console.log(error);
        }
    }

    async function actualizarTarea(tarea){
        const {estado,id,nombre} = tarea;

        const datos = new FormData();
        datos.append('id',id);
        datos.append('estado',estado);
        datos.append('nombre',nombre);
        datos.append('proyectoId',obtenerProyecto());

        try {
            const url = `${location.origin}/api/tarea/actualizar`;

            const respuesta = await fetch(url,{
                method: 'POST',
                body: datos
            })
            const resultado = await respuesta.json();
            
            if(resultado.tipo==='exito'){
                tareas = tareas.map(tareaMemoria =>{//Itera sobre todos los elementos del arreglo 
                    if(tareaMemoria.id === id){
                        tareaMemoria.estado = estado;
                    }

                    return tareaMemoria;//Asigna el valor al arreglo
                })
                const modal = document.querySelector('.modal');

                if(modal){
                    modal.remove();
                    Swal.fire('Editado!',resultado.mensaje,'success');
                }
                
            }
            
            mostrarTareas();
        } catch (error) {
            console.log(error);
        }
    }

    async function obtenerTareas(){
        try {
            //Obtener la url del proyecto 
            const id = obtenerProyecto();
            const url = `${location.origin}/api/tareas?id=${id}`;
            const respuesta = await fetch(url);
            const resultado = await respuesta.json(); 

            tareas = resultado.tareas;
            mostrarTareas();

        } catch (error) {
            console.log(error);
        }
    }    

    function totalPendientes(){
        const totalPendientes = tareas.filter(tarea => tarea.estado === "0");
        const pendienteRadio = document.querySelector('#pendientes');
        if(totalPendientes.length === 0){
            pendienteRadio.disabled = true;
        }else{
            pendienteRadio.disabled = false;
        }
    }
    
    function totalCompletadas(){
        const totalCompletadas = tareas.filter(tarea => tarea.estado === "1");
        const completadasRadio = document.querySelector('#completadas');
        if(totalCompletadas.length === 0){
            completadasRadio.disabled = true;
        }else{
            completadasRadio.disabled = false;
        }
    }

    function mostrarTareas(){
        limpiarTareas();

        totalPendientes();
        totalCompletadas();

        //Revisa si filtradas contiene alguna tarea si lo hay asigna esas tareas al arreglo si no arregla las tareas del arreglo tareas
        const arregloTareas = filtradas.length ? filtradas : tareas;

        const contenedorTareas = document.querySelector('#listado-tareas');

        //Muestra mensaje si no hay tareas
        if(arregloTareas.length === 0){           

            const textoNoTareas = document.createElement('LI');
            textoNoTareas.textContent = 'No hay tareas :(';
            textoNoTareas.classList.add('no-tareas');

            contenedorTareas.appendChild(textoNoTareas);

            return;
        }

        //Objeto con los estados que puede tener una tarea
        const estados = {
            0: 'Pendiente',
            1: 'Completa'
        }

        arregloTareas.forEach(tarea => {
            const contenedorTarea = document.createElement('LI');
            contenedorTarea.dataset.tareaId = tarea.id;
            contenedorTarea.classList.add('tarea');
            
            const nombreTarea = document.createElement('P');
            nombreTarea.classList.add('nombre-tarea');

            const spanNombre = document.createElement('SPAN');
            spanNombre.textContent = tarea.nombre;

            const btnEditar = document.createElement('BUTTON');
            btnEditar.classList.add('editar-tarea')
            btnEditar.innerHTML =  `<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-pencil" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="#597e8d" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" />
                                    <path d="M13.5 6.5l4 4" />
                                    </svg>`;
            btnEditar.ariaLabel = 'editar tarea';
            btnEditar.onclick = () =>{
                mostrarFormulario(true,tarea);
            };

            nombreTarea.appendChild(btnEditar);
            nombreTarea.appendChild(spanNombre);

            contenedorTarea.appendChild(nombreTarea);

            const opcionesDiv = document.createElement('DIV');
            opcionesDiv.classList.add('opciones');

            //Botones
            const btnEstadoTarea = document.createElement('BUTTON');
            btnEstadoTarea.classList.add('estadoTarea');
            btnEstadoTarea.classList.add(`${estados[tarea.estado].toLowerCase()}`);
            btnEstadoTarea.textContent = estados[tarea.estado];
            btnEstadoTarea.dataset.estadoTarea = tarea.estado;    
            btnEstadoTarea.onclick = () =>{
                cambiarEstadoTarea({...tarea});//Enviamos un nuevo objeto copia de tarea
            }

            const btnEliminarTarea = document.createElement('BUTTON');
            btnEliminarTarea.classList.add('eliminar-tarea');
            btnEliminarTarea.dataset.idTarea = tarea.id;
            btnEliminarTarea.textContent = 'Eliminar';
            btnEliminarTarea.onclick = () =>{
                confirarEliminarTarea(tarea);
            }

            opcionesDiv.appendChild(btnEstadoTarea);
            opcionesDiv.appendChild(btnEliminarTarea);
            
            contenedorTarea.appendChild(opcionesDiv);

            contenedorTareas.appendChild(contenedorTarea);
        });
    }


    function limpiarTareas(){
        const listadoTareas = document.querySelector('#listado-tareas');
        
        //Mientras listado tareas tenga un hijo
        while(listadoTareas.firstChild){
            listadoTareas.removeChild(listadoTareas.firstChild);//Remueve el hijo de listado tareas
        }
    }
    
    function cambiarEstadoTarea(tarea){
        const nuevoEstado = tarea.estado === "1"  ? "0" : "1";
        tarea.estado = nuevoEstado;

        actualizarTarea(tarea);
    }
    
    

    function confirarEliminarTarea(tarea){
        Swal.fire({
            title: "¿Elminar tarea?",
            showCancelButton: true,
            confirmButtonText: "Si",
            cancelButtonText: 'No'
          }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                eliminarTarea({...tarea});
            }
          });
    }

    async function eliminarTarea(tarea){
        const {id} = tarea;

        const datos = new FormData();
        datos.append('id',id);
        datos.append('proyectoId',obtenerProyecto());


        try {
            const url = `${location.origin}/api/tarea/eliminar`;

            const respuesta = await fetch(url,{
                method: 'POST',
                body: datos
            })

            const resultado = await respuesta.json();

            if(resultado.tipo==='exito'){
               Swal.fire('Eliminado!',resultado.mensaje,'success');
                // crear nuevo arreglo filtrando para que no se incluya la tarea que coincida con el id de la eliminada
                tareas = tareas.filter(tareaMemoria => tareaMemoria.id != id);
                mostrarTareas();
            }

        } catch (error) {
            console.log(error);
        }
    }

    //Muestra un mensaje en la interfaz
    function mostrarAlerta(mensaje,tipo,referencia){
        //Previene la creacion de multiples alertas
        const alertaPrevia = document.querySelector('.alertas');
        if(alertaPrevia){
            alertaPrevia.remove();
        }
        const alerta = document.createElement('DIV');
        alerta.classList.add('alertas',tipo);
        alerta.textContent = mensaje;
        referencia.parentElement.insertBefore(alerta,referencia.nextElementSibling);

        //Eliminar la alerta despues de 5 segundos
        setTimeout(() => {
            alerta.remove();
        }, 5000);
    }

    function obtenerProyecto(){
        const proyectoParams = new URLSearchParams(window.location.search);//Obtiene parametros de la URL 
        const proyecto = Object.fromEntries(proyectoParams.entries());//Convierte los parametros a un objeto
        return proyecto.id;
    }
    
})();