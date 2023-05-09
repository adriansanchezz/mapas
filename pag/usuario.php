<!DOCTYPE html>
<?php
require_once '../lib/functiones.php';
require_once '../lib/modulos.php';
?>
<html>
    <head>
        <?php head_info(); ?>
        <title>DisplayAds</title>
    </head>
    <body>
        <?php menu_general(); ?>
        
        <div class="d-flex vh-100">
            <div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 100px;">
                <br><br>
                
                <ul class="nav nav-pills flex-column mb-auto">
                    <li class="nav-item">
                        <form action="usuario.php" method="post">
                            <button type="submit" name="usuarioInicio" class="btn btn-link nav-link text-white">Inicio</button>
                        </form>
                    </li>
                    <li>
                        <form action="usuario.php" method="post">
                            <button type="submit" name="usuarioVigia" class="btn btn-link nav-link text-white">Vigía</button>
                        </form>
                    </li>
                </ul>
            </div>

            <?php
            if (isset($_POST['usuarioInicio'])) {
            ?>
                <div class="flex-grow-1">
                    <form class="form-inline my-2 my-lg-0" action="mapa.php" method="post">
                        <button class="btn btn-outline-success my-2 my-sm-0" name="usuarioMapa" type="submit">Mapa</button>
                    </form>
                    <form class="form-inline my-2 my-lg-0" action="usuario.php" method="post">
                        <button class="btn btn-outline-success my-2 my-sm-0" name="usuarioInicioTienda" type="submit">Tienda</button>
                    </form>
                </div>
            <?php
            } 
            ?>

            <?php
            if (isset($_POST['usuarioVigia'])) {
            ?>
                <div class="flex-grow-1">
                    <form class="form-inline my-2 my-lg-0" action="usuario.php" method="post">
                        <button class="btn btn-outline-success my-2 my-sm-0" name="usuarioVigiaMisiones" type="submit">Misiones</button>
                    </form>
                    <form class="form-inline my-2 my-lg-0" action="usuario.php" method="post">
                        <button class="btn btn-outline-success my-2 my-sm-0" name="usuarioVigiaPuntos" type="submit">Puntos</button>
                    </form>
                </div>
            <?php
            } 
            ?>
            <?php
            if (isset($_POST['usuarioInicioTienda'])) {
            ?> 
                <h1>TIENDA</h1>
            <?php
            } 
            ?>

            <?php
            if (isset($_POST['usuarioVigiaMisiones'])) {
            ?> 
                <h1>MISIONES</h1>
            <?php
            } 
            ?>
            
            <?php
            if (isset($_POST['usuarioVigiaPuntos'])) {
            ?> 
                <h1>PUNTOS</h1>
            <?php
            } 
            ?>

            <?php
            if (isset($_POST['usuarioInicioMapa'])) {
                mapa("guardar");
            } 
            ?>

        </div>

    </body>
</html>