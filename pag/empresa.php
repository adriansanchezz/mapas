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
    <title>DisplayAds</title>
    <style>
        .popup-content {
            max-width: 300px;
            /* Ajusta el ancho máximo según tus necesidades */
        }

        .popup-content img {
            max-width: 100%;
            max-height: 300px;
            /* Ajusta la altura máxima según tus necesidades */
        }
    </style>
    <script src="../js/funciones.js"></script>
    <script src="../js/mapa.js"></script>
</head>

<body>
    <?php
    if (isset($_SESSION['usuario']) && validarEmpresa($_SESSION['usuario']['id_usuario'])) {
        // Menu general
        menu_general(); ?>

        <!-- Menu horizontal -->
        <div class="d-flex vh-100">
            <div class="d-flex flex-column flex-shrink-0 p-3 text-white"
                style="width: 200px; background: linear-gradient(10deg, rgb(226, 249, 255), rgb(0, 102, 131));">
                <br><br>
                <!-- Crear submenu con sus opciones -->
                <ul class="nav nav-pills flex-column mb-auto">
                    <!-- <li class="nav-item">
                        <form action="empresa.php" method="post">
                            <button type="submit" name="empresaPrincipal"
                                class="btn btn-link nav-link text-white">Principal</button>
                        </form>
                    </li> -->
                    <li class="nav-item">
                        <form action="empresa.php" method="post">
                            <button type="submit" name="empresaMapa"
                                class="btn btn-link nav-link text-white">Publicitarse</button>
                        </form>
                    </li>
                    <li>
                        <form action="empresa.php" method="post">
                            <button type="submit" name="empresaInfo"
                                class="btn btn-link nav-link text-white">Informacion</button>
                        </form>
                    </li>

                </ul>
            </div>

            <!-- <?php
            if (isset($_REQUEST['empresaPrincipal'])) {
                ?>
                <div class="flex-grow-1">
                    <div id="seccion1" class="p-3" style="display: block;">
                        <h2>Noticias para empresa</h2><br>
                    </div>
                </div>
                <?php
            }
            ?> -->


            <?php
            if (isset($_REQUEST['empresaMapa'])) {
                ?>
                <!-- Se crea toda la maquetación del menú de empresa. -->
                <div class="flex-grow-1">
                    <div class="p-3" style="display: block;">
                        <div class="p-3" style="display: block;">
                            <form class="form-inline my-2 my-lg-0" action="empresa.php" method="post">
                                <button class="btn btn-outline-success my-2 my-sm-0" name="empresaCarrito"
                                    type="submit">Carrito</button>
                            </form>
                            <h1>Bienvenido a nuestro mapa</h1>
                            <h4>Selecciona alguna ubicación para ver información:</h4>
                            <p>¿Quieres buscar una ubicación?</p>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" id="direccion" placeholder="Buscar dirección">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" onclick="buscarDireccion()">Buscar</button>
                                </div>
                            </div>
                            <div id="map"></div>
                            <!-- Se le da estilos al mapa para que quede más estético. -->
                            <style>
                                #map {
                                    height: 70vh;
                                    border: 8px solid #2c3e50;
                                    /* Color del borde */
                                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
                                    /* Sombra */
                                }
                            </style>
                            <?php

                            mapa("ver"); ?>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>

            <?php
            if (isset($_POST['add_to_cart'])) {

                $product_id = $_POST['publicidad_id'];
                $precio = $_POST['precio'];
                $id_empresa = $_SESSION['usuario']['id_usuario'];
                $conn = conectar();
                $sql = "UPDATE publicidades SET comprador = ? WHERE id_publicidad = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ii", $id_empresa, $product_id);
                $stmt->execute();
                $stmt->close();
                $sql = "SELECT * FROM pedidos as p, lineas_pedidos as lp WHERE p.id_pedido = lp.id_pedido AND p.fecha_fin IS NULL AND p.id_usuario = " . $_SESSION['usuario']['id_usuario'] . " AND lp.id_publicidad = " . $product_id . " AND lp.cantidad > 0;";

                if (sqlSELECT($sql)->num_rows > 0) {
                    echo "<script>
                            alert('¡Atención! Este producto ya se encuentra en el carrito.');
                            window.location.href = 'empresa.php?empresaMapa=1';
                        </script>";
                    exit();

                } else {
                    
                    $sql = "SELECT * FROM pedidos as p, lineas_pedidos as lp WHERE p.id_pedido = lp.id_pedido AND p.fecha_fin IS NULL AND p.id_usuario = " . $_SESSION['usuario']['id_usuario'] . " AND lp.id_publicidad IS NOT NULL;";

                    if (sqlSELECT($sql)->num_rows > 0) {
                        $id_pedido = obtenerUltimoIdPedidoPublicidad(); // Obtener el último ID de pedido insertado
                        $sqlLinea = "INSERT INTO `lineas_pedidos`(`precio`, `cantidad`, `id_producto`, `id_publicidad`, `id_pedido`) VALUES ($precio, 1, NULL, $product_id, $id_pedido)";
                        sqlINSERT($sqlLinea);
                    } else {

                        $importe = 0;
                        $fecha_fin = "NULL"; // Asignar NULL a la columna fecha_fin
                        $id_usuario = $_SESSION['usuario']['id_usuario'];

                        $conn = conectar();
                        mysqli_begin_transaction($conn); // Reemplaza $conn con tu conexión a la base de datos
        
                        try {
                            // Insertar el pedido
                            $sqlPedido = "INSERT INTO `pedidos`(`importe`, `fecha_inicio`, `fecha_fin`, `id_usuario`) VALUES ($importe, NOW(), NULL, $id_usuario)";
                            $resultPedido = $conn->query($sqlPedido);
                            
                            // Obtener el último ID de pedido insertado
                            $id_pedido = mysqli_insert_id($conn);
                            
                            // Insertar la línea de pedido
                            $sqlLinea = "INSERT INTO `lineas_pedidos`(`precio`, `cantidad`, `id_publicidad`, `id_pedido`) VALUES ($precio, 1, $product_id, $id_pedido)";
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
                echo "<script>window.location.href = 'empresa.php?empresaMapa=1';</script>";
                exit();
            }

            // Calcular el total de dinero en el carrito
            $totalMoney = 0;
            ?>
            <?php

            if (isset($_POST['remove_from_cart'])) {
                $product_id = trim($_POST['id_publicidad']);

                $conn = conectar();
                $sql = "UPDATE publicidades SET comprador = NULL WHERE id_publicidad = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $product_id);
                $stmt->execute();
                $stmt->close();

                $sql = "SELECT * FROM pedidos AS p JOIN lineas_pedidos AS lp ON p.id_pedido = lp.id_pedido WHERE p.fecha_fin IS NULL AND lp.cantidad > 0 AND p.id_usuario = " . $_SESSION['usuario']['id_usuario'] . ";";

                if (sqlSELECT($sql)->num_rows > 0) {

                    $id_pedido = obtenerUltimoIdPedidoPublicidad(); // Obtener el último ID de pedido insertado
                    $sqlPedido = "DELETE FROM `lineas_pedidos` WHERE id_pedido = " . $id_pedido . " AND id_publicidad = " . $product_id;
                    sqlDELETE($sqlPedido);

                }

                // Redirigir nuevamente al carrito
                echo "<script>window.location.href = 'empresa.php?empresaCarrito=1';</script>";
                exit();
            }

            if (isset($_REQUEST['actualizarPedido'])) {
                $importe = $_REQUEST['importe']; // Obtener el valor de importe desde los parámetros de la solicitud
        
                // Actualizar el pedido en la base de datos utilizando el valor de importe
                $id_pedido = obtenerUltimoIdPedidoPublicidad();
                $sqlPedido = "UPDATE pedidos SET fecha_fin = NOW(), importe = " . $importe . " WHERE id_pedido = " . $id_pedido;
                sqlUPDATE($sqlPedido);
                foreach ($_REQUEST as $key => $value) {
                    if (strpos($key, 'months_') === 0) {
                        // Obtener la ID del producto
                        $product_id = substr($key, 7);

                        // Obtener el valor de los meses seleccionados
                        $selected_months = intval($value);


                        $query = "UPDATE lineas_pedidos 
                        SET precio = (SELECT precio FROM publicidades WHERE id_publicidad = $product_id) * $selected_months 
                        WHERE id_publicidad = $product_id";

                        // Ejecutar la consulta en tu base de datos
                        // ...
                        sqlUPDATE($query);


                        // Realizar la consulta de actualización
                        $fecha_según_mes = date('Y-m-d', strtotime("+$selected_months months"));
                        $query = "UPDATE publicidades SET caducidad_compra = '$fecha_según_mes' WHERE id_publicidad = $product_id";

                        // Ejecutar la consulta en tu base de datos
                        // ...
                        sqlUPDATE($query);
                    }
                }
            }
            ?>


            <?php
            if (isset($_REQUEST['empresaCarrito'])) {
                ?>
                <div class="products">
                    <?php
                    // Verificar si el carrito de compras está almacenado en la sesión
                    $id_pedido = obtenerUltimoIdPedidoPublicidad();
                    $conn = conectar();

                    // Consultar los productos desde la base de datos
                    $sql = "SELECT *
                            FROM lineas_pedidos AS lp
                            INNER JOIN publicidades AS publi ON lp.id_publicidad = publi.id_publicidad
                            INNER JOIN pedidos AS pedido ON lp.id_pedido = pedido.id_pedido
                            WHERE lp.id_pedido = " . intval($id_pedido) . " AND lp.cantidad > 0 AND pedido.fecha_fin IS NULL AND publi.ocupado = 1
                            GROUP BY lp.id_publicidad";

                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        $importe = 0;
                        // Antes del bucle 'while' que itera sobre los productos en el carrito
                        $product_prices = array();

                        // Iterar sobre los productos en el carrito
                        while ($row = $result->fetch_assoc()) {
                            $product_id = $row['id_publicidad'];
                            $product_name = $row['ubicacion'];
                            $product_description = $row['provincia'];
                            $product_price = $row['precio'];

                            // Almacenar el precio del producto en el array asociativo
                            $product_prices[$product_id] = $product_price;

                            // Obtener el valor seleccionado de meses
                            $selected_months = isset($_POST["months_$product_id"]) ? intval($_POST["months_$product_id"]) : 1;

                            // Calcular el subtotal por producto considerando el aumento de precios por mes
                            $subtotal = $product_price * $selected_months;

                            // Agregar el subtotal al total de dinero
                            $importe += $subtotal;

                            // Mostrar los detalles del producto en el carrito
                            echo "<div class='product card border-primary mb-3' style='max-width: 18rem;'>";
                            echo "<div class='card-header bg-primary text-white'>$product_name</div>";
                            echo "<div class='card-body text-primary'>";
                            echo "<h5 class='card-title'>$product_description</h5>";
                            echo "<p class='card-text'>Precio por mes: $product_price €</p>";
                            echo "<p class='card-text'>Meses seleccionados: <span id='months_selected_$product_id'>$selected_months</span></p>";
                            echo "<form action='empresa.php' method='post'>";
                            echo "<input type='hidden' name='id_publicidad' value='$product_id'>";
                            echo "<label for='months_$product_id'>Meses:</label>";
                            echo "<input type='number' name='months_$product_id' id='months_$product_id' min='1' max='12' value='$selected_months' onchange='updateTotalPrice($product_id)'>";
                            echo "<button class='btn btn-danger' name='remove_from_cart' value='$product_id' type='submit'>Eliminar</button>";
                            echo "</form>";
                            echo "<p id='total_price_$product_id'>Subtotal: " . ($product_price * $selected_months) . " €</p>";
                            echo "</div>";
                            echo "</div>";
                        }

                        // Mostrar el total de dinero en el carrito
                        echo "<p id='total_importe'>Total a pagar: $importe €</p>";
                    } else {
                        echo "<div class='col-lg-12'>";
                        echo "<div class='alert alert-info'>El carrito de compras está vacío.</div>";
                        echo "</div>";
                    }



                    ?>
                    <script>
                        function updateTotalPrice(productId) {
                            var productPrices = <?php echo json_encode($product_prices); ?>;
                            var products = document.getElementsByClassName('product');
                            var totalImporte = 0;

                            for (var i = 0; i < products.length; i++) {
                                var product = products[i];
                                var productIdElement = product.querySelector('input[name="id_publicidad"]');
                                var monthsSelector = product.querySelector('input[name^="months_"]');
                                var totalPriceElement = product.querySelector('[id^="total_price_"]');

                                var currentProductId = parseInt(productIdElement.value);
                                var currentProductPrice = parseFloat(productPrices[currentProductId]);
                                var currentSelectedMonths = parseInt(monthsSelector.value);
                                var currentTotalPrice = currentProductPrice * currentSelectedMonths;

                                // Actualizar el precio total del producto
                                totalPriceElement.textContent = 'Subtotal: ' + currentTotalPrice + ' €';

                                // Agregar el precio del producto al importe total
                                totalImporte += currentTotalPrice;
                            }

                            // Mostrar el importe total actualizado
                            var totalImporteElement = document.getElementById('total_importe');
                            totalImporteElement.textContent = 'Total a pagar: ' + totalImporte + ' €';
                        }
                    </script>
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
                            var productPrices = <?php echo json_encode($product_prices); ?>;
                            var products = document.getElementsByClassName('product');
                            var totalImporte = 0;

                            for (var i = 0; i < products.length; i++) {
                                var product = products[i];
                                var productIdElement = product.querySelector('input[name="id_publicidad"]');
                                var monthsSelector = product.querySelector('input[name^="months_"]');

                                var currentProductId = parseInt(productIdElement.value);
                                var currentProductPrice = parseFloat(productPrices[currentProductId]);
                                var currentSelectedMonths = parseInt(monthsSelector.value);
                                var currentTotalPrice = currentProductPrice * currentSelectedMonths;

                                // Agregar el precio del producto al importe total
                                totalImporte += currentTotalPrice;
                            }

                            return actions.order.create({
                                purchase_units: [{
                                    amount: {
                                        value: totalImporte.toFixed(2) // Redondear el importe a 2 decimales
                                    }
                                }]
                            })
                        },

                        onApprove: function (data, actions) {
                            actions.order.capture().then(function (detalles) {
                                var xhr = new XMLHttpRequest();
                                var productPrices = <?php echo json_encode($product_prices); ?>;
                                var products = document.getElementsByClassName('product');
                                var totalImporte = 0;

                                for (var i = 0; i < products.length; i++) {
                                    var product = products[i];
                                    var productIdElement = product.querySelector('input[name="id_publicidad"]');
                                    var monthsSelector = product.querySelector('input[name^="months_"]');

                                    var currentProductId = parseInt(productIdElement.value);
                                    var currentProductPrice = parseFloat(productPrices[currentProductId]);
                                    var currentSelectedMonths = parseInt(monthsSelector.value);
                                    var currentTotalPrice = currentProductPrice * currentSelectedMonths;

                                    // Agregar el precio del producto al importe total
                                    totalImporte += currentTotalPrice;
                                }
                                var importe = totalImporte; // Obtener el valor de $importe en JavaScript

                                // Obtener los meses y las IDs de productos seleccionados
                                var mesesSeleccionados = document.querySelectorAll('input[type="number"]');
                                var parametros = 'actualizarPedido&importe=' + importe;

                                // Agregar los meses y las IDs de productos a los parámetros de la solicitud
                                for (var i = 0; i < mesesSeleccionados.length; i++) {
                                    var product_id = mesesSeleccionados[i].getAttribute('id').substring(7);
                                    var selected_months = mesesSeleccionados[i].value;

                                    parametros += '&months_' + product_id + '=' + selected_months;
                                }

                                xhr.open('GET', 'empresa.php?' + parametros, true);
                                xhr.onreadystatechange = function () {
                                    if (xhr.readyState === 4 && xhr.status === 200) {

                                        window.location.href = 'empresa.php?empresaCarrito=1';
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
            if (isset($_REQUEST['empresaInfo'])) {
                echo "<div class='container'>";
                echo "<div class='row'>";
                echo "<div class='col-md-6'>";
                echo "<h2>Información general</h2>";
                $sql = "SELECT * FROM empresas WHERE id_empresa = " . $_SESSION['usuario']['id_usuario'];
                $result = sqlSELECT($sql);
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    echo "<p><strong>CIF:</strong> " . $row['cif'] . "</p>";
                    echo "<p><strong>Nombre:</strong> " . $row['nombre'] . "</p>";
                    echo "<p><strong>Telefono:</strong> " . $row['telefono'] . "</p>";
                    echo "<p><strong>Email:</strong> " . $row['email'] . "</p>";
                    echo "<p><strong>Direccion:</strong> " . $row['direccion'] . "</p>";
                }
                echo "</div>";

                $sql = "SELECT p.ubicacion, p.codigo_postal, f.foto, u.email, p.caducidad_compra
                        FROM publicidades as p, usuarios as u, fotos as f
                        WHERE p.id_usuario = u.id_usuario
                            AND f.id_publicidad = p.id_publicidad
                            AND p.comprador = " . $_SESSION['usuario']['id_usuario'] . "
                            AND p.ocupado = 1
                            AND p.caducidad_compra IS NOT NULL";
                $result = sqlSELECT($sql);

                echo "<div class='col-md-6'>";
                echo "<h2>Ubicaciones alquiladas</h2>";
                if ($result->num_rows > 0) {
                    echo "<table class='table table-striped table-bordered'>";
                    echo "<thead class='thead-dark'>";
                    echo "<tr>";
                    echo "<th>Ubicación</th>";
                    echo "<th>Código Postal</th>";
                    echo "<th>Foto</th>";
                    echo "<th>Dueño</th>";
                    echo "<th>Fecha de Finalización</th>";
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";

                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['ubicacion'] . "</td>";
                        echo "<td>" . $row['codigo_postal'] . "</td>";
                        echo "<td><img src='data:image/jpeg;base64," . base64_encode($row['foto']) . "' alt='Imagen del producto' class='img-thumbnail'></td>";
                        echo "<td>" . $row['email'] . "</td>";
                        echo "<td>" . $row['caducidad_compra'] . "</td>";
                        echo "</tr>";
                    }

                    echo "</tbody>";
                    echo "</table>";
                } else {
                    echo "<p>No se encontraron ubicaciones alquiladas.</p>";
                }
                echo "</div>";
                echo "</div>";
                echo "</div>";
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