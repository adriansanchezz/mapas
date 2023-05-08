<!DOCTYPE html>
<?php
if(isset($_POST['cerrarSesion'])){
    session_destroy(); // Destruye todas las variables de sesión
    header("Location:../login.php"); // Redirige al usuario a la página de inicio de sesión
    exit; // Detiene la ejecución del script después de la redirección
}
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

    </body>
</html>