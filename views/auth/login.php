<div class="contenedor login">
    <?php 
        include_once __DIR__.'/../templates/nombre-sitio.php';
    ?>
    <div class="contenedor-sm">
        <p class="descripcion-pagina">Iniciar Sesión</p>
        <?php 
            include_once __DIR__.'/../templates/alertas.php';
        ?>
        <form class="formulario" action="/" method="POST">
            <div class="campo">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="Tu email" value="<?php echo $usuario -> email; ?>">
            </div>
            <div class="campo">
                <label for="password">Contraseña</label>
                <input type="password" name="password" id="password" placeholder="Tu contraseña">
            </div>
            <input type="submit" class="boton" value="Iniciar Sesión">
        </form>
        <div class="acciones">
            <a href="/crear">¿Aún no tienes una cuenta? Crea una aquí</a>
            <a href="/olvide">¿Olvidaste tu contraseña?</a>
        </div>
    </div>
</div>