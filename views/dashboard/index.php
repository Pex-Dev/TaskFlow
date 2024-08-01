<?php include_once 'header.php'; ?>

<?php if(count($proyectos)===0){?>
    <p class="no-proyectos">No hay proyectos aún <a href="/crear-proyecto">Crear Proyecto</a></p>
    
<?php }else{ ?>
    <ul class="listado-proyectos">
        <?php foreach ($proyectos as $proyecto):?>
            <li class="proyecto">
                <a href="/proyecto?id=<?php echo $proyecto -> url  ?>"><?php echo $proyecto -> proyecto  ?></a>
            </li>
        <?php endforeach; ?>        
    </ul>
<?php } ?>
<?php include_once 'footer.php'; ?>