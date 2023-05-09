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
    <?php 
        menu_general();
        validarUsuario($_SESSION['usuario']['id_usuario']);  echo "<br>";
        validarAdmin($_SESSION['usuario']['id_usuario']);  echo "<br>";
        validarEmpresa($_SESSION['usuario']['id_usuario']);  echo "<br>";
        validarVIP($_SESSION['usuario']['id_usuario']);  echo "<br>";
        validarVigilante($_SESSION['usuario']['id_usuario']);




    ?>

</body>

</html>