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
    <?php
    if (isset($_SESSION['usuario'])) {
        // Menu general
        menu_general();
        ?>
        <?php

        if (isset($_POST['enviarSoporte'])) {
            try {
                $id_usuario = $_SESSION['usuario']['id_usuario'];
                $soporte = $_POST['opcion'];
                $asunto = $_POST['asunto'];
                $mensaje = $_POST['mensaje'];

                $conn = conectar();

                if (isset($_POST['reportar'])) {
                    $reportar = $_POST['reportar'];

                    $sql = "INSERT INTO soportes (asunto, reportar, mensaje, id_tipo_soporte, id_usuario) 
                    SELECT ?, ?, ?, id_tipo_soporte, ? FROM tipossoportes WHERE nombre = ?";

                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param('sssds', $asunto, $reportar, $mensaje, $id_usuario, $soporte);
                } else {
                    $sql = "INSERT INTO soportes (asunto, mensaje, id_tipo_soporte, id_usuario) 
                    SELECT ?, ?, id_tipo_soporte, ? FROM tipossoportes WHERE nombre = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param('ssds', $asunto, $mensaje, $id_usuario, $soporte);
                }

                if (!$stmt->execute()) {
                    throw new Exception("Error al insertar soporte: " . $stmt->error);
                }


                if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                    $imagen = $_FILES['imagen']['tmp_name'];
                    $contenidoImagen = file_get_contents($imagen);

                    $ultimoIdSoporte = $stmt->insert_id;

                    $sql = "INSERT INTO `fotos`(`foto`, `id_soporte`) VALUES (?, ?)";
                    $stmt2 = $conn->prepare($sql);
                    $stmt2->bind_param("si", $contenidoImagen, $ultimoIdSoporte);

                    if (!$stmt2->execute()) {
                        throw new Exception("Error al subir la imagen: " . $stmt2->error);
                    }

                }
            } catch (Exception $e) {
                echo "<h1>ERROR: " . $e->getMessage() . "</h1>";
            } finally {
                if (isset($stmt)) {
                    $stmt->close();
                }

                if (isset($stmt2)) {
                    $stmt2->close();
                }

                $conn->close();
            }
        }

        // Pendiente
        if (isset($_POST['solicitarEmpresa'])) {
            try {
                $id_usuario = $_SESSION['usuario']['id_usuario'];
                $soporte = $_POST['opcion'];
                $asunto = $_POST['asunto'];
                $mensaje = $_POST['mensaje'];

                $conn = conectar();

                if (isset($_POST['reportar'])) {
                    $reportar = $_POST['reportar'];

                    $sql = "INSERT INTO soportes (asunto, reportar, mensaje, id_tipo_soporte, id_usuario) 
                    SELECT ?, ?, ?, id_tipo_soporte, ? FROM tipossoportes WHERE nombre = ?";

                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param('sssds', $asunto, $reportar, $mensaje, $id_usuario, $soporte);
                } else {
                    $sql = "INSERT INTO soportes (asunto, mensaje, id_tipo_soporte, id_usuario) 
                    SELECT ?, ?, id_tipo_soporte, ? FROM tipossoportes WHERE nombre = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param('ssds', $asunto, $mensaje, $id_usuario, $soporte);
                }

                if (!$stmt->execute()) {
                    throw new Exception("Error al insertar soporte: " . $stmt->error);
                }


                if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                    $imagen = $_FILES['imagen']['tmp_name'];
                    $contenidoImagen = file_get_contents($imagen);

                    $ultimoIdSoporte = $stmt->insert_id;

                    $sql = "INSERT INTO `fotos`(`foto`, `id_soporte`) VALUES (?, ?)";
                    $stmt2 = $conn->prepare($sql);
                    $stmt2->bind_param("si", $contenidoImagen, $ultimoIdSoporte);

                    if (!$stmt2->execute()) {
                        throw new Exception("Error al subir la imagen: " . $stmt2->error);
                    }

                }
            } catch (Exception $e) {
                echo "<h1>ERROR: " . $e->getMessage() . "</h1>";
            } finally {
                if (isset($stmt)) {
                    $stmt->close();
                }

                if (isset($stmt2)) {
                    $stmt2->close();
                }

                $conn->close();
            }
        }

        if (isset($_POST['opcion'])) {
            ?>
            <form action="soporte.php" method="POST" enctype="multipart/form-data">
                <div class="container">
                    <h2>Redacta tu mensaje</h2>
                    <?php
                    $soporte = $_POST['opcion'];
                    if ($soporte == "Preguntas" || $soporte == "Reportar" || $soporte == "Error" || $soporte == "Sugerencia") {
                        echo "<input type='hidden' name='opcion' value='$soporte'>";
                        ?>
                        <div class="form-group">
                            <label for="asunto">Asunto:</label>
                            <input type="text" name="asunto" required>
                        </div>

                        <?php
                        if ($soporte == "Reportar") {
                            ?>
                            <div class="form-group">
                                <label for="usuario">Usuario:</label>
                                <input type="text" name="reportar" required>
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
                        <input type="submit" name='enviarSoporte' value="Enviar">
                    </div>
                </div>
            </form>
            <?php
        } else if (!isset($_REQUEST['solicitarEmpresa'])) {
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

        if (isset($_REQUEST['solicitarEmpresa'])) {
            ?>
            <div class="container">
                <h4>Solicitud para ser empresa</h4><br>
                <form action="soporte.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="cif">CIF</label>
                        <input type="text" class="form-control" id="cif" name="cif">
                    </div>
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre">
                    </div>
                    <div class="form-group">
                        <label for="telefono">Teléfono</label>
                        <input type="tel" class="form-control" id="telefono" name="telefono">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email">
                    </div>
                    <div class="form-group">
                        <label for="direccion">Dirección</label>
                        <input type="text" class="form-control" id="direccion" name="direccion">
                    </div>
                    <div class="form-group">
                        <label for="logo">Subir Logo</label>
                        <input type="file" class="form-control-file" id="logo" name="logo">
                    </div>
                    <div class="form-group">
                        <?php listarTiposEmpresas(); ?>
                    </div>
                    <button type="submit" name='solicitarEmpresa' class="btn btn-primary">Enviar</button>
                </form>
            </div>
            <?php
        }


    } else {
        echo ('Acceso denegado');
        print '<a href ="../index.php"><button>Volver</button></a>';
        session_destroy();
    }
    ?>

</body>

</html>