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
    <link href="../css/administrador.css" rel="stylesheet" type="text/css">
</head>

<body>
    <?php
    // comprobar si tiene sesión y si tiene rol de admin.
    if (isset($_SESSION['usuario']) && validarAdmin($_SESSION['usuario']['id_usuario'])) {
        // Menu general
        menu_general(); ?>

        <!-- Menu lateral -->
        <div class="d-flex vh-100">
            <div id="sidebar">
                <div class="p-2">
                    <a href="administrador.php?administradorPanel"
                        class="navbar-brand text-center text-light w-100 p-4 border-bottom">
                        Administrador
                    </a>
                </div>
                <div id="sidebar-accordion" class="accordion">
                    <div class="list-group">
                        <a href="administrador.php?administradorPanel"
                            class="list-group-item list-group-item-action text-light" id="sidebar2">
                            <i class="fa fa-tachometer mr-3" aria-hidden="true"></i>Panel de control
                        </a>

                        <a href="administrador.php?administradorUsuarios"
                            class="list-group-item list-group-item-action text-light" id="sidebar2">
                            <i class="fa fa-user mr-3" aria-hidden="true"></i>Usuarios
                        </a>

                        <a href="administrador.php?administradorProductos"
                            class="list-group-item list-group-item-action text-light" id="sidebar2">
                            <i class="fa fa-shopping-cart mr-3" aria-hidden="true"></i>Productos
                        </a>
                        <a href="administrador.php?administradorMisiones"
                            class="list-group-item list-group-item-action text-light" id="sidebar2">
                            <i class="fa fa-bullseye mr-3" aria-hidden="true"></i>Misiones
                        </a>
                        <a href="administrador.php?administradorSoportes"
                            class="list-group-item list-group-item-action text-light" id="sidebar2">
                            <i class="fa fa-cog mr-3" aria-hidden="true"></i>Soportes
                        </a>
                    </div>
                </div>
            </div>


            <div class="flex-grow-1">
                <div id="seccion1" class="p-3" style="display: block;">

                    <?php
                    // Botón bloquear usuario.
                    if (isset($_REQUEST['bloquearUsuario'])) {
                        $id = $_POST['id_usuario'];
                        bloquearUsuario($id);
                    }
                    ?>

                    <?php
                    // Botón de eliminar rol a un usuario.
                    if (isset($_REQUEST['eliminarRolUsuario'])) {
                        $id = $_POST['id_usuario'];
                        $nombre_rol = $_POST['nombre_rol'];
                        eliminarRoles($id, $nombre_rol);
                    }
                    ?>

                    <?php
                    // Botón de agregar rol a un usuario.
                    if (isset($_REQUEST['agregarRolUsuario'])) {
                        $id = $_POST['id_usuario'];
                        $nombre_rol = $_POST['nombre_rol'];
                        agregarRoles($id, $nombre_rol);
                    }
                    ?>

                    <?php
                    // Borrar producto.
                    if (isset($_POST['borrarProducto'])) {
                        $id = $_POST['idProducto'];
                        borrarProducto($id);
                    }
                    ?>

                    <?php
                    // Desactivar un producto.
                    if (isset($_POST['desactivarProducto'])) {
                        $id = $_POST['idProducto'];
                        desactivarProducto($id);
                    }
                    ?>

                    <?php
                    // Activar un producto.
                    if (isset($_POST['activarProducto'])) {
                        $id = $_POST['idProducto'];
                        activarProducto($id);
                    }
                    ?>

                    <?php
                    // Revisión del soporte.
                    if (isset($_POST['revisarSoporte'])) {
                        $id = $_POST['id_soporte'];
                        $responder = isset($_POST['responderSoporte']) ? $_POST['responderSoporte'] : null;
                        finalizarSoporte($id, $responder);
                    }
                    ?>

                    <?php
                    // Aprobar la solicitud de una empresa.
                    if (isset($_POST['aprovarEmpresa'])) {
                        $id = $_POST['id_empresa'];
                        $responder = isset($_POST['responderSoporteEmpresa']) ? $_POST['responderSoporteEmpresa'] : null;
                        aprovarSolicitudEmpresa($id, $responder);
                    }
                    ?>

                    <?php
                    // Rechazar solicitud de una empresa.
                    if (isset($_POST['rechazarEmpresa'])) {
                        $id = $_POST['id_empresa'];
                        $responder = isset($_POST['responderSoporteEmpresa']) ? $_POST['responderSoporteEmpresa'] : null;
                        rechazarSolicitudEmpresa($id, $responder);
                    }
                    ?>

                    <?php
                    // Si el admin quiere entrar al panel:
                    if (isset($_REQUEST['administradorPanel'])) {
                        ?>

                        <!-- Page Heading -->
                        <div class="d-sm-flex align-items-center justify-content-between mb-4">
                            <h1 class="h3 mb-0 text-gray-800">Panel de control</h1>
                        </div>

                        <!-- Content Row -->
                        <div class="row">

                            <!-- Earnings (Monthly) Card Example -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-primary shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                    Visitas (Total)</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                    <?php verVisitaTotal(); ?>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fa-solid fa-chart-simple fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Earnings (Monthly) Card Example -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-success shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                    Visitas (Mensual)</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                    <?php verVisitaTotal(); ?>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fa-solid fa-chart-simple fa-2x text-gray-300"></i>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Earnings (Monthly) Card Example -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-info shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Pedidos
                                                    realizados
                                                </div>
                                                <div class="row no-gutters align-items-center">
                                                    <div class="col-auto">
                                                        <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                                            <?php verPedidoTotal(); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Pending Requests Card Example -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-warning shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                    Ingresos totales</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                    <?php verImporteTotal(); ?> €
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-euro-sign fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                        <?php ubicacionesCompradas(); ?>
                        </div>
                        <div>
                        <?php productosComprados(); ?>
                        </div>
                        <div>
                        <?php solicitudesPisos(); ?>
                        </div>
                        <div>
                        <?php lanzarAlertas(); ?>
                        </div>
                        <?php
                    }
                    // Para ejecutar el lanzado de alerta.
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

                    // Para aceptar el certificado de un piso.
                    if (isset($_POST['aceptarCertificado'])) {

                        $id_publicidad = $_POST['id_publicidad'];
                        $sql = "UPDATE publicidades SET revision = 1 WHERE id_publicidad = " . $id_publicidad;

                        if (sqlUPDATE($sql)) {
                            echo "<script>window.location.href = 'administrador.php?administradorPanel';</script>";
                            exit();
                        }


                    }

                    // Para rechazar el certificado de un piso.
                    if (isset($_POST['rechazarCertificado'])) {

                        $id_publicidad = $_POST['id_publicidad'];
                        $sql = "UPDATE publicidades SET revision = 0 WHERE id_publicidad = " . $id_publicidad;

                        if (sqlUPDATE($sql)) {
                            echo "<script>window.location.href = 'administrador.php?administradorPanel';</script>";
                            exit();
                        }


                    }

                    // Para marcar como revisada la compra de una ubicación.
                    if (isset($_POST['revisarCompraUbicacion'])) {
                        $id_publicidad = $_POST['id_publicidad'];
                        $sql = "UPDATE publicidades SET revision = 3 WHERE id_publicidad = " . $id_publicidad;
                        $sql2 = "UPDATE pedidos AS p, lineas_pedidos AS lp SET p.revision = 1 WHERE lp.id_pedido = p.id_pedido AND lp.id_publicidad = " . $id_publicidad;

                        if (sqlUPDATE($sql)) {
                            if (sqlUPDATE($sql2)) {
                                echo "<script>window.location.href = 'administrador.php?administradorPanel';</script>";
                                exit();
                            }
                        }

                    }

                    // Para marcar como revisada la compra de un producto.
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
                    // Para que el admin pueda administrar los usuarios existentes en la aplicación.
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
                                    <input type='submit' name='nuevoProducto' class='btn btn-success' value='Crear producto'>
                                </form>
                            </div>

                            <div class='container'>
                                <h1 class='mt-5'>Lista de Productos</h1>
                                <?php
                                    listarProductos();
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
                        $estado = 1;

                        $conn = conectar();
                        $sql = "INSERT INTO `productos`(`nombre`, `descripcion`, `puntos`, `precio`, `recompensa`, `estado`) VALUES (?, ?, ?, ?, ?, ?)";
                        $stmt = $conn->prepare($sql);
                        // Y mediante un bind_param se establecen los valores.
                        $stmt->bind_param('ssdddi', $nombreProducto, $descripcionProducto, $puntosProducto, $precioProducto, $recompensaProducto, $estado);
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
                                        Misiones en espera de aceptación:
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
                                                misionesSinAceptar();

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