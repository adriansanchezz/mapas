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
    <!-- Menu general -->
    <?php menu_general(); ?>

    <div class="d-flex vh-100">
        <div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 200px;">
            <br><br>

            <ul class="nav nav-pills flex-column mb-auto">
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

        if (isset($_REQUEST['misiones'])) {
            ?>
            <div id="seccion1" class="p-3" style="display: block;">
                <h1>MISIONES</h1>
            </div>
            <?php
        }
        ?>

        <?php
        if (isset($_REQUEST['recompesas'])) {
            ?>
            <div id="seccion1" class="p-3" style="display: block;">
                <h1>Recompensas</h1>
            </div>
            <?php
        }
        ?>

    </div>
</body>

</html>