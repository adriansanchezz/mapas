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
        menu_general(); ?>
        
        <!-- Crear submenu con sus opciones -->
        <div class="d-flex vh-100">
            <div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 200px;">
                <br><br>
                <ul class="nav nav-pills flex-column mb-auto">
                    <li>
                        <form action="vigilante.php" method="post">
                            <button type="submit" name="vigilantePrincipal"
                                class="btn btn-link nav-link text-white">Principal
                            </button>
                        </form>
                    </li>
                    <li>
                        <form action="vigilante.php" method="post">
                            <button type="submit" name="misiones" class="btn btn-link nav-link text-white">Misiones
                            </button>
                        </form>
                    </li>
                    <li>
                        <form action="vigilante.php" method="post">
                            <button type="submit" name="recompesas" class="btn btn-link nav-link text-white">Recompensas
                            </button>
                        </form>
                    </li>
                </ul>
            </div>

            <?php 
                $puntos = 0;
                $id_usuario = $_SESSION['usuario']['id_usuario'];
                $conn = conectar();
                $sql = "SELECT * FROM misiones WHERE id_usuario='$id_usuario' AND estado=1";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        if($row['aceptacion'] == 1)
                        {
                            $puntos = $puntos + 10;
                        }
                    }
                }
                
            ?>
            <style>
        .puntos-container {
            background-color: #e9f2fe;
            border: 1px solid #007bff;
            padding: 10px;
            height: 100px;
            border-radius: 5px;
            float: right;
            margin-right: 20px;
            margin-top: 20px;
        }
    </style>
            <div class="puntos-container">
                <h5>Tus puntos:</h5>
                <p><?php echo $puntos; ?></p>
            </div>


            <?php
            if (isset($_REQUEST['vigilantePrincipal'])) {
                ?>
                <div class="flex-grow-1">
                    <div class="p-3" style="display: block;">
                        <h2>Noticias para vigilante</h2><br>
                    </div>
                </div>
                <?php
            }
            ?>

            <?php
            if (isset($_REQUEST['misiones'])) {
                ?>


                <?php mapa("vigilar"); ?>
                <?php

                echo '<input type="submit" class="btn btn-primary" id="solicitarMision" value="Solicitar mision">';
                ?>

                <?php
            }
            if (isset($_REQUEST['solicitarMision'])) {
                function randomMission()
                {

                }
            }
            ?>

            <?php
            if (isset($_REQUEST['recompesas'])) {
                ?>
                < div class="p-3" style="display: block;">
                    <h1>Recompensas</h1>

            </div>
            <?php
            }
            if (isset($_POST['imagenMision'])) {
                $id_mision = $_POST['id_mision'];
                if (isset($_FILES['imagen'])) {
                    if ($_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                        $imagen = $_FILES['imagen']['tmp_name'];
                        $contenidoImagen = file_get_contents($imagen);
                        $conn = conectar();
            
                        // Actualizar campo `fecha_fin`
                        $fechaFin = date('Y-m-d'); // Obtener fecha actual
                        $sqlFechaFin = "UPDATE `misiones` SET `fecha_fin` = ? WHERE `id_mision` = ?";
                        $stmtFechaFin = $conn->prepare($sqlFechaFin);
                        $stmtFechaFin->bind_param("si", $fechaFin, $id_mision);
                        $stmtFechaFin->execute();
            
                        // Insertar imagen en la tabla `fotos`
                        $sqlInsertarFoto = "INSERT INTO `fotos` (`foto`, `id_mision`) VALUES (?, ?)";
                        $stmtInsertarFoto = $conn->prepare($sqlInsertarFoto);
                        $stmtInsertarFoto->bind_param("si", $contenidoImagen, $id_mision);
            
                        // Ejecutar la consulta de inserción
                        if ($stmtInsertarFoto->execute()) {
                            // Actualizar el estado de la misión
                            $sqlUpdate = "UPDATE `misiones` SET `estado` = 1 WHERE `id_mision` = ?";
                            $stmtUpdate = $conn->prepare($sqlUpdate);
                            $stmtUpdate->bind_param("i", $id_mision);
                            $stmtUpdate->execute();
            
                            echo "<script>window.location.href = 'vigilante.php?misiones=';</script>";
                            exit();
                        } else {
                            echo "Error al subir la imagen: " . $stmtInsertarFoto->error;
                        }
                    }
                } else {
                    echo "<h1>ERROR</h1>";
                }
            }
            
            ?>

        </div>
        <?php
    } else {
        echo ('Acceso denegado');
        print '<a href ="../index.php"><button>Volver</button></a>';
        session_destroy();
    }
    ?>

</body>

</html>