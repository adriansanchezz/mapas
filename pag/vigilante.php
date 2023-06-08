<!DOCTYPE html>
<?php
//Importar y abrir session que esta dentro de funciones.php
require_once '../lib/funciones.php';
require_once '../lib/modulos.php';
require_once '../lib/mapa.php';
?>
<html>

<head>
    <!-- Meter informacion general de head -->
    <?php head_info(); ?>
    <script src="../js/funciones.js"></script>
    <script src="../js/mapa.js"></script>
    <title>DisplayAds</title>
    <style>
        #map {
            height: 70vh;
            width: 150vh;
            margin-left: 10vh;
            border: 8px solid #2c3e50;
            /* Color del borde */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            /* Sombra */
        }
    </style>

</head>

<body>
    <style>
        #map {
            z-index: 0;
        }
    </style>
    <?php
    if (isset($_SESSION['usuario']) && validarVigilante($_SESSION['usuario']['id_usuario'])) {
        // Menu general
        menu_general();
        ?>
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
                            <button type="submit" name="recompensas" class="btn btn-link nav-link text-white">Recompensas
                            </button>
                        </form>
                    </li>
                </ul>
            </div>

            <?php
            $puntos = 0;
            $id_usuario = $_SESSION['usuario']['id_usuario'];
            $conn = conectar();
            $sql = "SELECT * FROM usuarios WHERE id_usuario='$id_usuario'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $puntos = $row['puntos'];
                }

            }

            ?>


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
                <div class="flex-grow-1">
                    <?php 

                    mapa("vigilar"); ?>
                </div>

                <?php


            }

            ?>
            <?php
            if (isset($_REQUEST['recompensas'])) {
                ?>
                <div class="flex-grow-1">
                    <div class="p-3" style="display: block;">

                        <h1>RECOMPENSAS</h1>
                        <div class="products">
                            <?php
                            // Consultar los productos desde la base de datos
                            $sql = "SELECT * FROM productos as p, fotos as f 
                            WHERE f.id_producto = p.id_producto
                            AND p.estado = 1 AND p.puntos > 0";

                            $result = sqlSELECT($sql);
                            // Verificar si se encontraron productos
                            if ($result->num_rows > 0) {
                                // Iterar sobre los productos y mostrarlos en la página
                                while ($row = $result->fetch_assoc()) {
                                    echo "<div class='product card border-primary mb-3' style='max-width: 18rem;'>";
                                    echo "<div class='card-body'>";
                                    echo "<h3 class='card-title'>" . $row['nombre'] . "</h3>";
                                    echo "<p class='card-text'>" . $row['descripcion'] . "</p>";
                                    echo "<td><img src='data:image/jpeg;base64," . base64_encode($row['foto']) . "' alt='Imagen del producto' class='img-thumbnail'></td>";
                                    echo "<p class='card-text'>Puntos: " . $row['puntos'] . "</p>";


                                    echo "<form action='vigilante.php' method='post'>";
                                    echo "<input type='hidden' name='product_id' value='" . $row['id_producto'] . "'>";
                                    $sql2 = "SELECT ubicacion, fecha_inicio FROM pedidos WHERE id_usuario = " . $_SESSION['usuario']['id_usuario'] . " AND ubicacion IS NOT NULL ORDER BY fecha_inicio DESC LIMIT 1";
                                    $result2 = sqlSELECT($sql2);
                                    
                                    if ($result2->num_rows > 0) {
                                        $row2 = $result2->fetch_assoc();
                                        $ubicacionReciente = $row2['ubicacion'];
                                        $fechaInicio = $row2['fecha_inicio'];
                                        echo "<input type='text' id='ubicacion-input' name='ubicacion' placeholder='Indica la ubicación a la que enviar el producto' value='" . htmlspecialchars($ubicacionReciente) . "' required>";
                                    } else {
                                        echo "<input type='text' id='ubicacion-input' name='ubicacion' placeholder='Indica la ubicación a la que enviar el producto' required>";
                                    }
                                    echo "<input class='btn btn-primary' type='submit'  name='reclamarRecompensa' value='Reclamar'>";
                                    echo "</form>";
                                    echo "</div>";
                                    echo "</div>";
                                }
                            } else {
                                echo "No se encontraron productos";
                            }
                            if (isset($_GET['mensaje'])) {
                                $mensaje = $_GET['mensaje'];
                                echo "<p>$mensaje</p>";
                            }
                            ?>
                        </div>
                    </div>
                </div>


                <?php
            }
            if (isset($_POST['reclamarRecompensa'])) {
                $id_usuario = $_SESSION['usuario']['id_usuario'];
                $product_id = $_POST['product_id'];
                $ubicacion = $_POST['ubicacion'];
                $sql = "SELECT * FROM productos WHERE id_producto = " . $product_id;
                $result = sqlSELECT($sql);
                $conn = conectar();
                // Si da resultados entonces entra en el if.
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $sql2 = "SELECT * FROM usuarios WHERE id_usuario = " . $id_usuario;
                        $result2 = sqlSELECT($sql2);
                        if ($result2->num_rows > 0) {
                            while ($row2 = $result2->fetch_assoc()) {
                                $puntos_vale = $row['puntos'];
                                $puntos_tiene = $row2['puntos'];
                                if (($puntos_tiene - $puntos_vale) >= 0) {
                                    $precio = $row['puntos'];




                                    $sqlPedido = "INSERT INTO `pedidos` (`puntos`, `fecha_inicio`, `fecha_fin`, `id_usuario`, `ubicacion`) VALUES ($precio, NOW(), NOW(), $id_usuario, '$ubicacion')";
                                    $resultPedido = $conn->query($sqlPedido);

                                    // Obtener el último ID de pedido insertado
                                    $id_pedido = mysqli_insert_id($conn);
                                    $sqlLinea = "INSERT INTO `lineas_pedidos`(`precio`, `cantidad`, `id_producto`, `id_publicidad`, `id_pedido`) VALUES ($precio, 1, $product_id, NULL, $id_pedido)";
                                    $resultLinea = $conn->query($sqlLinea);

                                    $sql2 = "UPDATE usuarios SET puntos = puntos - " . (int) $row['puntos'] . " WHERE id_usuario = " . $_SESSION['usuario']['id_usuario'];
                                    sqlUPDATE($sql2);
                                } else {
                                    $mensaje = "No tienes suficientes puntos para reclamar esta recompensa.";
                                }
                            }
                        }
                    }
                }

                $url = "vigilante.php?recompensas";
                if (isset($mensaje)) {
                    $url .= "&mensaje=" . urlencode($mensaje);
                }

                echo "<script>window.location.href = '$url';</script>";
                exit();
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
            <style>
                .puntos-container {
                    background-color: #e9f2fe;
                    border: 1px solid #007bff;
                    padding: 10px;
                    height: 100px;
                    border-radius: 5px;
                    float: right;
                    margin-right: 20px;
                    margin-top: 6vh;
                }
            </style>
            <div class="puntos-container">
                <h5>Tus puntos:</h5>
                <p>
                    <?php echo $puntos; ?>
                </p>
            </div>

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