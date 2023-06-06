<!DOCTYPE html>
<?php
//Importar y abrir session que esta dentro de funciones.php
require_once '../lib/funciones.php';
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
    <?php
    if (isset($_SESSION['usuario']) && validarAdmin($_SESSION['usuario']['id_usuario'])) {
        // Menu general
        menu_general(); ?>

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
                    <li class="nav-item">
                        <form action="administrador.php" method="get">
                            <button type="submit" name="administradorSoportes" class="btn btn-link nav-link text-white">
                                Soportes
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
                    if (isset($_POST['borrarProducto'])) {
                        $id = $_POST['idProducto'];
                        borrarProducto($id);
                    }
                    ?>

                    <?php
                    if (isset($_POST['desactivarProducto'])) {
                        $id = $_POST['idProducto'];
                        desactivarProducto($id);
                    }
                    ?>

                    <?php
                    if (isset($_POST['activarProducto'])) {
                        $id = $_POST['idProducto'];
                        activarProducto($id);
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
                        echo "<br><br><h1>Ubicaciones compradas</h1><br>";
                        if ($result->num_rows > 0) {
                            echo "<table>";
                            echo "<tr>
                                    <th>Usuario/Dueño</th>
                                    <th>Ubicación Envío</th>
                                    <th>Código Postal</th>
                                    <th>Empresa Compradora</th>
                                    <th>Precio</th>
                                    <th>Fecha final</th>
                                    <th>Precio total</th>
                                    <th>Estado</th>
                                    </tr>";
                            while ($row = $result->fetch_assoc()) {
                                $sql2 = "SELECT * FROM empresas WHERE id_empresa = " . $row['comprador'];
                                $result2 = $conn->query($sql2);
                                if ($result2->num_rows > 0) {
                                    while ($row2 = $result2->fetch_assoc()) {
                                        echo "<tr>
                                        <td>" . $row['email'] . "</td>
                                        <td>" . $row['ubicacion'] . "</td>
                                        <td>" . $row['codigo_postal'] . "</td>
                                        <td>" . $row2['nombre'] . "</td>
                                        <td>" . $row['precio'] . "</td>
                                        <td>" . $row['caducidad_compra'] . "</td>";

                                        $sql3 = "SELECT * FROM pedidos as p, lineas_pedidos as lp WHERE p.id_pedido = lp.id_pedido AND lp.id_publicidad = " . $row['id_publicidad'];
                                        $result3 = $conn->query($sql3);
                                        if ($result3->num_rows > 0) {
                                            while ($row3 = $result3->fetch_assoc()) {
                                                echo "<td>" . $row3['importe'] . "</td>";
                                            }
                                        }


                                        echo "<td>";
                                        $sql4 = "SELECT * FROM publicidades WHERE id_publicidad = " . $row['id_publicidad'] . " AND revision IS NULL";
                                        $result4 = sqlSELECT($sql4);

                                        // Si da resultados entonces entra en el if.
                                        if ($result4->num_rows > 0) {
                                            echo "<form action='administrador.php' method='POST'>
                                            <input type='hidden' name='id_publicidad' value='" . $row['id_publicidad'] . "'>
                                            <input type='submit' name='revisarCompraUbicacion' value='Revisado'>
                                            </form>";
                                        } else {
                                            echo "<p>REVISADO</p>";
                                        }

                                        echo "</td>
                                        </tr>";
                                    }
                                }
                            }
                            echo "</table>";
                        }


                        echo "<br><br><h1>Productos comprados</h1><br>";
                        $sql4 = "SELECT * FROM pedidos WHERE fecha_fin IS NOT NULL";
                        $result = $conn->query($sql4);
                        if ($result->num_rows > 0) {
                            echo "<table>";
                            echo "<tr>
                                    <th>Usuario</th>
                                    <th>Productos</th>
                                    <th>Importe</th>
                                    <th>Tipo</th>
                                    <th>Ubicación</th>
                                    <th>Fecha</th>
                                    <th>Estado</th>
                                    </tr>";
                            while ($row3 = $result->fetch_assoc()) {

                                echo "<tr>";

                                $sql5 = "SELECT * FROM usuarios WHERE id_usuario = " . $row3['id_usuario'];
                                $result3 = sqlSELECT($sql5);

                                // Si da resultados entonces entra en el if.
                                if ($result3->num_rows > 0) {
                                    while ($row4 = $result3->fetch_assoc()) {

                                        echo "<td>" . $row4['email'] . "</td>";
                                    }
                                }

                                $sql6 = "SELECT * FROM lineas_pedidos as lp, productos as p WHERE lp.id_producto = p.id_producto AND lp.id_pedido = " . $row3['id_pedido'];
                                $result4 = sqlSELECT($sql6);

            
                                if ($result4->num_rows > 0) {
                                    while ($row5 = $result4->fetch_assoc()) {

                                        echo "<td>" . $row5['nombre'] . "</td>";
                                    }
                                }
                                


                                if($row3['importe']>0)
                                {
                                    echo "<td>" . $row3['importe'] . "</td>";
                                    echo "<td>Dinero</td>";
                                }
                                else
                                {
                                    echo "<td>" . $row3['puntos'] . "</td>";
                                    echo "<td>Puntos</td>";
                                }
                                
                                   echo "<td>" . $row3['ubicacion'] . "</td>
                                    <td>" . $row3['fecha_fin'] . "</td>";
                                echo "<td>";
                                if ($row3['revision'] != NULL) {

                                    echo "<p>Enviado</p>";
                                }
                                if ($row3['revision'] == NULL) {
                                    echo "<form action='administrador.php' method='POST'>
                                            <input type='hidden' name='id_pedido' value='" . $row3['id_pedido'] . "'>
                                            <input type='submit' name='revisarCompraProducto' value='Revisado'>
                                            </form>";
                                }
                                echo "</td>";
                                echo "</tr>";

                            }
                            echo "</table>";
                        }

                        echo "<br><br><h1>Solicitudes de Pisos</h1><br>";
                        $sql5 = "SELECT * FROM publicidades as p, usuarios as u WHERE p.revision = 0 AND p.id_usuario = u.id_usuario";
                        $result = $conn->query($sql5);
                        if ($result->num_rows > 0) {
                            echo "<table>";
                            echo "<tr>
                                    <th>Usuario</th>
                                    <th>Importe</th>
                                    <th>Ubicación</th>
                                    <th>Fecha</th>
                                    <th>Aceptación</th>
                                    </tr>";

                            while ($row5 = $result->fetch_assoc()) {

                                echo "<tr>";

                                echo "<td>" . $row5['email'] . "</td>";
                                echo "<td>" . $row5['email'] . "</td>";
                                echo "<td>" . $row5['email'] . "</td>";
                                $sql6 = "SELECT * FROM fotos WHERE id_publicidad = " . $row5['id_publicidad'];
                                $result = $conn->query($sql6);
                                if ($result->num_rows > 0) {
                                    echo "<td>";
                                    while ($row6 = $result->fetch_assoc()) {
                                        $imagen = $row6["foto"];
                                        // Mostrar la imagen en la página
                                        $mostrarImagen = "<img src='data:image/jpeg;base64," . base64_encode($imagen) . "' alt='Imagen del producto'>";
                                        echo $mostrarImagen;
                                    }
                                    echo "</td>";
                                }
                                echo "<td><form action='administrador.php' method='POST'>
                                            <input type='hidden' name='id_publicidad' value='" . $row5['id_publicidad'] . "'>
                                            <input type='submit' name='aceptarCertificado' value='Aceptar'>
                                            </form></td>";
                                echo "</tr>";

                            }

                        }

                        echo "<br><br><h1>Lanzar alerta.</h1><br>";
                        echo "<form action='administrador.php' method='POST'>";
                        echo "<div class='form-group'>";
                        echo "<label for='usuarioSeleccionado'>Selecciona un usuario:</label>";
                        echo "<select class='form-control' name='usuarioSeleccionado'>";
                        $sql6 = "SELECT * FROM usuarios";
                        $result = $conn->query($sql6);
                        if ($result->num_rows > 0) {
                            while ($row6 = $result->fetch_assoc()) {
                                echo "<option value='" . $row6['id_usuario'] . "'>" . $row6['email'] . "</option>";
                            }
                        }
                        echo "</select>";
                        echo "</div>";
                        echo "<div class='form-group'>";
                        echo "<label for='titulo'>Título:</label>";
                        echo "<input type='text' class='form-control' name='titulo'>";
                        echo "</div>";
                        echo "<div class='form-group'>";
                        echo "<label for='texto'>Texto:</label>";
                        echo "<textarea class='form-control' name='texto'></textarea>";
                        echo "</div>";
                        echo "<button type='submit' class='btn btn-primary' name='usuarioAlerta'>Enviar</button>";
                        echo "</form>";


                    }
                    if (isset($_POST['usuarioAlerta'])) {

                        $usuario = $_POST['usuarioSeleccionado'];
                        $titulo = $_POST['titulo'];
                        $texto = $_POST['texto'];
                        $id_admin = $_SESSION['usuario']['id_usuario'];
                        $fechaHora = date('Y-m-d H:i:s'); // Obtiene la fecha y hora actual en el formato deseado
                
                        $sql = "INSERT INTO `alertas`(`titulo`, `descripcion`, `usuario`, `estado`, `fecha_hora`, `id_usuario`) VALUES ('$titulo', '$texto', '$usuario', 0, '$fechaHora', '$id_admin')";
                        sqlINSERT($sql);
                        echo "<script>window.location.href = 'administrador.php?administradorPanel';</script>";
                        exit();

                    }
                    if (isset($_POST['aceptarCertificado'])) {

                        $id_publicidad = $_POST['id_publicidad'];
                        $sql = "UPDATE publicidades SET revision = NULL WHERE id_publicidad = " . $id_publicidad;

                        if (sqlUPDATE($sql)) {
                            echo "<script>window.location.href = 'administrador.php?administradorPanel';</script>";
                            exit();
                        }


                    }
                    if (isset($_POST['revisarCompraUbicacion'])) {
                        $id_publicidad = $_POST['id_publicidad'];
                        $sql = "UPDATE publicidades SET revision = 1 WHERE id_publicidad = " . $id_publicidad;

                        if (sqlUPDATE($sql)) {
                            echo "<script>window.location.href = 'administrador.php?administradorPanel';</script>";
                            exit();
                        }
                    }
                    if (isset($_POST['revisarCompraProducto'])) {
                        $id_pedido = $_POST['id_pedido'];
                        $sql = "UPDATE pedidos SET revision = 1 WHERE id_pedido = " . $id_pedido;

                        if (sqlUPDATE($sql)) {
                            echo "<script>window.location.href = 'administrador.php?administradorPanel';</script>";
                            exit();
                        }
                    }
                    ?>


                    <?php
                    if (isset($_REQUEST['administradorUsuarios'])) {
                        listarUsuarios($_SESSION['usuario']['id_usuario']);
                    }

                    if (isset($_REQUEST['administradorSoportes'])) {
                        ?>
                        <h3>Soportes</h3>
                        <?php listarSoporte(); ?> <br><br>

                        <h3>Solucitud de empresa</h3>
                        <?php listarSoporteEmpresa(); ?> <br><br>
                        <?php
                    }

                    if (isset($_REQUEST['administradorProductos'])) {
                        ?>
                        <div>
                            <div class='container'>
                                <h2 class='mt-5'>Crear nuevo producto</h2>
                                <form action='administrador.php' method='POST' enctype='multipart/form-data'>
                                    <div class='form-group'>
                                        <label for='nombre'>Nombre:</label>
                                        <input type='text' name='nombre' id='nombre' class='form-control' required>
                                    </div>
                                    <div class='form-group'>
                                        <label for='descripcion'>Descripción:</label>
                                        <textarea name='descripcion' id='descripcion' class='form-control' required></textarea>
                                    </div>
                                    <div class='form-group'>
                                        <label for='precio'>Precio:</label>
                                        <input type='text' name='precio' id='precio' class='form-control' required>
                                    </div>
                                    <div class='form-group'>
                                        <label for='puntos'>Puntos:</label>
                                        <input type='text' name='puntos' id='puntos' class='form-control' required>
                                    </div>
                                    <div class='form-group'>
                                        <input type='file' name='imagen' accept='image/*' required>
                                    </div>
                                    <div class='form-group'>
                                        <label for='recompensa_titulo'>Mostrar en:</label>
                                        <select name='recompensa' class='custom-select'>
                                            <option selected disabled>Selecciona una opcion</option>
                                            <option value='0'>Tienda</option>
                                            <option value='1'>Recompensas</option>
                                        </select>
                                    </div>
                                    <input type='submit' name='nuevoProducto' class='btn btn-danger' value='Crear producto'>
                                </form>
                            </div>

                            <div class='container'>
                                <h1 class='mt-5'>Lista de Productos</h1>
                                <?php
                                $sql = "SELECT * FROM productos";
                                $result = sqlSELECT($sql);

                                // Verificar si se encontraron productos
                                if ($result->num_rows > 0) {
                                    // Recorrer los resultados y crear las opciones del select
                                    echo "<table class='table'>";
                                    echo "<thead>";
                                    echo "<tr>";
                                    echo "<th>Nombre</th>";
                                    echo "<th>Descripcion</th>";
                                    echo "<th>Precio</th>";
                                    echo "<th>Puntos</th>";
                                    echo "<th>Foto</th>";
                                    echo "<th>Mostrar</th>";
                                    echo "<th>Estado</th>";
                                    echo "<th>Acciones</th>";

                                    echo "</tr>";
                                    echo "</thead>";
                                    echo "<tbody>";
                                    while ($row = mysqli_fetch_assoc($result)) {

                                        echo "<tr>";
                                        echo "<td><span class='editableProducto' id='nombre' data-producto-id='" . $row["id_producto"] . "'>" . $row["nombre"] . "</span></td>";
                                        echo "<td><span class='editableProducto' id='descripcion' data-producto-id='" . $row["id_producto"] . "'>" . $row["descripcion"] . "</span></td>";
                                        echo "<td><span class='editableProducto' id='precio' data-producto-id='" . $row["id_producto"] . "'>" . $row["precio"] . "</span></td>";
                                        echo "<td><span class='editableProducto' id='puntos' data-producto-id='" . $row["id_producto"] . "'>" . $row["puntos"] . "</span></td>";

                                        $sql2 = "SELECT * FROM `fotos` where id_producto =" . $row['id_producto'];
                                        $result2 = sqlSELECT($sql2);

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

                                        $recompensa = ($row["recompensa"] == 1) ? "Recompensa" : "Tienda";

                                        echo "<td><span id='recompensa'>" . $recompensa . "</span></td>";

                                        $estado = ($row["estado"] == 1) ? "Activado" : "Desactivado";

                                        echo "<td><span id='estado'>" . $estado . "</span></td>";
                                        echo "<td>";
                                        echo "<form action='administrador.php?administradorProductos' method='POST'>";
                                        echo "<input type='hidden' name='idProducto' value='" . $row["id_producto"] . "'>";
                                        echo "<input type='submit' name='activarProducto' value='Activar' class='btn btn-success'>";
                                        echo "<input type='submit' name='desactivarProducto' value='Desactivar' class='btn btn-secondary'>";
                                        echo "<input type='submit' name='borrarProducto' value='Borrar' class='btn btn-danger'>";
                                        echo "</form>";
                                        echo "</td>";
                                        echo "</tr>";

                                    }
                                    echo "</tbody>";
                                    echo "</table>";
                                } else {
                                    echo "<option value=''>No hay tipos de publicidades disponibles</option>";
                                }
                                ?>
                            </div>
                        </div>
                        <?php
                    }
                    if (isset($_POST['nuevoProducto'])) {
                        $nombreProducto = $_POST['nombre'];
                        $descripcionProducto = $_POST['descripcion'];
                        $precioProducto = $_POST['precio'];
                        $puntosProducto = $_POST['puntos'];
                        $recompensaProducto = $_POST['recompensa'];

                        $conn = conectar();
                        $sql = "INSERT INTO `productos`(`nombre`, `descripcion`, `puntos`, `precio`, `recompensa`) VALUES (?, ?, ?, ?, ?)";
                        $stmt = $conn->prepare($sql);
                        // Y mediante un bind_param se establecen los valores.
                        $stmt->bind_param('ssddd', $nombreProducto, $descripcionProducto, $puntosProducto, $precioProducto, $recompensaProducto);
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
                                                        <form action='administrador.php' method='POST'>
                                                        <input type='hidden' name='id_mision' value='" . $row['id_mision'] . "'>
                                                        <input type='submit' name='aceptarMision' class='btn btn-success' value='Aceptar'>
                                                        </form>
                                                        <form action='administrador.php' method='POST'>
                                                        <input type='hidden' name='id_mision' value='" . $row['id_mision'] . "'>
                                                        <input type='submit' name='rechazarMision' class='btn btn-success' value='Rechazar'>
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
                        $sqlUpdate = "UPDATE usuarios AS u, misiones AS m SET u.puntos = u.puntos + 10 WHERE m.id_mision = ? AND u.id_usuario = m.id_usuario";
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