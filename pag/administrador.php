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
    <?php
    if (isset($_SESSION['usuario'])) {
        // Menu general
        menu_general(); ?>
        <!-- Menu horizontal -->
        <div class="d-flex vh-100">
            <div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 200px;">
                <hr>
                <!-- Crear submenu con sus opciones -->
                <ul class="nav nav-pills flex-column mb-auto">
                    <li class="nav-item">
                        <form action="administrador.php" method="get">
                            <button type="submit" name="administradorPanel" class="btn btn-link nav-link text-white">
                                Panel de control
                            </button>
                        </form>
                    </li>
                    <li class="nav-item">
                        <form action="administrador.php" method="get">
                            <button type="submit" name="administradorRoles"
                                class="btn btn-link nav-link text-white">
                                Roles
                            </button>
                        </form>
                    </li>
                    <li class="nav-item">
                        <form action="administrador.php" method="get">
                            <button type="submit" name="administradorEmpresas" class="btn btn-link nav-link text-white">
                                Usuario
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
                    <li class="nav-item">
                        <form action="administrador.php" method="get">
                            <button type="submit" name="administradorNoticia" class="btn btn-link nav-link text-white">
                                Noticia
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
            <div class="flex-grow-1">
                <div id="seccion1" class="p-3" style="display: block;">


                    <?php
                    if (isset($_REQUEST['administradorPanel'])) {
                        ?>
                        <h2>Panel de control</h2><br>

                        Visitas total obtenida:
                        <?php
                        verVisitaTotal();
                    }
                    ?>


                    <?php
                    if (isset($_REQUEST['administradorRoles'])) {
                        listarRoles($_SESSION['usuario']['id_usuario']);
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
                        echo "<input type='number' name='precio' id='precio' class='form-control' required>";
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
                                echo "<td><p class='editable' id='nombre' data-producto-id='" . $row["id_producto"] . "'>" . $row["nombre"] . "</p></td>";
                                echo "<td><p class='editable' id='descripcion' data-producto-id='" . $row["id_producto"] . "'>" . $row["descripcion"] . "</p></td>";
                                echo "<td><p class='editable' id='precio' data-producto-id='" . $row["id_producto"] . "'>" . $row["precio"] . "</p></td>";

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
                    if (isset($_REQUEST['anhadirProductos'])) {


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
                
                        // Validar y sanitizar el nombre de la columna
                        $columnasPermitidas = array('nombre', 'descripcion', 'precio'); // Lista de columnas permitidas
                        if (!in_array($columna, $columnasPermitidas)) {
                            echo "Columna no válida";
                            exit();
                        }

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

        function prueba() {
            // Obtén todos los elementos con la clase "editable"
            var editables = document.getElementsByClassName('editable');

            // Agrega un controlador de eventos a cada elemento editable
            for (var i = 0; i < editables.length; i++) {
                editables[i].addEventListener('click', function () {
                    if (!this.classList.contains('editing')) {
                        // Marcar el elemento como en edición.
                        this.classList.add('editing');

                        // Obtén el texto actual del valor
                        var valorActual = this.innerText;

                        // Obtén el nombre de la columna desde el id del elemento
                        var columna = this.id;

                        // Crea un input para editar
                        var inputEdicion = document.createElement('input');
                        inputEdicion.type = 'text';
                        inputEdicion.value = valorActual;

                        // Reemplaza el elemento "valor" con el input de edición
                        this.innerText = '';
                        this.appendChild(inputEdicion);

                        // Poner el foco en el input de edición
                        inputEdicion.focus();

                        // Guardar los cambios al presionar Enter en el input de edición
                        inputEdicion.addEventListener('keyup', function (event) {
                            if (event.key === 'Enter') {
                                // Obtén el nuevo valor del input de edición
                                var nuevoValor = inputEdicion.value;

                                // Realizar la actualización en el mismo archivo PHP
                                var productoId = this.parentNode.getAttribute('data-producto-id');
                                var url = 'administrador.php?editarProducto=' + productoId;
                                var data = 'nuevoValor=' + nuevoValor + '&columna=' + columna;

                                // Realizar una solicitud POST utilizando AJAX
                                var xhr = new XMLHttpRequest();
                                xhr.open('POST', url, true);
                                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                                xhr.onreadystatechange = function () {
                                    if (xhr.readyState === 4 && xhr.status === 200) {
                                        // Manejar la respuesta del servidor después de la actualización
                                        console.log(xhr.responseText); // Puedes mostrar un mensaje de éxito o realizar alguna otra acción

                                        // Restaurar el elemento original con el nuevo valor
                                        var nuevoValorElemento = document.createElement('p');
                                        nuevoValorElemento.className = 'editable';
                                        nuevoValorElemento.id = columna; // Asegúrate de agregar el id de la columna al nuevo elemento
                                        nuevoValorElemento.innerText = nuevoValor;
                                        var parentElement = inputEdicion.parentNode;
                                        parentElement.innerText = '';
                                        parentElement.appendChild(nuevoValorElemento);
                                        parentElement.classList.remove('editing');
                                    }
                                };
                                xhr.send(data);
                            }
                        });

                        // Cancelar la edición al presionar Esc en el input de edición
                        inputEdicion.addEventListener('keyup', function (event) {
                            if (event.key === 'Escape') {
                                // Restaurar el elemento original sin realizar la actualización
                                var parentElement = inputEdicion.parentNode;
                                parentElement.innerText = valorActual;
                                parentElement.classList.remove('editing');
                            }
                        });
                    }
                });
            }
        }

        window.onload = prueba;

    </script>

</body>

</html>