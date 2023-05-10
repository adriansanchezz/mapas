<!DOCTYPE html>
<?php
//Importar y abrir session que esta dentro de functiones.php
require_once '../lib/functiones.php';
require_once '../lib/modulos.php';
?>
<html>

<head>
    <?php head_info(); ?>
    <title>DisplayAds</title>
</head>

<body>
    <!-- Menu general -->
    <?php menu_general(); ?>

    <div class="d-flex vh-100">
        <div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 100px;">
            <br><br>

            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <form action="usuario.php" method="post">
                        <button type="submit" name="usuarioInicio"
                            class="btn btn-link nav-link text-white">Principal</button>
                    </form>
                </li>
                <li>
                    <form action="usuario.php" method="post">
                        <button type="submit" name="usuarioVigia"
                            class="btn btn-link nav-link text-white">Vigía</button>
                    </form>
                </li>
            </ul>
        </div>

        <?php
        if (isset($_REQUEST['usuarioInicio'])) {
            ?>
            <div class="flex-grow-1">
                <div id="seccion1" class="p-3" style="display: block;">
                    <form class="form-inline my-2 my-lg-0" action="usuario.php" method="post">
                        <button class="btn btn-outline-success my-2 my-sm-0" name="usuarioMapa" type="submit">Mapa</button>
                    </form>
                    <form class="form-inline my-2 my-lg-0" action="usuario.php" method="post">
                        <button class="btn btn-outline-success my-2 my-sm-0" name="usuarioTienda"
                            type="submit">Tienda</button>
                    </form>
                </div>
            </div>
            <?php
        }
        ?>

        <?php
        if (isset($_REQUEST['usuarioVigia'])) {
            ?>
            <div class="flex-grow-1">
                <div id="seccion1" class="p-3" style="display: block;">
                    <form class="form-inline my-2 my-lg-0" action="usuario.php" method="post">
                        <button class="btn btn-outline-success my-2 my-sm-0" name="usuarioVigiaMisiones"
                            type="submit">Misiones</button>
                    </form>
                    <form class="form-inline my-2 my-lg-0" action="usuario.php" method="post">
                        <button class="btn btn-outline-success my-2 my-sm-0" name="usuarioVigiaPuntos"
                            type="submit">Puntos</button>
                    </form>
                </div>
            </div>
            <?php
        }
        ?>

        <?php
        if (isset($_REQUEST['usuarioTienda'])) {
            ?>
            <div class="flex-grow-1">
                <div id="seccion1" class="p-3" style="display: block;">
                    <form class="form-inline my-2 my-lg-0" action="usuario.php" method="post">
                        <button class="btn btn-outline-success my-2 my-sm-0" name="usuarioCarrito"
                            type="submit">Carrito</button>
                    </form>
                    <h1>TIENDA</h1>
                    <div class="products">
                        <?php
                        // Establecer la conexión con la base de datos utilizando una función de conexión existente
                        $conn = conectar();

                        // Consultar los productos desde la base de datos
                        $sql = "SELECT * FROM productos";
                        $result = $conn->query($sql);

                        // Verificar si se encontraron productos
                        if ($result->num_rows > 0) {
                            // Iterar sobre los productos y mostrarlos en la página
                            while ($row = $result->fetch_assoc()) {
                                echo "<div class='product card border-primary mb-3' style='max-width: 18rem;'>";
                                echo "<div class='card-body'>";
                                echo "<h3 class='card-title'>" . $row['nombre'] . "</h3>";
                                echo "<p class='card-text'>" . $row['descripcion'] . "</p>";
                                echo "<p class='card-text'>Precio: $" . $row['precio'] . "</p>";
                                echo "<form action='usuario.php' method='post'>";
                                echo "<input type='hidden' name='product_id' value='" . $row['id_producto'] . "'>";
                                echo "<input class='btn btn-primary' type='submit' name='add_to_cart' value='Agregar al carrito'>";
                                echo "</form>";
                                echo "</div>";
                                echo "</div>";

                            }
                        } else {
                            echo "No se encontraron productos";
                        }

                        // Cerrar la conexión
                        //cerrarConexion($conn);
                        ?>
                    </div>
                </div>
            </div>
            <?php
        }


        ?>
        <?php
        if (isset($_POST['add_to_cart'])) {
            $product_id = $_POST['product_id'];

            // Verificar si el carrito de compras ya está almacenado en la sesión
            if (isset($_SESSION['cart'])) {
                $cart = $_SESSION['cart'];
            } else {
                $cart = array(); // Crear un nuevo carrito si no existe
            }

            // Verificar si el producto ya existe en el carrito
            if (isset($cart[$product_id])) {
                // Incrementar la cantidad del producto en 1
                $cart[$product_id]++;
            } else {
                // Agregar el producto al carrito con una cantidad de 1
                $cart[$product_id] = 1;
            }

            // Guardar el carrito actualizado en la sesión
            $_SESSION['cart'] = $cart;

            exit();
        }

        // Calcular el total de dinero en el carrito
        $totalMoney = 0;
        ?>


        <?php
        if (isset($_REQUEST['usuarioCarrito'])) {
            ?>
            <div class="flex-grow-1">
                <div id="seccion1" class="p-3" style="display: block;">
                    <form class="form-inline my-2 my-lg-0" action="usuario.php" method="post">
                        <button class="btn btn-outline-success my-2 my-sm-0" name="usuarioMapa" type="submit">Mapa</button>
                    </form>
                    <form class="form-inline my-2 my-lg-0" action="usuario.php" method="post">
                        <button class="btn btn-outline-success my-2 my-sm-0" name="usuarioTienda"
                            type="submit">Tienda</button>
                    </form>
                    <form class="form-inline my-2 my-lg-0" action="usuario.php" method="post">
                        <button class="btn btn-outline-success my-2 my-sm-0" name="usuarioCarrito"
                            type="submit">Carrito</button>
                    </form>
                    <h1>Carrito</h1>
                    <div class="products">
                        <?php
                        // Verificar si el carrito de compras está almacenado en la sesión
                        if (isset($_SESSION['cart'])) {
                            $cart = $_SESSION['cart'];

                            // Consultar los productos desde la base de datos utilizando los IDs en el carrito
                            $productIds = array_keys($cart);
                            $sql = "SELECT id_producto, nombre, descripcion, precio FROM productos WHERE id_producto IN (" . implode(",", $productIds) . ")";
                            $conn = conectar();
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                // Iterar sobre los productos en el carrito
                                while ($row = $result->fetch_assoc()) {
                                    $product_id = $row['id_producto'];
                                    $product_name = $row['nombre'];
                                    $product_description = $row['descripcion'];
                                    $product_price = $row['precio'];
                                    $product_quantity = $cart[$product_id];

                                    // Calcular el subtotal por producto
                                    $subtotal = $product_price * $product_quantity;

                                    // Agregar el subtotal al total de dinero
                                    $totalMoney += $subtotal;

                                    // Mostrar los detalles del producto en el carrito
                                    echo "<div class='product card border-primary mb-3' style='max-width: 18rem;'>";
                                    echo "<div class='card-header bg-primary text-white'>$product_name</div>";
                                    echo "<div class='card-body text-primary'>";
                                    echo "<h5 class='card-title'>$product_description</h5>";
                                    echo "<p class='card-text'>Precio: $product_price</p>";
                                    echo "<p class='card-text'>Cantidad: $product_quantity</p>";
                                    echo "<p class='card-text'>Subtotal: $subtotal</p>";
                                    echo "</div>";
                                    echo "</div>";
                                }
                            }
                        }

                        // Mostrar el total de dinero en el carrito
                        echo "<p>Total a pagar: $totalMoney</p>";
                        ?>
                    </div>
                </div>
            </div>


            <?php
        }
        if (isset($_REQUEST['usuarioVigiaMisiones'])) {
            ?>
            <h1>MISIONES</h1>
            <?php
        }
        ?>

        <?php
        if (isset($_REQUEST['usuarioVigiaPuntos'])) {
            ?>
            <h1>PUNTOS</h1>
            <?php
        }
        ?>

        <?php
        if (isset($_REQUEST['usuarioMapa'])) {
            mapa("guardar");
        }
        ?>
        <?php
        if (isset($_REQUEST['guardarMarcador'])) {
            guardarMarcador();
        }
        ?>

    </div>
</body>

</html>