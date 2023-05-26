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

    <!-- Agregar enlace a Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

    <style>
        .center-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .button-container {
            margin: 10px;
            text-align: center;
        }


        .blue-button {
            font-size: 24px;
            background-color: blue;
            border: none;
            color: white;
            padding: 20px 40px;
            border-radius: 5px;
            margin: 10px;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <?php
    if (isset($_SESSION['usuario'])) {
        // Menu general
        menu_general();

        ?>

        <div class="center-container">
            <div class="button-container">
                <form action="guias.php">
                    <button type="submit" class="btn btn-primary blue-button">Guías de usuarios</button>
                </form>

            </div>
            <div class="button-container">
                <button class="btn btn-primary blue-button">Soporte técnico</button>
            </div>
        </div>


        <?php
    } else {
        echo ('Acceso denegado');
        print '<a href ="../index.php"><button>Volver</button></a>';
        session_destroy();
    }
    ?>

    <!-- Agregar enlace a Bootstrap JS y jQuery (opcional) -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

</html>