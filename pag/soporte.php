<!DOCTYPE html>
<?php
//Importar y abrir session que esta dentro de funciones.php
require_once '../lib/funciones.php';
require_once '../lib/modulos.php';
?>
<html>

<head>
    <!-- Meter informacion general de head -->
    <?php head_info(); ?>
    <title>DisplayAds</title>

    <!-- Agregar enlace a Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<body>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
        }

        .container {
            text-align: center;
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 100px;
        }

        .container h2 {
            text-align: center;
            color: #333333;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #333333;
        }

        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #dddddd;
            border-radius: 4px;
        }

        .form-group textarea {
            height: 100px;
        }

        .form-group input[type="submit"] {
            background-color: #007bff;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
        }

        .form-group input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
    <div class="separar">
        <?php
        if (isset($_SESSION['usuario'])) {
            // Menu general
            menu_general();
            ?>
        </div>

        <?php

        if (isset($_POST['opcion'])) {
            ?>
            <form action="procesar_ticket.php" method="POST">
                <div class="container">
                    <h2>Redacta tu mensaje</h2>
                    <?php
                    $soporte = $_POST['opcion'];
                    if ($soporte == "Preguntas" || $soporte == "Reportar" || $soporte == "Error" || $soporte == "Sugerencia") {
                        ?>
                        <div class="form-group">
                            <label for="asunto">Asunto:</label>
                            <input type="text" id="asunto" name="asunto" required>
                        </div>

                        <?php
                        if ($soporte == "Reportar") {
                            ?>
                            <div class="form-group">
                                <label for="usuario">Usuario:</label>
                                <input type="text" id="usuario" name="usuario" required>
                            </div>
                            <?php
                        }
                        ?>

                        <div class="form-group">
                            <label for="mensaje">Mensaje:</label>
                            <textarea id="mensaje" name="mensaje" required></textarea>
                        </div>

                        <?php
                        if ($soporte == "Reportar" || $soporte == "Error") {
                            ?>
                            <div class='form-group'>
                                <label for="foto">Imagen:</label>
                                <input type='file' name='imagen' accept='image/*' required>
                            </div>
                            <?php
                        }
                        ?>
                        <?php
                    }
                    ?>
                    <div class="form-group">
                        <input type="submit" value="Enviar">
                    </div>
                </div>
            </form>
            <?php
        } else {
            ?>
            <div class="container">
                <h2>Ayuda y Soporte</h2>

                <div class="description">Guía de Usuario:</div>
                <form action="guias.php">
                    <button type="submit" class="btn btn-info">Guías de usuarios</button>
                </form>

                <br>
                <div class="description">Tipo de soporte y envíe su mensaje:</div>
                <form action="soporte.php" method="POST">
                    <div class="input-group">
                        <?php listarTiposSoportes(); ?>
                        <div class="input-group-append">
                            <input type='submit' name='aceptarMision' class='btn btn-success' value='Aceptar'>
                        </div>
                    </div>
                </form>
            </div>

            <?php
        }

        if (isset($_POST['enviarSoporte'])) {
            $nombreProducto = $_POST['nombre'];
            $descripcionProducto = $_POST['descripcion'];
            $precioProducto = $_POST['precio'];


            $conn = conectar();
            $sql = "INSERT INTO `productos`(`nombre`, `descripcion`, `precio`) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            // Y mediante un bind_param se establecen los valores.
            $stmt->bind_param('ssd', $nombreProducto, $descripcionProducto, $precioProducto);
            // Se ejecuta la consulta.
            $stmt->execute();

            // Verificar si la inserción fue exitosa.
            if ($stmt->affected_rows > 0) {

                if (isset($_FILES['imagen'])) {
                    if ($_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                        $imagen = $_FILES['imagen']['tmp_name'];
                        $contenidoImagen = file_get_contents($imagen);
                        $ultimoIdProducto = $stmt->insert_id;
                        $sql = "INSERT INTO `fotos`(`foto`, `id_producto`) VALUES (?, ?)";
                        $stmt2 = $conn->prepare($sql);
                        $stmt2->bind_param("si", $contenidoImagen, $ultimoIdProducto);
                        // Ejecutar la consulta
                        if ($stmt2->execute()) {
                            echo "<script>window.location.href = 'administrador.php?administradorProductos=';</script>";
                            exit();
                        } else {
                            echo "Error al subir la imagen: " . $stmt->error;
                        }

                    }
                } else {
                    echo "<h1>ERROR</h1>";
                }

            } else {
                // Si no lo fue, se indica un error.
                echo "Error al guardar el marcador.";
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