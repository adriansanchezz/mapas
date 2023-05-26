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
        menu_general();
        ?>
        <form action="soporte.php" method="POST">
            <?php listarTiposSoportes(); ?>
            <input type='submit' name='aceptarMision' class='btn btn-success' value='Aceptar'>
        </form>

        <?php
        if (isset($_POST['opcion'])) {
            $soporte = $_POST['opcion'];
            switch ($soporte) {
                case "Preguntas":
                    ?>
                        <h1>1</h1>
                    <?php
                    break;
                case "Reportar":
                    ?>
                        <h1>2</h1>

                    <?php
                    break;
                case "Error":
                    ?>
                        <h1>3</h1>

                    <?php
                    break;
                case "Sugerencia":
                    ?>
                        <h1>4</h1>

                    <?php
                    break;
                default:
                    ?>
                        <h1>5</h1>

                    <?php
                    break;
            }
        }


    } else {
        echo ('Acceso denegado');
        print '<a href ="../index.php"><button>Volver</button></a>';
        session_destroy();
    }
    ?>
</body>

</html>