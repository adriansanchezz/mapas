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
    <div class="center-container">
        <div class="button-container">
            <form action="guias.php">
                <button type="submit" class="btn btn-primary blue-button">Guías de usuarios</button>
            </form>
        </div>

        <div class="button-container">
            <button class="btn btn-primary blue-button">Soporte técnico</button>
        </div>
        
        <?php
        if (isset($_SESSION['usuario'])) {
            // Menu general
            menu_general();
            ?>
            <form action="soporte.php" method="POST">
                <?php listarTiposSoportes(); ?>
                <input type='submit' name='aceptarMision' class='btn btn-success' value='Aceptar'>
            </form>

            ?>
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

                    </div>
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