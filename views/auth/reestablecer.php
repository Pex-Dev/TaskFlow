<div class="contenedor crear">
    <?php 
        include_once __DIR__.'/../templates/nombre-sitio.php';
    ?>
    <div class="contenedor-sm">
        <p class="descripcion-pagina">Coloca tu nuevo password</p>
        <?php 
            include_once __DIR__.'/../templates/alertas.php';
            if($mostrar):
        ?>
        <form class="formulario" method="POST">
            <div class="campo">
                <label for="password">Contraseña</label>
                <input type="password" name="password" id="password" placeholder="Tu contraseña">
            </div>
            <div class="campo">
                <label for="password2">Repite tu Contraseña</label>
                <input type="password" name="password2" id="password2" placeholder="Repite tu contraseña">
            </div>
            <input type="submit" class="boton" value="Confirmar Contraseña">
        </form>
        <div class="aviso">
            <p>La contraseña debe contener lo siguiente:</p>
            <ul>
                <li>Al menos 6 caracteres</li>
                <li>Al menos una letra mayúscula</li>
                <li>Al menos una letra minúscula</li>
                <li>Al menos un símbolo (#,!,$, etc)</li>
            </ul>
        </div>
        <?php 
            endif;
        ?>
        <div class="acciones">
            <a href="/">¿Ya tienes cuenta? Inicia Sesión</a>
            <a href="/crear">¿Aún no tienes una cuenta? Crea una aquí</a>
        </div>
    </div>
</div>