<!DOCTYPE html>
<?php
require_once 'lib/functiones.php';
require_once 'lib/modulos.php';
?>
<html>
    <head>
        <?php head_info(); ?>
        <title>DisplayAds</title>
    </head>
    <body>
        <!-- Imprimir menu del index, de forma modular sin introducir los codigos -->
        <?php menu_index(); ?>
        <br>
        <br>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <!-- Contenedor de la izquierda -->
                    <h2>TEXTO EXPLICATIVO</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed in nibh eu nisi lacinia pretium.</p>
                </div>
            </div>
        </div>
    </body>
</html>
