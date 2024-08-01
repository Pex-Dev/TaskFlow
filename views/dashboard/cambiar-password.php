<?php include_once 'header.php'; ?>

<div class="contenedor-sm">    

    <a href="/perfil" class="enlace">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-narrow-left" width="32" height="32" viewBox="0 0 24 24" stroke-width="2.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
        <path d="M5 12l14 0" />
        <path d="M5 12l4 4" />
        <path d="M5 12l4 -4" />
        </svg> 
        <span>Volver</span>
    </a>

    <?php include_once __DIR__ . '/../templates/alertas.php'; ?>

    <div class="aviso">
        <p>La contraseña debe contener lo siguiente:</p>
        <ul>
            <li>Al menos 6 caracteres</li>
            <li>Al menos una letra mayúscula</li>
            <li>Al menos una letra minúscula</li>
            <li>Al menos un símbolo (#,!,$, etc)</li>
        </ul>
    </div>
    <form action="/cambiar-password" class="formulario" method="POST">
        <div class="campo">
            <label for="password_actual">Contraseña Actual</label>
            <input type="password" name="password_actual" id="password_actual" value="" placeholder="Tu actual contraseña">
        </div>
        <div class="campo">
            <label for="password_nuevo">Nueva Contraseña</label>
            <input type="password" name="password_nuevo" id="password_nuevo" value="" placeholder="Tu nueva contraseña">
        </div>
        <input type="submit" value="Guardar Cambios">
    </form>


</div>

<?php include_once 'footer.php'; ?>