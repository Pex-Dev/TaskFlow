<div class="contenedor crear">
    <?php 
        include_once __DIR__.'/../templates/nombre-sitio.php';
    ?>
    <div class="contenedor-sm">
        <p class="descripcion-pagina">Crea tu cuenta</p>
        <?php 
            include_once __DIR__.'/../templates/alertas.php';
        ?>
        <form class="formulario" action="/crear" method="POST">     
            <div class="campo">
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" id="nombre" placeholder="Tu nombre" value="<?php echo $usuario->nombre; ?>" >
            </div>
            <div class="campo">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="Tu email" value="<?php echo $usuario->email; ?>" >
            </div>       
            <div class="campo">
                <label for="password">Contraseña</label>
                <input type="password" name="password" id="password" placeholder="Tu contraseña" >
            </div>
            <div class="campo">
                <label for="password2">Repetir Contraseña</label>
                <input type="password" name="password2" id="password2" placeholder="Repite tu contraseña" >
            </div>
            <input type="submit" class="boton" value="Crear Cuenta">
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
        <div class="acciones">
            <a href="/">¿Ya tienes cuenta? Inicia Sesión</a>
            <a href="/olvide">¿Olvidaste tu contraseña?</a>
        </div>
    </div>
</div>