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

        if (validarUsuario($_SESSION['usuario']['id_usuario'])) {
            echo "Usuario SI";
        } else {
            echo "Usuario NO";
        }
        echo "<br>";

        if (validarAdmin($_SESSION['usuario']['id_usuario'])) {
            echo "Admin SI";
        } else {
            echo "Admin NO";
        }
        echo "<br>";

        if (validarEmpresa($_SESSION['usuario']['id_usuario'])) {
            echo "Empresa SI";
        } else {
            echo "Empresa NO";
        }
        echo "<br>";

        if (validarVIP($_SESSION['usuario']['id_usuario'])) {
            echo "VIP SI";
        } else {
            echo "VIP NO";
        }
        echo "<br>";

        if (validarVigilante($_SESSION['usuario']['id_usuario'])) {
            echo "Vigilante SI";
        } else {
            echo "Vigilante NO";
        }

    } else {
        echo ('Acceso denegado');
        print '<a href ="../index.php"><button>Volver</button></a>';
        session_destroy();
    }
    ?>

</body>

</html>