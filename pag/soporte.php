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
    <link href="../css/soporte.css" rel="stylesheet" type="text/css">
</head>
<body>
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
                    echo "
                    <div class='row justify-content-center fixed-bottom'>
                        <div class='alert alert-danger' role='alert'>
                            <?php throw new Exception('Error a la hora de envio: ' . $stmt->error); ?>
                        </div>
                    </div>
                    ";
                } else {
                    echo "
                    <div class='row justify-content-center fixed-bottom'>
                        <div class='alert alert-primary' role='alert'>
                            Se ha enviado correctamente!
                        </div>
                    </div>
                    ";
                }

                if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                    $imagen = $_FILES['imagen']['tmp_name'];
                    $contenidoImagen = file_get_contents($imagen);

                    $ultimoIdSoporte = $stmt->insert_id;

                    $sql = "INSERT INTO `fotos`(`foto`, `id_soporte`) VALUES (?, ?)";
                    $stmt2 = $conn->prepare($sql);
                    $stmt2->bind_param("si", $contenidoImagen, $ultimoIdSoporte);

                    if (!$stmt2->execute()) { 
                        echo "
                        <div class='row justify-content-center fixed-bottom'>
                            <div class='alert alert-danger' role='alert'>
                                <?php throw new Exception('Error al subir la imagen: ' . $stmt2->error); ?>
                            </div>
                        </div>
                        ";
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

        if (isset($_POST['enviarEmpresa'])) {
            try {
                // Obtener los datos del formulario
                $id_usuario = $_SESSION['usuario']['id_usuario'];
                $cif = $_POST['cif'];
                $nombre = $_POST['nombre'];
                $telefono = $_POST['telefono'];
                $email = $_POST['email'];
                $direccion = $_POST['direccion'];
                $tipoEmpresa = $_POST['tipoEmpresa'];

                $conn = conectar();

                if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {

                    $imagen = $_FILES['logo']['tmp_name'];
                    $contenidoImagen = file_get_contents($imagen);


                    $sql = "INSERT INTO empresas (id_empresa, cif, nombre, telefono, email, direccion, logo, id_tipo_empresa) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

                    $stmt2 = $conn->prepare($sql);
                    $stmt2->bind_param("issssssi", $id_usuario, $cif, $nombre, $telefono, $email, $direccion, $contenidoImagen, $tipoEmpresa);

                    if (!$stmt2->execute()) {
                        echo "
                        <div class='row justify-content-center fixed-bottom'>
                            <div class='alert alert-danger' role='alert'>
                                <?php throw new Exception('Error a la hora de envio: ' . $stmt2->error); ?>
                            </div>
                        </div>
                        ";
                    } else {
                        echo "
                        <div class='row justify-content-center fixed-bottom'>
                            <div class='alert alert-primary' role='alert'>
                                Se ha enviado correctamente!
                            </div>
                        </div>
                        ";
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
                        echo "<input type='hidden' name='opcion' value='$soporte'>
                            <h5>$soporte</h5>
                        ";
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
                        if ($soporte == "Reportar" || $soporte == "Error" || $soporte == "Sugerencia") {
                            ?>
                            <div class='form-group'>
                                <label for="foto">Imagen:</label>
                                <input type='file' name='imagen' accept='image/*' required>
                            </div>
                            <?php
                        }
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
                        <input type="text" class="form-control" id="cif" name="cif" required>
                    </div>
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="telefono">Teléfono</label>
                        <input type="tel" class="form-control" id="telefono" name="telefono" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="direccion">Dirección</label>
                        <input type="text" class="form-control" id="direccion" name="direccion" required>
                    </div>
                    <div class="form-group">
                        <label for="logo">Subir Logo</label>
                        <input type="file" class="form-control-file" id="logo" name="logo" required>
                    </div>
                    <div class="form-group">
                        <?php listarTiposEmpresas(); ?>
                    </div>
                    <button type="submit" name='enviarEmpresa' class="btn btn-primary">Enviar</button>
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