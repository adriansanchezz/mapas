<!DOCTYPE html>
<?php
//Importar y abrir session que esta dentro de functiones.php
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
    <!-- Menu horizontal -->
    <div class="d-flex vh-100">
        <div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 200px;">
            <hr>
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <form action="administrador.php" method="get">
                        <button type="submit" name="administradorAdministradores"
                            class="btn btn-link nav-link text-white">
                            Administrar Roles
                        </button>
                    </form>
                </li>
                <li class="nav-item">
                    <form action="administrador.php" method="get">
                        <button type="submit" name="administradorEmpresas" class="btn btn-link nav-link text-white">
                            Banear usuario
                        </button>
                    </form>
                </li>
            </ul>
        </div>
        <div class="flex-grow-1">
            <div id="seccion1" class="p-3" style="display: block;">



                
                
                <?php
                if (isset($_REQUEST['administradorAdministradores'])) {
                    listarRoles($_SESSION['usuario']['id_usuario']);
                }
                ?>














            </div>
        </div>
    </div>

</body>

</html>