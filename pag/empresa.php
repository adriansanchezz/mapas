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
                <hr>
                <ul class="nav nav-pills flex-column mb-auto">
                    <li class="nav-item">
                        <form action="empresa.php">
                            <button type="submit" name="empresaMapa" class="btn btn-link nav-link text-white">
                                Mapa
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
            <?php
            if (isset($_POST['empresaMapa'])) {
                mapa("ver");
            } 
            ?>
        </div>
    </body>
</html>