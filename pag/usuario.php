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
    <!-- Menu general -->
    <?php menu_general(); ?>
<!-- Crear submenu con sus opciones -->
    <div class="d-flex vh-100">
        <div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 200px;">
            <br><br>

            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <form action="usuario.php" method="post">
                        <button type="submit" name="usuarioPrincipal" class="btn btn-link nav-link text-white">Principal</button>
                    </form>
                </li>
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
                <div id="seccion1" class="p-3" style="display: block;">
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

                        mysqli_close($conn);
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

            header("Location: usuario.php?usuarioTienda=1");
            exit();
        }

        // Calcular el total de dinero en el carrito
        $totalMoney = 0;
        ?>

        <?php
        if (isset($_POST['remove_from_cart'])) {
            $product_id = $_POST['product_id'];

            // Verificar si el carrito de compras está almacenado en la sesión
            if (isset($_SESSION['cart'])) {
                $cart = $_SESSION['cart'];

                // Verificar si el producto existe en el carrito
                if (isset($cart[$product_id])) {
                    // Restar una cantidad del producto en el carrito
                    $cart[$product_id]--;

                    // Verificar si la cantidad llega a cero o menos y eliminar el producto del carrito si es así
                    if ($cart[$product_id] <= 0) {
                        unset($cart[$product_id]);
                    }

                    // Guardar el carrito actualizado en la sesión
                    $_SESSION['cart'] = $cart;
                }
            }

            // Redirigir nuevamente al carrito
            header("Location: usuario.php?usuarioCarrito=1");
            exit();
        }
        ?>



        <?php

        if (isset($_REQUEST['usuarioCarrito'])) {
            ?>
            <div class="flex-grow-1">
                <div id="seccion1" class="p-3" style="display: block;">
                    <h1>Carrito</h1>
                    <div class="products">
                        <?php
                        // Verificar si el carrito de compras está almacenado en la sesión
                        if (isset($_SESSION['cart'])) {
                            $cart = $_SESSION['cart'];

                            // Consultar los productos desde la base de datos utilizando los IDs en el carrito
                            // Consultar los productos desde la base de datos utilizando los IDs en el carrito
                            $productIds = array_keys($cart);
                            if (!empty($productIds)) {
                                $productIdsString = implode(",", $productIds);
                                $sql = "SELECT id_producto, nombre, descripcion, precio FROM productos WHERE id_producto IN ($productIdsString)";
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
                                        echo "<form action='usuario.php' method='post'>";
                                        echo "<input type='hidden' name='product_id' value='$product_id'>";
                                        echo "<button class='btn btn-danger' name='remove_from_cart' type='submit'>Eliminar</button>";
                                        echo "</form>";
                                        echo "</div>";
                                        echo "</div>";
                                    }
                                }
                                mysqli_close($conn);
                            }

                        }

                        // Mostrar el total de dinero en el carrito
                        echo "<p>Total a pagar: $totalMoney €</p>";
                        ?>
                    </div>
                </div>
            </div>


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
</body>

</html>