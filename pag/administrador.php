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
        img {
            width: 500px;
            height: 500px;
        }
    </style>
    <script src="../js/funciones.js"></script>
</head>

<body>

    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #333;
            color: white;
        }

        td:nth-child(even) {
            background-color: #f2f2f2;
            color: #333;
        }

        td:nth-child(odd) {
            background-color: #ddd;
            color: #333;
        }
    </style>
    <div class="separar">
        <?php
        if (isset($_SESSION['usuario'])) {
            // Menu general
            menu_general(); ?>
        </div>
        <!-- Menu lateral -->
        <div class="d-flex vh-100">
            <div class="menu-lateral d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 200px;">
                <hr>
                <!-- Crear submenu con sus opciones -->
                <ul class="nav nav-pills flex-column mb-auto flex-grow-1">
                    <li class="nav-item">
                        <form action="administrador.php" method="get">
                            <button type="submit" name="administradorPanel" class="btn btn-link nav-link text-white">
                                Panel de control
                            </button>
                        </form>
                    </li>
                    <li class="nav-item">
                        <form action="administrador.php" method="get">
                            <button type="submit" name="administradorUsuarios" class="btn btn-link nav-link text-white">
                                Usuarios
                            </button>
                        </form>
                    </li>
                    <li class="nav-item">
                        <form action="administrador.php" method="get">
                            <button type="submit" name="administradorProductos" class="btn btn-link nav-link text-white">
                                Productos
                            </button>
                        </form>
                    </li>
                    <!-- <li class="nav-item">
                        <form action="administrador.php" method="get">
                            <button type="submit" name="administradorNoticias" class="btn btn-link nav-link text-white">
                                Noticias
                            </button>
                        </form>
                    </li> -->
                    <li class="nav-item">
                        <form action="administrador.php" method="get">
                            <button type="submit" name="administradorMisiones" class="btn btn-link nav-link text-white">
                                Misiones
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
            <div class="flex-grow-1">
                <div id="seccion1" class="p-3" style="display: block;">

                    <?php
                    if (isset($_REQUEST['bloquearUsuario'])) {
                        $id = $_POST['id_usuario'];
                        bloquearUsuario($id);
                    }
                    ?>

                    <?php
                    if (isset($_REQUEST['eliminarRolUsuario'])) {
                        $id = $_POST['id_usuario'];
                        $nombre_rol = $_POST['nombre_rol'];
                        eliminarRoles($id, $nombre_rol);
                    }
                    ?>

                    <?php
                    if (isset($_REQUEST['agregarRolUsuario'])) {
                        $id = $_POST['id_usuario'];
                        $nombre_rol = $_POST['nombre_rol'];
                        agregarRoles($id, $nombre_rol);
                    }
                    ?>


                    <?php
                    if (isset($_REQUEST['administradorPanel'])) {
                        ?>
                        <h2>Panel de control</h2><br>

                        Visitas total obtenida:
                        <?php
                        verVisitaTotal();


                        $conn = conectar();

                        // Consultar los productos desde la base de datos
                
                        $sql = "SELECT * FROM publicidades as p, usuarios as u WHERE p.comprador IS NOT NULL AND p.id_usuario <> p.comprador AND p.ocupado = 1 AND p.id_usuario = u.id_usuario";
                        $result = $conn->query($sql);
                        echo "<h1>UBICACIONES COMPRADAS</h1>";
                        if ($result->num_rows > 0) {
                            echo "<table>";
                            echo "<tr>
                                    <th>Usuario</th>
                                    <th>Ubicación Envío</th>
                                    <th>Código Postal</th>
                                    <th>Empresa Compra</th>
                                    <th>Estado</th>
                                    </tr>";
                            while ($row = $result->fetch_assoc()) {
                                $sql2 = "SELECT * FROM empresas WHERE id_empresa = " . $row['comprador'];
                                $result2 = $conn->query($sql2);
                                if ($result2->num_rows > 0) {

                                    while ($row2 = $result2->fetch_assoc()) {
                                        echo "
                                              
                                              <tr>
                                                <td>" . $row['email'] . "</td>
                                                <td>" . $row['ubicacion'] . "</td>
                                                <td>" . $row['codigo_postal'] . "</td>
                                                <td>" . $row2['nombre'] . "</td>
                                                <td>
                                                    <form action administrador.php
                                                </td>
                                              </tr>";
                                    }


                                }

                            }

                            echo "</table>";
                        }
                    }
                    ?>


                    <?php
                    if (isset($_REQUEST['administradorUsuarios'])) {
                        listarUsuarios($_SESSION['usuario']['id_usuario']);
                    }



                    if (isset($_REQUEST['administradorProductos'])) {
                        echo "<div>";
                        echo "<div class='container'>";
                        echo "<h2 class='mt-5'>Crear nuevo producto</h2>";
                        echo "<form action='administrador.php' method='POST' enctype='multipart/form-data'>";
                        echo "<div class='form-group'>";
                        echo "<label for='nombre'>Nombre del producto:</label>";
                        echo "<input type='text' name='nombre' id='nombre' class='form-control' required>";
                        echo "</div>";
                        echo "<div class='form-group'>";
                        echo "<label for='descripcion'>Descripción del producto:</label>";
                        echo "<textarea name='descripcion' id='descripcion' class='form-control' required></textarea>";
                        echo "</div>";
                        echo "<div class='form-group'>";
                        echo "<label for='precio'>Precio del producto:</label>";
                        echo "<input type='text' name='precio' id='precio' class='form-control' required>";
                        echo "</div>";
                        echo "<div class='form-group'>";
                        echo "<input type='file' name='imagen' accept='image/*' required>";
                        echo "</div>";
                        echo "<input type='submit' name='nuevoProducto' class='btn btn-danger' value='Crear producto'>";
                        echo "</form>";
                        echo "</div>";
                        echo "<div class='container'>";
                        echo "<h1 class='mt-5'>Lista de Productos</h1>";
                        $conn = conectar();
                        $sql = "SELECT * FROM productos";
                        $result = $conn->query($sql);

                        // Verificar si se encontraron productos
                        if ($result->num_rows > 0) {
                            // Recorrer los resultados y crear las opciones del select
                            echo "<table class='table'>";
                            echo "<thead>";
                            echo "<tr>";
                            echo "<th>Nombre</th>";
                            echo "<th>Descripcion</th>";
                            echo "<th>Precio</th>";
                            echo "<th>Foto</th>";
                            echo "<th>Acciones</th>";

                            echo "</tr>";
                            echo "</thead>";
                            echo "<tbody>";
                            while ($row = mysqli_fetch_assoc($result)) {

                                echo "<tr>";
                                echo "<td><span class='editableProducto' id='nombre' data-producto-id='" . $row["id_producto"] . "'>" . $row["nombre"] . "</span></td>";
                                echo "<td><span class='editableProducto' id='descripcion' data-producto-id='" . $row["id_producto"] . "'>" . $row["descripcion"] . "</span></td>";
                                echo "<td><span class='editableProducto' id='precio' data-producto-id='" . $row["id_producto"] . "'>" . $row["precio"] . "</span></td>";

                                $sql2 = "SELECT * FROM `fotos` where id_producto =" . $row['id_producto'];
                                $result2 = $conn->query($sql2);

                                echo "<td>";
                                if ($result2->num_rows > 0) {
                                    // Recuperar la información de la imagen
                                    $row2 = $result2->fetch_assoc();
                                    $imagen = $row2["foto"];

                                    // Mostrar la imagen en la página
                                    echo "<img src='data:image/jpeg;base64," . base64_encode($imagen) . "' alt='Imagen del producto'>";
                                } else {
                                    echo "No se encontró la imagen asociada.";
                                }
                                echo "</td>";
                                echo "<td>";
                                echo "<form action='administrador.php' method='POST'>";
                                echo "<input type='hidden' name='idProducto' value='" . $row["id_producto"] . "' />";
                                echo "<input type='submit' name='borrarProducto' value='Borrar producto' class='btn btn-danger' />";
                                echo "</form>";
                                echo "</td>";
                                echo "</tr>";

                            }
                            echo "</tbody>";
                            echo "</table>";
                            mysqli_close($conn);
                        } else {
                            echo "<option value=''>No hay tipos de publicidades disponibles</option>";
                        }
                        echo "</div>";
                        echo "</div>";

                    }

                    if (isset($_POST['nuevoProducto'])) {
                        $nombreProducto = $_POST['nombre'];
                        $descripcionProducto = $_POST['descripcion'];
                        $precioProducto = $_POST['precio'];


                        $conn = conectar();
                        $sql = "INSERT INTO `productos`(`nombre`, `descripcion`, `precio`) VALUES (?, ?, ?)";
                        $stmt = $conn->prepare($sql);
                        // Y mediante un bind_param se establecen los valores.
                        $stmt->bind_param('ssd', $nombreProducto, $descripcionProducto, $precioProducto);
                        // Se ejecuta la consulta.
                        $stmt->execute();

                        // Verificar si la inserción fue exitosa.
                        if ($stmt->affected_rows > 0) {

                            if (isset($_FILES['imagen'])) {
                                if ($_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                                    $imagen = $_FILES['imagen']['tmp_name'];
                                    $contenidoImagen = file_get_contents($imagen);
                                    $ultimoIdProducto = $stmt->insert_id;
                                    $sql = "INSERT INTO `fotos`(`foto`, `id_producto`) VALUES (?, ?)";
                                    $stmt2 = $conn->prepare($sql);
                                    $stmt2->bind_param("si", $contenidoImagen, $ultimoIdProducto);
                                    // Ejecutar la consulta
                                    if ($stmt2->execute()) {
                                        echo "<script>window.location.href = 'administrador.php?administradorProductos=';</script>";
                                        exit();
                                    } else {
                                        echo "Error al subir la imagen: " . $stmt->error;
                                    }

                                }
                            } else {
                                echo "<h1>ERROR</h1>";
                            }

                        } else {
                            // Si no lo fue, se indica un error.
                            echo "Error al guardar el marcador.";
                        }
                    }

                    if (isset($_POST['borrarProducto'])) {
                        $id = $_POST['idProducto'];
                        $conn = conectar();
                        $sql = "DELETE FROM `productos` WHERE id_producto =" . $id . ";";
                        $resultado = mysqli_query($conn, $sql);

                        if ($resultado) {
                            echo "<script>window.location.href = 'administrador.php?administradorProductos=';</script>";
                            exit();
                        } else {
                            echo "Error al ejecutar la consulta de eliminación: " . mysqli_error($conn);
                        }
                    }
                    ?>

                    <?php
                    // Verificar si se recibió un pedido para editar un producto
                    if (isset($_GET['editarProducto'])) {
                        // Obtener el ID del producto a editar
                        $productoId = $_GET['editarProducto'];

                        // Obtener el nuevo valor del producto
                        $nuevoValor = $_POST['nuevoValor'];

                        // Obtener el nombre de la columna a actualizar (puede venir como parámetro en la solicitud)
                        $columna = $_POST['columna']; // Asegúrate de validar y sanitizar este valor
                
                        // Realizar la lógica para actualizar el valor en la base de datos
                        // Aquí debes escribir el código específico para tu base de datos y tabla
                
                        // Por ejemplo, supongamos que tienes una tabla llamada "productos"
                        // Puedes utilizar una consulta SQL para actualizar el valor del producto en la columna específica
                        // Ejemplo con MySQLi:
                        $conexion = conectar();
                        $columna = $conexion->real_escape_string($columna); // Escapar el nombre de la columna para evitar inyección de SQL
                        $consulta = "UPDATE productos SET $columna = '$nuevoValor' WHERE id_producto = $productoId";
                        $resultado = $conexion->query($consulta);

                        // Manejar la respuesta de la actualización (puedes enviar un mensaje de éxito o realizar alguna otra acción)
                        if ($resultado) {
                            echo "Actualización exitosa";
                        } else {
                            echo "Error al actualizar el valor";
                        }
                        // Terminar la ejecución del script PHP
                        exit();
                    }
                    ?>

                    <?php
                    // Verificar si se recibió un pedido para editar un usuario
                    if (isset($_GET['editarUsuario'])) {
                        // Obtener el ID del producto a editar
                        $usuarioId = $_GET['editarUsuario'];

                        // Obtener el nuevo valor del producto
                        $nuevoValor = $_POST['nuevoValor'];

                        // Obtener el nombre de la columna a actualizar (puede venir como parámetro en la solicitud)
                        $columna = $_POST['columna']; // Asegúrate de validar y sanitizar este valor
                
                        // Por ejemplo, supongamos que tienes una tabla llamada "productos"
                        // Puedes utilizar una consulta SQL para actualizar el valor del producto en la columna específica
                        // Ejemplo con MySQLi:
                        $conexion = conectar();
                        $columna = $conexion->real_escape_string($columna); // Escapar el nombre de la columna para evitar inyección de SQL
                        $consulta = "UPDATE usuarios SET $columna = '$nuevoValor' WHERE id_usuario = $usuarioId";
                        $resultado = $conexion->query($consulta);

                        // Manejar la respuesta de la actualización (puedes enviar un mensaje de éxito o realizar alguna otra acción)
                        if ($resultado) {
                            echo "Actualización exitosa";
                        } else {
                            echo "Error al actualizar el valor";
                        }
                        // Terminar la ejecución del script PHP
                        exit();
                    }


                    if (isset($_REQUEST['administradorMisiones'])) {
                        ?>
                        <div class="flex-grow-1">
                            <div class="p-3" style="display: block;">
                                <h1>MISIONES</h1>
                                <div id="map"></div>
                                <div class="container mt-4">
                                    <div class="table-responsive mb-4">
                                        Misiones en proceso:
                                        <table id="tabla-puntos" class="table">
                                            <thead>
                                                <tr>
                                                    <th>Descripcion</th>
                                                    <th>Latitud</th>
                                                    <th>Longitud</th>
                                                    <th>Prueba</th>
                                                    <th>Aceptacion</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $conn = conectar();
                                                $sql = "SELECT * FROM misiones WHERE  estado=1 AND aceptacion=0";
                                                $result = $conn->query($sql);
                                                if ($result->num_rows > 0) {
                                                    while ($row = $result->fetch_assoc()) {
                                                        echo '<tr>';
                                                        echo '<td>' . $row['descripcion'] . '</td>'; // Columna de descripción
                                                        echo '<td>' . $row['fecha_fin'] . '</td>'; // Columna de fecha_fin
                                                        echo '<td>' . $row['descripcion'] . '</td>'; // Columna de descripción
                                                        $sql2 = "SELECT * FROM `fotos` where id_mision =" . $row['id_mision'];
                                                        $result2 = $conn->query($sql2);

                                                        echo "<td>";
                                                        if ($result2->num_rows > 0) {
                                                            // Recuperar la información de la imagen
                                                            $row2 = $result2->fetch_assoc();
                                                            $imagen = $row2["foto"];

                                                            // Mostrar la imagen en la página
                                                            echo "<img src='data:image/jpeg;base64," . base64_encode($imagen) . "' alt='Imagen de la prueba.'>";
                                                        } else {
                                                            echo "No se encontró la imagen asociada.";
                                                        }
                                                        echo "</td>";
                                                        echo "<td>
                                    <form action='administrador.php?aceptarMision' method='POST'>
                                    <input type='hidden' name='id_mision' value='" . $row['id_mision'] . "'>
                                    <input type='submit' name='aceptarMision' class='btn btn-success' value='Aceptar'>
                                    </form>
                                    </td>";

                                                        echo '</tr>';

                                                    }
                                                    echo "</tbody>";
                                                }

                    }
                    if (isset($_POST['aceptarMision'])) {
                        $id_mision = $_POST['id_mision'];
                        $conn = conectar();
                        $sqlUpdate = "UPDATE `misiones` SET `aceptacion` = 1 WHERE `id_mision` = ?";
                        $stmt = $conn->prepare($sqlUpdate);
                        $stmt->bind_param("i", $id_mision);
                        $stmt->execute();

                        echo "<script>window.location.href = 'administrador.php?administradorMisiones=';</script>";
                        exit();
                    }
                    if (isset($_POST['rechazarMision'])) {
                        $id_mision = $_POST['id_mision'];
                        $conn = conectar();
                        $sqlUpdate = "UPDATE `misiones` SET `aceptacion` = 2 WHERE `id_mision` = ?";
                        $stmt = $conn->prepare($sqlUpdate);
                        $stmt->bind_param("i", $id_mision);
                        $stmt->execute();

                        echo "<script>window.location.href = 'administrador.php?administradorMisiones=';</script>";
                        exit();
                    }
                    ?>

                                </div>
                            </div>
                        </div>

                        <?php
    } else {
        echo ('Acceso denegado');
        print '<a href ="../index.php"><button>Volver</button></a>';
        session_destroy();
    }
    ?>

                    <script>
                        administradorUsuarios();
                        administradorProductos();
                    </script>


</body>

</html>