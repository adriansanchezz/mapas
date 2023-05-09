<!DOCTYPE html>
<?php
require_once '../lib/functiones.php';
require_once '../lib/modulos.php';
?>
<html>

<head>
    <meta charset="UTF-8">
    <title>DisplayAds - Confirmar Compra</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body>
    <h1>Confirmar Compra</h1>

    <?php


    if (isset($_POST['compraUbicacion']) && $_POST['compraUbicacion'] == '1') {
        $lat = $_POST['lat'];
        $lng = $_POST['lng'];
        $titulo = $_POST['titulo'];
        $texto = $_POST['texto'];

        // Mostrar los datos de la ubicación seleccionada
        echo "<p><strong>Ubicación:</strong> $titulo</p>";
        echo "<p><strong>Descripción:</strong> $texto</p>";
        echo "<p><strong>Latitud:</strong> $lat</p>";
        echo "<p><strong>Longitud:</strong> $lng</p>";







        ?>
        <p>¿Confirmar compra?</p>
        <form method="post" action="confirmarCompra.php">
            <input type="hidden" name="compraUbicacion" value="true">
            <input type="hidden" name="lat" value="<?php echo $lat; ?>">
            <input type="hidden" name="lng" value="<?php echo $lng; ?>">
            <input type="hidden" name="titulo" value="<?php echo $titulo; ?>">
            <input type="hidden" name="texto" value="<?php echo $texto; ?>">
            <input type="submit" value="Confirmar">
        </form>
        <?php
    }
    ?>





</body>

</html>