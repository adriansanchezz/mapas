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
    <link href="../css/usuario.css" rel="stylesheet" type="text/css">
    <script src="../js/mapa.js"></script>
    <title>DisplayAds</title>

</head>

<body>
    <?php
    if (isset($_SESSION['usuario']) && validarUsuario($_SESSION['usuario']['id_usuario'])) {
        // Menu general
        menu_general();
        ?>

        <!-- Crear submenu con sus opciones -->
        <div class="d-flex vh-100">
            <div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 200px;">
                <br><br>

                <ul class="nav nav-pills flex-column mb-auto">
                    <!-- <li class="nav-item">
                        <form action="usuario.php" method="post">
                            <button type="submit" name="usuarioPrincipal"
                                class="btn btn-link nav-link text-white">Principal</button>
                        </form>
                    </li> -->
                    <li>
                        <form action="usuario.php" method="post">
                            <button type="submit" name="usuarioMapa" class="btn btn-link nav-link text-white">Mapa</button>
                        </form>
                    </li>
                    <li>
                        <form action="usuario.php" method="post">
                            <button type="submit" name="usuarioTienda"
                                class="btn btn-link nav-link text-white">Tienda</button>
                        </form>
                    </li>
                </ul>
            </div>

            <?php
            if (isset($_REQUEST['usuarioPrincipal'])) {
                ?>
                <div class="flex-grow-1">
                    <div class="p-3" style="display: block;">
                        <h2>Noticias para usuario</h2><br>
                    </div>
                </div>
                <?php
            }
            ?>

            <?php
            if (isset($_REQUEST['usuarioTienda'])) {
                ?>
                <div class="flex-grow-1">
                    <div class="p-3" style="display: block;">
                        <form class="form-inline my-2 my-lg-0" action="usuario.php" method="post">
                            <button class="btn btn-outline-success my-2 my-sm-0" name="usuarioCarrito"
                                type="submit">Carrito</button>
                        </form>
                        <h1>TIENDA</h1>
                        <div class="container">
                            <div class="row">
                                <?php
                                // Consultar los productos desde la base de datos
                                $sql = "SELECT * FROM productos as p, fotos as f 
                                WHERE f.id_producto = p.id_producto
                                AND p.recompensa = 0
                                AND p.estado = 1";

                                $result = sqlSELECT($sql);

                                // Verificar si se encontraron productos
                                if ($result->num_rows > 0) {
                                    // Iterar sobre los productos y mostrarlos en la página
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<div class='col-lg-4 mb-3'>";
                                        echo "<div class='card border-primary'>";
                                        echo "<div class='card-body'>";
                                        echo "<h3 class='card-title'>" . $row['nombre'] . "</h3>";
                                        echo "<p class='card-text'>" . $row['descripcion'] . "</p>";
                                        $imagen = $row["foto"];

                                        // Mostrar la imagen en la página
                                        echo "<img src='data:image/jpeg;base64," . base64_encode($imagen) . "' alt='Imagen del producto'>";
                                        echo "<p class='card-text'>Precio: $" . $row['precio'] . "</p>";
                                        echo "<form action='usuario.php' method='post'>";
                                        echo "<input type='hidden' name='id_producto' value='" . $row['id_producto'] . "'>";
                                        echo "<input type='hidden' name='precio' value='" . $row['precio'] . "'>";
                                        echo "<input class='btn btn-primary' type='submit' name='add_to_cart' value='Agregar al carrito'>";
                                        echo "</form>";
                                        echo "</div>";
                                        echo "</div>";
                                        echo "</div>";
                                    }
                                } else {
                                    echo "No se encontraron productos";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
            }


            ?>
            <?php
            if (isset($_POST['add_to_cart'])) {
                $product_id = $_POST['id_producto'];
                $precio = $_POST['precio'];

                $sql = "SELECT * FROM pedidos as p, lineas_pedidos as lp WHERE p.id_pedido = lp.id_pedido AND p.fecha_fin IS NULL AND p.id_usuario = " . $_SESSION['usuario']['id_usuario'] . " AND lp.id_producto = " . $product_id . ";";

                if (sqlSELECT($sql)->num_rows > 0) {
                    $id_pedido = obtenerUltimoIdPedido(); // Obtener el último ID de pedido insertado
        
                    $sqlPedido = "UPDATE lineas_pedidos SET cantidad = cantidad + 1 WHERE id_pedido = " . $id_pedido . " AND id_producto = " . $product_id;
                    sqlUPDATE($sqlPedido);

                } else {
                    $sql = "SELECT * FROM pedidos as p, lineas_pedidos as lp WHERE p.id_pedido = lp.id_pedido AND p.fecha_fin IS NULL AND p.id_usuario = " . $_SESSION['usuario']['id_usuario'] . " AND lp.id_producto IS NOT NULL;";

                    if (sqlSELECT($sql)->num_rows > 0) {
                        $id_pedido = obtenerUltimoIdPedido(); // Obtener el último ID de pedido insertado
                        $sqlLinea = "INSERT INTO `lineas_pedidos`(`precio`, `cantidad`, `id_producto`, `id_publicidad`, `id_pedido`) VALUES ($precio, 1, $product_id, NULL, $id_pedido)";
                        sqlINSERT($sqlLinea);
                    } else {

                        $importe = 0;
                        $fecha_fin = "NULL"; // Asignar NULL a la columna fecha_fin
                        $id_usuario = $_SESSION['usuario']['id_usuario'];

                        // Iniciar una transacción
                        $conn = conectar();
                        mysqli_begin_transaction($conn); // Reemplaza $conn con tu conexión a la base de datos
        
                        try {
                            // Insertar el pedido
                            $sqlPedido = "INSERT INTO `pedidos`(`importe`, `fecha_inicio`, `fecha_fin`, `id_usuario`) VALUES ($importe, NOW(), $fecha_fin, $id_usuario)";
                            $resultPedido = $conn->query($sqlPedido);

                            // Obtener el último ID de pedido insertado
                            $id_pedido = mysqli_insert_id($conn);

                            // Insertar la línea de pedido
                            $sqlLinea = "INSERT INTO `lineas_pedidos`(`precio`, `cantidad`, `id_producto`, `id_publicidad`, `id_pedido`) VALUES ($precio, 1, $product_id, NULL, $id_pedido)";
                            $resultLinea = $conn->query($sqlLinea);

                            // Confirmar la transacción
                            mysqli_commit($conn);
                        } catch (Exception $e) {
                            // Ocurrió un error, revertir la transacción
                            mysqli_rollback($conn);
                            // Manejar el error adecuadamente
                            echo "Error: " . $e->getMessage();
                        }
                    }
                }

                echo "<script>window.location.href = 'usuario.php?usuarioTienda';</script>";
                exit();
            }


            ?>

            <?php
            if (isset($_POST['remove_from_cart'])) {
                $product_id = $_POST['id_producto'];


                $sql = "SELECT * FROM pedidos AS p JOIN lineas_pedidos AS lp ON p.id_pedido = lp.id_pedido WHERE p.fecha_fin IS NULL AND lp.cantidad > 0 AND p.id_usuario = " . $_SESSION['usuario']['id_usuario'] . ";";

                if (sqlSELECT($sql)->num_rows > 0) {

                    $id_pedido = obtenerUltimoIdPedido(); // Obtener el último ID de pedido insertado
                    $sqlPedido = "UPDATE lineas_pedidos SET cantidad = cantidad - 1 WHERE id_pedido = " . $id_pedido . " AND id_producto = " . $product_id;
                    sqlUPDATE($sqlPedido);

                }

                // Redirigir nuevamente al carrito
                echo "<script>window.location.href = 'usuario.php?usuarioCarrito=1';</script>";
                exit();

            }

            if (isset($_REQUEST['actualizarPedido'])) {
                $importe = $_GET['importe'];
                $ubicacion = $_GET['ubicacion'];

                // Actualizar el pedido en la base de datos utilizando el valor de importe y ubicacion
                $id_pedido = obtenerUltimoIdPedido();
                $sqlPedido = "UPDATE pedidos SET fecha_fin = NOW(), importe = '" . $importe . "', ubicacion = '" . $ubicacion . "' WHERE id_pedido = " . $id_pedido;
                sqlUPDATE($sqlPedido);
            }

            ?>



            <?php

            if (isset($_REQUEST['usuarioCarrito'])) {
                ?>
                <div class="flex-grow-1">
                    <div id="seccion1" class="p-3" style="display: block;">
                        <h1>Carrito</h1>
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="products">
                                        <?php
                                        // Verificar si el carrito de compras está almacenado en la sesión
                                        $id_pedido = obtenerUltimoIdPedido();
                                        $conn = conectar();

                                        // Consultar los productos desde la base de datos
                                        $sql = "SELECT lp.id_producto, lp.cantidad, prod.nombre, prod.descripcion, prod.precio
                                                FROM lineas_pedidos AS lp
                                                INNER JOIN productos AS prod ON lp.id_producto = prod.id_producto
                                                INNER JOIN pedidos AS pedido ON lp.id_pedido = pedido.id_pedido
                                                WHERE lp.id_pedido = " . intval($id_pedido) . " AND lp.cantidad > 0 AND pedido.fecha_fin IS NULL
                                                GROUP BY lp.id_producto";

                                        $result = $conn->query($sql);

                                        if ($result->num_rows > 0) {
                                            $importe = 0;

                                            // Iterar sobre los productos en el carrito
                                            while ($row = $result->fetch_assoc()) {
                                                $product_id = $row['id_producto'];
                                                $product_name = $row['nombre'];
                                                $product_description = $row['descripcion'];
                                                $product_price = $row['precio'];
                                                $product_quantity = $row['cantidad'];

                                                // Calcular el subtotal por producto
                                                $subtotal = $product_price * $product_quantity;
                                                if (validarVIP($_SESSION['usuario']['id_usuario'])) {
                                                    $subtotal = $subtotal * 0.95;
                                                }
                                                $importe += $subtotal;

                                                // Mostrar los detalles del producto en el carrito
                                                echo "<div class='col-lg-4 mb-4'>";
                                                echo "<div class='card'>";
                                                echo "<div class='card-header bg-primary text-white'>$product_name</div>";
                                                echo "<div class='card-body'>";
                                                echo "<h5 class='card-title'>$product_description</h5>";
                                                echo "<p class='card-text'>Precio: $product_price</p>";
                                                echo "<p class='card-text'>Cantidad: $product_quantity</p>";
                                                echo "<p class='card-text'>Subtotal: $subtotal €</p>";

                                                echo "<form action='usuario.php' method='post'>";
                                                echo "<input type='hidden' name='id_producto' value='$product_id'>";
                                                echo "<button class='btn btn-danger' name='remove_from_cart' type='submit'>Eliminar</button>";
                                                echo "</form>";
                                                echo "</div>";
                                                echo "</div>";
                                                echo "</div>";
                                            }
                                            
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
                                            echo "<div id='mensajeUbicacion'></div>";
                                            // Mostrar el total de dinero en el carrito
                                            echo "<div class='col-lg-12 mt-3'>";
                                            echo "<div class='card'>";
                                            echo "<div class='card-body'>";
                                            echo "<h5 class='card-title'>Total a pagar:</h5>";
                                            echo "<p class='card-text'>$importe € ";
                                            if (validarVIP($_SESSION['usuario']['id_usuario'])) {
                                                echo "<small><i>*Se ha aplicado un descuento de 5% para usuario VIP</i></small>";
                                            }
                                            echo "</p>";
                                            echo "</div>";
                                            echo "</div>";
                                            echo "</div>";
                                        } else {
                                            echo "<div class='col-lg-12'>";
                                            echo "<div class='alert alert-info'>El carrito de compras está vacío.</div>";
                                            echo "</div>";
                                        }

                                        mysqli_close($conn);
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <script
                    src="https://www.paypal.com/sdk/js?client-id=Ae-QOggCqT3W10C1Q7U1lTDaYwmgEsmPuPxDuQEOD4uHZK0DMvJb2brCahcG-HMPPBti9IsX8pCsB-Db&currency=EUR"></script>
                <!-- Set up a container element for the button -->
                <div id="paypal-button-container"></div>
                <script>
                    paypal.Buttons({
                        style: {
                            color: 'blue',
                            shape: 'pill',
                            label: 'pay'
                        },
                        createOrder: function (data, actions) {
                            return actions.order.create({
                                purchase_units: [{
                                    amount: {
                                        value: '<?php echo $importe; ?>'
                                    }
                                }]
                            });
                        },
                        onApprove: function (data, actions) {
                            var ubicacionInput = document.getElementById('ubicacion-input');
                            var ubicacion = ubicacionInput.value;

                            // Validar que el campo de ubicación no esté vacío
                            if (ubicacion.trim() === '') {
                                
                                document.getElementById('mensajeUbicacion').innerHTML = 'Debes indicar la ubicación antes de continuar';
                                document.getElementById('mensajeUbicacion').style.color = 'red';
                                return;
                            }

                            // Realizar la captura del pago y actualizar el pedido
                            actions.order.capture().then(function (detalles) {
                                var xhr = new XMLHttpRequest();
                                var importe = '<?php echo $importe; ?>'; // Obtener el valor de $importe en JavaScript
                                xhr.open('GET', 'usuario.php?actualizarPedido&importe=' + importe + '&ubicacion=' + encodeURIComponent(ubicacion), true);
                                xhr.onreadystatechange = function () {
                                    if (xhr.readyState === 4 && xhr.status === 200) {
                                        console.log('El pedido se actualizó correctamente.');
                                        window.location.href = 'usuario.php?usuarioCarrito=1';
                                        exit();
                                    }
                                };
                                xhr.send();
                            });
                        },
                        onCancel: function (data) {
                            alert("Pago cancelado");
                        }
                    }).render('#paypal-button-container');
                </script>

                <?php
            }

            ?>

            <?php
            if (isset($_REQUEST['usuarioMapa'])) {
                ?>
                <div class="container">
                    <div class="row">
                        <div class="col-md-9">
                            <div class="p-3">
                                <h1 class="text-primary">MAPA</h1>
                                <p>¿Quieres buscar una ubicación?</p>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" id="direccion" placeholder="Buscar dirección">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" onclick="buscarDireccion()">Buscar</button>
                                    </div>
                                </div>
                                <div id="map"></div>
                            </div>
                        </div>
                        <style>
                            #map {
                                height: 70vh;

                                border: 8px solid #2c3e50;
                                /* Color del borde */
                                box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
                                /* Sombra */
                            }

                            #imagenPopUp {
                                width: 30vh;
                            }
                        </style>
                        <?php mapa("guardar"); ?>
                    </div>
                </div>


                <?php
            }
            ?>
            <?php
            if (isset($_REQUEST['guardarMarcador'])) {
                guardarMarcador();
            }
            if (isset($_POST['borrarMarcador'])) {
                $id = $_POST['id_publicidad'];
                $conn = conectar();
                $sql = "DELETE FROM `misiones` WHERE id_publicidad =" . $id . ";";
                $resultado = mysqli_query($conn, $sql);

                $sql2 = "DELETE FROM `publicidades` WHERE id_publicidad =" . $id . ";";
                $resultado2 = mysqli_query($conn, $sql2);

                if ($resultado2) {
                    echo "<script>window.location.href = 'usuario.php?usuarioMapa=';</script>";
                    exit();
                } else {
                    echo "Error al ejecutar la consulta de eliminación: " . mysqli_error($conn);
                }
            }

            if (isset($_POST['compraUbicacion'])) {
                // Obtener los datos de la ubicación seleccionada desde la solicitud POST
                $latitud = $_POST['lat'];
                $longitud = $_POST['lng'];
                $ubicacion = $_POST['ubicacion'];
                $descripcion = $_POST['descripcion'];

                // Agregar los datos de la ubicación al carrito (puedes adaptar esta lógica según tu implementación)
                // Por ejemplo, puedes almacenar los datos en un array o en la sesión
                $carrito[] = array(
                    'latitud' => $latitud,
                    'longitud' => $longitud,
                    'ubicacion' => $ubicacion,
                    'descripcion' => $descripcion
                );

                // Redirigir nuevamente a la página del carrito
                header("Location: empresa.php?empresaCarrito=1");
                exit();
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