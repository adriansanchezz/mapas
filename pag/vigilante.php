<!DOCTYPE html>
<?php
//Importar y abrir session que esta dentro de functiones.php
require_once '../lib/functiones.php';
require_once '../lib/modulos.php';
?>
<html>

<head>
    <!-- Meter informacion general de head -->
    <?php head_info(); ?>
    <title>DisplayAds</title>
</head>

<body>
    <?php
    if (isset($_SESSION['usuario'])) {
        // Menu general
        menu_general(); ?>
        <!-- Crear submenu con sus opciones -->
        <div class="d-flex vh-100">
            <div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 200px;">
                <br><br>
                <ul class="nav nav-pills flex-column mb-auto">
                    <li>
                        <form action="vigilante.php" method="post">
                            <button type="submit" name="vigilantePrincipal"
                                class="btn btn-link nav-link text-white">Principal
                            </button>
                        </form>
                    </li>
                    <li>
                        <form action="vigilante.php" method="post">
                            <button type="submit" name="misiones" class="btn btn-link nav-link text-white">Misiones
                            </button>
                        </form>
                    </li>
                    <li>
                        <form action="vigilante.php" method="post">
                            <button type="submit" name="recompesas" class="btn btn-link nav-link text-white">Recompensas
                            </button>
                        </form>
                    </li>
                </ul>
            </div>



            <?php
            if (isset($_REQUEST['vigilantePrincipal'])) {
                ?>
                <div class="flex-grow-1">
                    <div id="seccion1" class="p-3" style="display: block;">
                        <h2>Noticias para vigilante</h2><br>
                    </div>
                </div>
                <?php
            }
            ?>

            <?php
            if (isset($_REQUEST['misiones'])) {
                ?>


                <?php mapa("vigilar"); ?>
                <?php
                echo '<input type="button" class="btn btn-primary" id="solicitarMision" value="Solicitar mision">';
                ?>
                <?php
            }
            if (isset($_REQUEST['solicitarMision'])) {
                function randomMission()
                {

                }
            }
            ?>

            <?php
            if (isset($_REQUEST['recompesas'])) {
                ?>
                < div id="seccion1" class="p-3" style="display: block;">
                    <h1>Recompensas</h1>

            </div>
            <?php
            }
            ?>
        </div>
        <?php
    } else {
        echo ('Acceso denegado');
        print '<a href ="../index.php"><button>Volver</button></a>';
        session_destroy();
    }
    ?>

</body>

</html>