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
    <link href="../css/mapa.css" rel="stylesheet" type="text/css">
    <script src="../js/mapa.js"></script>
    <title>DisplayAds</title>

</head>

<body>
    <?php
    if (isset($_SESSION['usuario']) && validarUsuario($_SESSION['usuario']['id_usuario']) && validarBloqueo($_SESSION['usuario']['id_usuario'])) {
        // Menu general
        menu_general();
        ?>

        <!-- Crear submenu con sus opciones -->
        <div class="d-flex vh-100">
            <div id="sidebar">
                <div class="p-2">
                    <a href="usuario.php?usuarioMapa" class="navbar-brand text-center text-light w-100 p-4 border-bottom">
                        Usuario
                    </a>
                </div>
                <div id="sidebar-accordion" class="accordion">
                    <div class="list-group">
                        <a href="usuario.php?usuarioMapa" class="list-group-item list-group-item-action text-light"
                            id="sidebar2">
                            <i class="fa fa-map mr-3" aria-hidden="true"></i>Mapa
                        </a>

                        <a href="usuario.php?usuarioTienda" class="list-group-item list-group-item-action text-light"
                            id="sidebar2">
                            <i class="fa fa-shopping-bag mr-3" aria-hidden="true"></i> Tienda
                        </a>

                        <a href="usuario.php?usuarioCarrito" class="list-group-item list-group-item-action text-light"
                            id="sidebar2">
                            <i class="fa fa-shopping-cart mr-3" aria-hidden="true"></i>Carrito
                        </a>
                    </div>
                </div>
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

                        <section class="bg-white py-4 my-3">
                            <div class="container">
                                <div class="row">
                                    <div class="col-12">
                                        <h2 class="mb-3 text-primary">TIENDA</h2>
                                    </div>
                                    <?php usuarioTienda(); ?>
                                </div>
                            </div>
                    </div>
                </div>
            </div>

            <?php
            }
            ?>
        <?php
        if (isset($_POST['add_to_cart'])) {
            $id_producto = $_POST['id_producto'];
            $precio = $_POST['precio'];
            addCart($id_producto, $precio);
        }


        ?>

        <?php
        if (isset($_POST['remove_from_cart'])) {
            $id_producto = $_POST['id_producto'];

            removeCart($id_producto);
            

        }

        if (isset($_REQUEST['actualizarPedido'])) {
            $importe = $_GET['importe'];
            $ubicacion = $_GET['ubicacion'];
            actualizarPedido($importe, $ubicacion);
        }

        

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
                                            $id_producto = $row['id_producto'];
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
                                            echo "<input type='hidden' name='id_producto' value='$id_producto'>";
                                            echo "<button class='btn btn-danger' name='remove_from_cart' type='submit'>Eliminar</button>";
                                            echo "</form>";
                                            echo "</div>";
                                            echo "</div>";
                                            echo "</div>";
                                        }

                                        $sql2 = "SELECT ubicacion, fecha_inicio FROM pedidos WHERE id_usuario = " . $_SESSION['usuario']['id_usuario'] . " AND ubicacion IS NOT NULL ORDER BY fecha_inicio DESC LIMIT 1";
                                        $result2 = sqlSELECT($sql2);

                                        echo " 
                                                <div class='form-group'>
                                                    <label for='formGroupExampleInput'>Ubicacion de envio: </label>
                                            ";
                                        if ($result2->num_rows > 0) {
                                            $row2 = $result2->fetch_assoc();
                                            $ubicacionReciente = $row2['ubicacion'];
                                            $fechaInicio = $row2['fecha_inicio'];
                                            echo "<input class='form-control' type='text' id='ubicacion-input' name='ubicacion' placeholder='Indica la ubicación a la que enviar el producto' value='" . htmlspecialchars($ubicacionReciente) . "' required>";

                                        } else {
                                            echo "<input class='form-control' type='text' id='ubicacion-input' name='ubicacion' placeholder='Indica la ubicación a la que enviar el producto' required>";
                                        }
                                        echo "  </div>
                                            </form>
                                            ";

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

                            document.getElementById('mensajeUbicacion').innerHTML = '<div class="alert alert-warning" role="alert">Debes indicar la ubicación antes de continuar</div>';
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
            
            mapa("guardar"); 
            
        }
        
        if (isset($_REQUEST['guardarMarcador'])) {
            guardarMarcador();
        }
        if (isset($_POST['borrarMarcador'])) {
            $id = $_POST['id_publicidad'];
            borrarMarcador($id); 
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