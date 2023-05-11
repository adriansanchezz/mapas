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
    <?php menu_general(); ?>
    <!-- Menu horizontal -->
    <div class="d-flex vh-100">
        <div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 150px;">
            <br><br>

            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <form action="empresa.php" method="post">
                        <button type="submit" name="empresaInicio"
                            class="btn btn-link nav-link text-white">Principal</button>
                    </form>
                </li>
                <li>
                    <form action="empresa.php" method="post">
                        <button type="submit" name="empresaCarrito"
                            class="btn btn-link nav-link text-white">Carrito</button>
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
        if (isset($_REQUEST['empresaInicio'])) {
            ?>
            <div class="flex-grow-1">
                <div id="seccion1" class="p-3" style="display: block;">

                    <form class="form-inline my-2 my-lg-0" action="empresa.php" method="post">
                        <button class="btn btn-outline-success my-2 my-sm-0" name="empresaMapa" type="submit">Mapa</button>
                    </form>
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

            // Verificar si el carrito de compras ya está almacenado en la sesión
            if (isset($_SESSION['empresaCart'])) {
                $empresaCart = $_SESSION['empresaCart'];
            } else {
                $empresaCart = array(); // Crear un nuevo carrito si no existe
            }

            // Verificar si el producto ya existe en el carrito
            if (isset($empresaCart[$product_id])) {
                // Incrementar la cantidad del producto en 1
                $empresaCart[$product_id]++;
            } else {
                // Agregar el producto al carrito con una cantidad de 1
                $empresaCart[$product_id] = 1;
            }

            // Guardar el carrito actualizado en la sesión
            $_SESSION['empresaCart'] = $empresaCart;

            header("Location: empresa.php?empresaMapa=1");
            exit();
        }

        // Calcular el total de dinero en el carrito
        $totalMoney = 0;
        ?>
        <?php
        if (isset($_REQUEST['empresaCarrito'])) {
            ?>
            <div class="products">
                <?php
                // Verificar si el carrito de compras está almacenado en la sesión
                if (isset($_SESSION['empresaCart'])) {
                    $empresaCart = $_SESSION['empresaCart'];

                    // Consultar los productos desde la base de datos utilizando los IDs en el carrito
                    // Consultar los productos desde la base de datos utilizando los IDs en el carrito
                    $productIds = array_keys($empresaCart);

                    if (!empty($productIds)) {
                        $productIdsString = implode(",", $productIds);
                        $productIdsString = trim($productIdsString, ", ");

                        $sql = "SELECT * FROM propiedades WHERE id_propiedad IN ($productIdsString)";
                        $conn = conectar();
                        $result = $conn->query($sql);



                        if ($result->num_rows > 0) {
                            // Iterar sobre los productos en el carrito
                            while ($row = $result->fetch_assoc()) {
                                $product_id = $row['id_propiedad'];
                                $product_name = $row['ubicacion'];
                                $product_description = $row['provincia'];
                                $product_price = $row['precio'];

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
                                echo "<form action='usuario.php' method='post'>";
                                echo "<input type='hidden' name='product_id' value='$product_id'>";
                                echo "<button class='btn btn-danger' name='remove_from_empresaCart' type='submit'>Eliminar</button>";
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


            <?php
        }
        ?>
    </div>
</body>

</html>