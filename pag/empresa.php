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
        <div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 150px;">
            <br><br>

            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <form action="empresa.php" method="post">
                        <button type="submit" name="empresaInicio"
                            class="btn btn-link nav-link text-white">Principal</button>
                    </form>
                </li>
                <li>
                    <form action="empresa.php" method="post">
                        <button type="submit" name="empresaVigia"
                            class="btn btn-link nav-link text-white">Informacion</button>
                    </form>
                </li>
            </ul>
        </div>

        <?php
        if (isset($_POST['empresaInicio'])) {
            ?>
            <div class="flex-grow-1">
                <form class="form-inline my-2 my-lg-0" action="mapa.php" method="post">
                    <button class="btn btn-outline-success my-2 my-sm-0" name="empresaMapa" type="submit">Mapa</button>
                </form>
            </div>
            <?php
        }
        ?>


        <?php
        if (isset($_POST['empresaMapa'])) {
            mapa("ver");
        }
        ?>
    </div>
</body>

</html>