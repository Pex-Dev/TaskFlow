<div class="barra-mobile">
  <header>
    <h1>UpTask</h1>
    <div class="menu">
      <svg id="mobile-menu" aria-label="menu mobile" xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-menu-2" width="48" height="48" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
        <path d="M4 6l16 0" />
        <path d="M4 12l16 0" />
        <path d="M4 18l16 0" />
      </svg>
    </div>
  </header>
  <div class="contenido">
    <nav class="mobile-nav">
        <?php include 'enlaces-nav.php'; ?>
    </nav>
    <?php include 'btn-cerrar-sesion.php' ?>
  </div>  
</div>



<div class="barra">
    <p>Hola: <span><?php echo $_SESSION['nombre']; ?></span></p>
    <?php include 'btn-cerrar-sesion.php' ?>
</div>