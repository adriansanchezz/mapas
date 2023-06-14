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
    <script src="../js/funciones.js"></script>
    <script src="../js/mapa.js"></script>
    <script src="../js/paypal.js"></script>
    <link href="../css/empresa.css" rel="stylesheet" type="text/css">
</head>

<body>
    <?php
    if (isset($_SESSION['usuario']) && validarEmpresa($_SESSION['usuario']['id_usuario']) && validarBloqueo($_SESSION['usuario']['id_usuario'])) {
        // Menu general
        menu_general(); ?>

        <!-- Menu horizontal -->
        <div class="d-flex vh-100">
            <div id="sidebar">
                <div class="p-2">
                    <a href="empresa.php?empresaMapa" class="navbar-brand text-center text-light w-100 p-4 border-bottom">
                        Empresa
                    </a>
                </div>
                <div id="sidebar-accordion" class="accordion">
                    <div class="list-group">
                        <a href="empresa.php?empresaMapa" class="list-group-item list-group-item-action text-light"
                            id="sidebar2">
                            <i class="fa fa-map mr-3" aria-hidden="true"></i>Publicitarse
                        </a>

                        <a href="empresa.php?empresaInfo" class="list-group-item list-group-item-action text-light"
                            id="sidebar2">
                            <i class="fa fa-user mr-3" aria-hidden="true"></i> Informacion
                        </a>
                    </div>
                </div>
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

                mapa("ver");

            }
            ?>

            <?php
            // Añadir al carrito al seleccionar una publicidad.
            if (isset($_POST['add_to_cart'])) {

                $id_producto = $_POST['publicidad_id'];
                $precio = $_POST['precio'];
                $id_empresa = $_SESSION['usuario']['id_usuario'];
                addCartEmpresa($id_producto, $precio, $id_empresa);
                
            }

            // Calcular el total de dinero en el carrito
            $totalMoney = 0;
            ?>
            <?php
            // Eliminar del carrito.
            if (isset($_POST['remove_from_cart'])) {
                $id_producto = trim($_POST['id_publicidad']);
                removeCartEmpresa($id_producto);
                
            }

            // Actualizar el pedido cuando se compre, ya que el pedido empieza con algunos datos sin identificar.
            if (isset($_REQUEST['actualizarPedido'])) {
                $importe = $_REQUEST['importe']; // Obtener el valor de importe desde los parámetros de la solicitud
                actualizarPedidoEmpresa($importe);
            }
            ?>


            <?php
            // Carrito de la empresa.
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
                            $id_producto = $row['id_publicidad'];
                            $product_name = $row['ubicacion'];
                            $product_description = $row['provincia'];
                            $product_price = $row['precio'];

                            // Almacenar el precio del producto en el array asociativo
                            $product_prices[$id_producto] = $product_price;

                            // Obtener el valor seleccionado de meses
                            $selected_months = isset($_POST["months_$id_producto"]) ? intval($_POST["months_$id_producto"]) : 1;

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
                            echo "<p class='card-text'>Meses seleccionados: <span id='months_selected_$id_producto'>$selected_months</span></p>";
                            echo "<form action='empresa.php' method='post'>";
                            echo "<input type='hidden' name='id_publicidad' value='$id_producto'>";
                            echo "<label for='months_$id_producto'>Meses:</label>";
                            // se le aplica un name al input llamado months_idproducto que contendrá la id de producto.
                            echo "<input type='number' name='months_$id_producto' id='months_$id_producto' min='1' max='12' value='$selected_months' onchange='precioTotalMeses($id_producto)'>";
                            echo "<button class='btn btn-danger' name='remove_from_cart' value='$id_producto' type='submit'>Eliminar</button>";
                            echo "</form>";
                            echo "<p id='total_price_$id_producto'>Subtotal: " . ($product_price * $selected_months) . " €</p>";
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
                        // Función para cambiar el precio según los meses seleccionados.
                        function precioTotalMeses(productId) {
                            // en el array de precios de productos se añade el precio del producto.
                            var productPrices = <?php echo json_encode($product_prices); ?>;
                            // Se obtiene el div de producto por su nombre de clase. Y segun más productos haya más coge.
                            var products = document.getElementsByClassName('product');
                            var totalImporte = 0;
                            // Se recorre products.
                            for (var i = 0; i < products.length; i++) {
                                // product es igual a la posicion i de products.
                                var product = products[i];
                                // Se obtiene la id del elemento.
                                var productIdElement = product.querySelector('input[name="id_publicidad"]');
                                // Se obtiene el selector de meses.
                                var monthsSelector = product.querySelector('input[name^="months_"]');
                                // Se obtiene el precio total.
                                var totalPriceElement = product.querySelector('[id^="total_price_"]');

                                // Se obtiene la id.
                                var currentProductId = parseInt(productIdElement.value);
                                // Se obtiene el precio de producto.
                                var currentProductPrice = parseFloat(productPrices[currentProductId]);
                                // Se obtiene los meses seleccionados.
                                var currentSelectedMonths = parseInt(monthsSelector.value);
                                // Se multiplica el precio por los meses.
                                var currentTotalPrice = currentProductPrice * currentSelectedMonths;

                                // Actualizar el precio total del producto.
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
                    // Funciones de paypal.
                    paypal.Buttons({
                        style: {
                            color: 'blue',
                            shape: 'pill',
                            label: 'pay'
                        },
                        createOrder: function (data, actions) {
                            // Se obtiene los precios de los productos.
                            var productPrices = <?php echo json_encode($product_prices); ?>;
                            // Se obtiene los productos.
                            var products = document.getElementsByClassName('product');
                            var totalImporte = 0;

                            // Se recorre products.
                            for (var i = 0; i < products.length; i++) {
                                // Se obtiene el producto de posición i.
                                var product = products[i];
                                // Se selecciona el id.
                                var productIdElement = product.querySelector('input[name="id_publicidad"]');
                                // Se selecciona el selector de meses.
                                var monthsSelector = product.querySelector('input[name^="months_"]');

                                // Se obtiene la id.
                                var currentProductId = parseInt(productIdElement.value);
                                // Se obtiene el precio.
                                var currentProductPrice = parseFloat(productPrices[currentProductId]);
                                // Se obtiene los meses.
                                var currentSelectedMonths = parseInt(monthsSelector.value);
                                // Se multiplica los meses por el precio y se hace con cada producto.
                                var currentTotalPrice = currentProductPrice * currentSelectedMonths;

                                // Se va sumando el importe total.
                                totalImporte += currentTotalPrice;
                            }

                            return actions.order.create({
                                purchase_units: [{
                                    amount: {
                                        // Se crea una orden con el importe total.
                                        value: totalImporte.toFixed(2) // Redondear el importe a 2 decimales
                                    }
                                }]
                            })
                        },

                        // Si se aprueba la petición.
                        onApprove: function (data, actions) {
                            actions.order.capture().then(function (detalles) {
                                // Se realiza una petición http.
                                var xhr = new XMLHttpRequest();
                                // Se obtienen los precios.
                                var productPrices = <?php echo json_encode($product_prices); ?>;
                                // Se obtienen los productos.
                                var products = document.getElementsByClassName('product');
                                var totalImporte = 0;

                                // Se recorren los productos.
                                for (var i = 0; i < products.length; i++) {
                                    // Se obtiene el producto.
                                    var product = products[i];
                                    // Se selecciona el id.
                                    var productIdElement = product.querySelector('input[name="id_publicidad"]');
                                    // Se selecciona el selector de meses.
                                    var monthsSelector = product.querySelector('input[name^="months_"]');

                                    // Se obtiene el id.
                                    var currentProductId = parseInt(productIdElement.value);
                                    // Se obtiene el precio.
                                    var currentProductPrice = parseFloat(productPrices[currentProductId]);
                                    // Se obtienen los meses seleccionados.
                                    var currentSelectedMonths = parseInt(monthsSelector.value);
                                    // Se obtiene el precio total.
                                    var currentTotalPrice = currentProductPrice * currentSelectedMonths;

                                    // Se va sumando el precio total.
                                    totalImporte += currentTotalPrice;
                                }
                                var importe = totalImporte; 

                                // Obtener los meses seleccionados.
                                var mesesSeleccionados = document.querySelectorAll('input[type="number"]');
                                // Se crean los parámetros para la consulta http.
                                var parametros = 'actualizarPedido&importe=' + importe;

                                // Se recorre mesesSeleccionados.
                                for (var i = 0; i < mesesSeleccionados.length; i++) {
                                    // Se obtiene el atributo id que es una cadena de texto.
                                    var id_producto = mesesSeleccionados[i].getAttribute('id').substring(7);
                                    // Se obtienen los meses seleccionados.
                                    var selected_months = mesesSeleccionados[i].value;
                                    // Se posicionan los parámetros meses junto a productoid.
                                    parametros += '&months_' + id_producto + '=' + selected_months;
                                }
                                // Y se envía a actualizarPedido los datos obtenidos aquí.
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
                empresaInfo();
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