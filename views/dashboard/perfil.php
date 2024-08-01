<?php include_once 'header.php'; ?>

<div class="contenedor-sm">
    <?php include_once __DIR__ . '/../templates/alertas.php'; ?>

    <form action="/perfil" class="formulario perfil" method="POST">
        <header>        
            <a href="/cambiar-password" class="enlace ocultar" id="cambiar-contraseña">Cambiar Contraseña</a>
        </header>

        <div class="campo">
            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" id="nombre" value="<?php echo $usuario -> nombre; ?>" placeholder="Tu nombre" disabled>
        </div>
        <div class="campo">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="<?php echo $usuario -> email; ?>" placeholder="Tu email" disabled>
        </div>
        <div class="acciones">            
            <button class="editar" id="editar"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-pencil" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="#597e8d" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" />
                                    <path d="M13.5 6.5l4 4" />
                                    </svg> <span>Editar</span> </button>
            <input type="submit" class="ocultar" value="Guardar Cambios" id="guardar">
            <button class="cancelar ocultar" id="cancelar">Cancelar</button>
        </div>        
    </form>
    <!-- <button class="boton-editar-perfil">Editar perfil</button> -->
</div>
<?php include_once 'footer.php'; ?>
<?php $script .= '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                 <script src="build/js/perfil.js"></script>'; ?>