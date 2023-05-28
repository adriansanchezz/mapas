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
</head>

<body>
    <?php
    if (isset($_SESSION['usuario'])) {
        // Menu general
        menu_general(); ?>
        <!-- Menu horizontal -->
        <div class="d-flex vh-100">
            <div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 200px;">
                <br><br>
                <!-- Crear submenu con sus opciones -->
                <ul class="nav nav-pills flex-column mb-auto">
                    <li class="nav-item">
                        <form action="empresa.php" method="post">
                            <button type="submit" name="empresaPrincipal"
                                class="btn btn-link nav-link text-white">Principal</button>
                        </form>
                    </li>
                    <li class="nav-item">
                        <form action="empresa.php" method="post">
                            <button type="submit" name="empresaMapa"
                                class="btn btn-link nav-link text-white">Publicitarse</button>
                        </form>
                    </li>
                    <li>
                        <form action="empresa.php" method="post">
                            <button type="submit" name="empresaVigia"
                                class="btn btn-link nav-link text-white">Informacion</button>
                        </form>
                    </li>

                </ul>
            </div>

            <?php
            if (isset($_REQUEST['empresaPrincipal'])) {
                ?>
                <div class="flex-grow-1">
                    <div id="seccion1" class="p-3" style="display: block;">
                        <h2>Noticias para empresa</h2><br>
                    </div>
                </div>
                <?php
            }
            ?>


            <?php
            if (isset($_REQUEST['empresaMapa'])) {
                mapa("ver");
            }


            if (isset($_POST['add_to_cart'])) {
                $product_id = $_POST['product_id'];
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
                        $id_pedido = obtenerUltimoIdPedido(); // Obtener el último ID de pedido insertado
                        $sqlLinea = "INSERT INTO `lineas_pedidos`(`precio`, `cantidad`, `id_producto`, `id_publicidad`, `id_pedido`) VALUES ($precio, 1, NULL, $product_id, $id_pedido)";
                        sqlINSERT($sqlLinea);
                    } else {
                        $cantidad = 0;
                        $importe = 0;
                        $fecha_fin = "NULL"; // Asignar NULL a la columna fecha_fin
                        $id_usuario = $_SESSION['usuario']['id_usuario'];

                        $sqlPedido = "INSERT INTO `pedidos`(`cantidad`, `importe`, `fecha_fin`, `id_usuario`) VALUES ($cantidad, $importe, $fecha_fin, $id_usuario)";
                        sqlINSERT($sqlPedido);


                        $id_pedido = obtenerUltimoIdPedido(); // Obtener el último ID de pedido insertado
                        $sqlLinea = "INSERT INTO `lineas_pedidos`(`precio`, `cantidad`, `id_producto`, `id_publicidad`, `id_pedido`) VALUES ($precio, 1, NULL, $product_id, $id_pedido)";
                        sqlINSERT($sqlLinea);
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
                $product_id = trim($_POST['product_id']);
                
                $conn = conectar();
                $sql = "UPDATE publicidades SET comprador = NULL WHERE id_publicidad = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $product_id);
                $stmt->execute();
                $stmt->close();

                $sql = "SELECT * FROM pedidos AS p JOIN lineas_pedidos AS lp ON p.id_pedido = lp.id_pedido WHERE p.fecha_fin IS NULL AND lp.cantidad > 0 AND p.id_usuario = " . $_SESSION['usuario']['id_usuario'] . ";";

                if (sqlSELECT($sql)->num_rows > 0) {

                    $id_pedido = obtenerUltimoIdPedido(); // Obtener el último ID de pedido insertado
                    $sqlPedido = "UPDATE lineas_pedidos SET cantidad = cantidad - 1 WHERE id_pedido = " . $id_pedido . " AND id_publicidad = " . $product_id;
                    sqlUPDATE($sqlPedido);

                }

                // Redirigir nuevamente al carrito
                echo "<script>window.location.href = 'empresa.php?empresaCarrito=1';</script>";
                exit();
            }

            if (isset($_REQUEST['actualizarPedido'])) {

                $id_pedido = obtenerUltimoIdPedido(); // Obtener el último ID de pedido insertado
                $sqlPedido = "UPDATE pedidos SET fecha_fin = NOW() WHERE id_pedido = " . $id_pedido;
                sqlUPDATE($sqlPedido);


            }
            ?>


            <?php
            if (isset($_REQUEST['empresaCarrito'])) {
                ?>
                <div class="products">
                    <?php
                    // Verificar si el carrito de compras está almacenado en la sesión
                    $id_pedido = obtenerUltimoIdPedido();
                    $conn = conectar();

                    // Consultar los productos desde la base de datos
                    $sql = "SELECT *
                            FROM lineas_pedidos AS lp
                            INNER JOIN publicidades AS publi ON lp.id_publicidad = publi.id_publicidad
                            INNER JOIN pedidos AS pedido ON lp.id_pedido = pedido.id_pedido
                            WHERE lp.id_pedido = " . $id_pedido . " AND lp.cantidad > 0 AND pedido.fecha_fin IS NULL
                            GROUP BY lp.id_publicidad";

                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        // Iterar sobre los productos en el carrito
                        while ($row = $result->fetch_assoc()) {
                            $product_id = $row['id_publicidad'];
                            $product_name = $row['ubicacion'];
                            $product_description = $row['provincia'];
                            $product_price = $row['precio'];

                            // Calcular el subtotal por producto
                            $subtotal = $product_price;

                            // Agregar el subtotal al total de dinero
                            $totalMoney += $subtotal;

                            // Mostrar los detalles del producto en el carrito
                            echo "<div class='product card border-primary mb-3' style='max-width: 18rem;'>";
                            echo "<div class='card-header bg-primary text-white'>$product_name</div>";
                            echo "<div class='card-body text-primary'>";
                            echo "<h5 class='card-title'>$product_description</h5>";
                            echo "<p class='card-text'>Precio: $product_price</p>";
                            echo "<form action='empresa.php' method='post'>";
                            echo "<input type='hidden' name='product_id' value='$product_id'>";
                            echo "<button class='btn btn-danger' name='remove_from_cart' value='$product_id' type='submit'>Eliminar</button>";
                            echo "</form>";
                            echo "</div>";
                            echo "</div>";

                            mysqli_close($conn);
                        }

                    }


                    // Mostrar el total de dinero en el carrito
                    echo "<p>Total a pagar: $totalMoney €</p>";
                    ?>
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
                                        value: '<?php echo $totalMoney; ?>' // Reemplazar "100" por el valor de $totalMoney
                                    }
                                }]
                            })
                        },

                        onApprove: function (data, actions) {
                            console.log("hola");
                            actions.order.capture().then(function (detalles) {
                                console.log(detalles);
                            });

                        },

                        onCancel: function (data) {
                            alert("pago cancelado")
                        }
                    }).render('#paypal-button-container');
                </script>
                <?php
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