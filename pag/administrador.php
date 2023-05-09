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
            <div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 200px;">
                <hr>
                <ul class="nav nav-pills flex-column mb-auto">
                    <li class="nav-item">
                        <form action="menu.php" method="get">
                            <button type="submit" name="administradorAdministradores" class="btn btn-link nav-link text-white">
                                Administradores
                            </button>
                        </form>
                    </li>
                    <li class="nav-item">
                        <form action="menu.php" method="get">
                            <button type="submit" name="administradorEmpresas" class="btn btn-link nav-link text-white">
                                Empresas
                            </button>
                        </form>
                    </li>
                    <li class="nav-item">
                        <form action="menu.php" method="get">
                            <button type="submit" name="administradorVigilantes" class="btn btn-link nav-link text-white">
                                Vigilantes
                            </button>
                        </form>
                    </li>
                    <li class="nav-item">
                        <form action="menu.php" method="get">
                            <button type="submit" name="administradorUsuarios" class="btn btn-link nav-link text-white">
                                Usuarios
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
            <div class="flex-grow-1">
                <div id="seccion1" class="p-3" style="display: block;">
                    <h1></h1>
                </div>

            </div>
        </div>
    </body>
</html>