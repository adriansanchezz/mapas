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
    if (isset($_SESSION['usuario'])) {
        // Menu general
        menu_general(); ?>

        <!-- Menu horizontal -->
        <div class="d-flex vh-100">
            <div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 240px;">
                <br><br>
                <!-- Crear submenu con sus opciones -->
                <ul class="nav nav-pills flex-column mb-auto">
                    <li>
                        <form action="cuenta.php" method="post">
                            <button type="submit" name="cuentaPrincipal" class="btn btn-link nav-link text-white">Principal
                            </button>
                        </form>
                    </li>

                    <li>
                        <form action="cuenta.php" method="post">
                            <button type="submit" name="cambiar_nombre" class="btn btn-link nav-link text-white">Modificar
                                nombre</button>
                        </form>
                    </li>
                    <li>
                        <form action="cuenta.php" method="post">
                            <button type="submit" name="cambiar_correo" class="btn btn-link nav-link text-white">Modificar
                                correo</button>
                        </form>
                    </li>
                    <li>
                        <form action="cuenta.php" method="post">
                            <button type="submit" name="cambiar_contra" class="btn btn-link nav-link text-white">Modificar
                                constraseña</button>
                        </form>
                    </li>
                    <li>
                        <form action="cuenta.php" method="post">
                            <button type="submit" name="suscripcion"
                                class="btn btn-link nav-link text-white">Suscripción</button>
                        </form>
                    </li>
                </ul>
            </div>

            <?php
            if (isset($_REQUEST['activarPublicidad'])) {
                $id = $_POST['id_publicidad'];
                activarPublicidad($id);
            }
            ?>

            <?php
            if (isset($_REQUEST['desactivarPublicidad'])) {
                $id = $_POST['id_publicidad'];
                desactivarPublicidad($id);
            }
            ?>

            <?php
            if (isset($_REQUEST['borrarPublicidad'])) {
                $id = $_POST['id_publicidad'];
                borrarPublicidad($id);
            }
            ?>

            <?php
            if (isset($_REQUEST['cuentaPrincipal'])) {
                ?>
                <div class="flex-grow-1">
                    <div id="seccion1" class="p-3" style="display: block;">
                        <h2>Informacion personal</h2><br>
                        <!-- get hace falta -->
                        <h3>Correo</h3>
                        <?php echo "<b>Correo actual: </b>" . $_SESSION['usuario']['email']; ?> <br><br>
                        <div class="flex-grow-1">
                            <form action="cuenta.php" method="post">
                                <div class="form-group">
                                    <label for="correo">Nuevo correo</label>
                                    <input type="email" class="form-control" name="nuevoCorreo"
                                        placeholder="Introducir el correo" required>
                                </div>
                                <div class="form-group">
                                    <label for="correo">Confirmar correo</label>
                                    <input type="email" class="form-control" name="nuevoCorreo2"
                                        placeholder="Confirmar el correo" required>
                                </div>
                                <input type="submit" class="btn btn-primary" name="confirmarCorreo" value="Confirmar">
                            </form>
                        </div><br><br>


                        <h3>Nombre</h3><br>
                        <?php echo "<b>Nombre actual<: /b>" . $_SESSION['usuario']['nombre']; ?> <br><br>
                        <div class="flex-grow-1">
                            <form action="cuenta.php" method="post">
                                <div class="form-group">
                                    <label for="nombre">Nombre nuevo</label>
                                    <input type="text" class="form-control" name="nuevoNombre" placeholder="Introduce el nombre"
                                        required>
                                </div>
                                <input type="submit" class="btn btn-primary" name="guardarNombre" value="Confirmar">
                            </form>
                        </div><br><br>


                        <h3>Contraseña</h3><br>
                        <div class="flex-grow-1">
                            <form action="cuenta.php" method="post">
                                <div class="form-group">
                                    <label for="contraseña">Contraseña actual</label>
                                    <input type="password" class="form-control" name="antiguoPass"
                                        placeholder="Introducir la contraseña" required>
                                </div>
                                <div class="form-group">
                                    <label for="contraseña">Nuevo contraseña</label>
                                    <input type="password" class="form-control" name="nuevoPass"
                                        placeholder="Introducir la contraseña" required>
                                </div>
                                <div class="form-group">
                                    <label for="contraseña">Confirmar contraseña</label>
                                    <input type="password" class="form-control" name="nuevoPass2"
                                        placeholder="Confirmar la contraseña" required>
                                </div>
                                <input type="submit" class="btn btn-primary" name="cambioContra" value="Confirmar">
                            </form>
                        </div><br><br>


                        <h3>Propiedades</h3>
                        <?php listarPublicidades($_SESSION['usuario']['id_usuario']); ?>
                    </div>
                </div>
                <?php
            }

            if (isset($_REQUEST['guardarNombre'])) {
                guardarNombre($_POST['nuevoNombre'], $_SESSION['usuario']['id_usuario']);
            }

            if (isset($_POST['confirmarCorreo'])) {
                guardarCorreo($_POST['nuevoCorreo'], $_POST['nuevoCorreo2'], $_SESSION['usuario']['id_usuario']);
            }

            if (isset($_POST['cambioContra'])) {
                guardarPassword($_POST['antiguoPass'], $_POST['nuevoPass'], $_POST['nuevoPass2'], $_SESSION['usuario']['id_usuario']);
            }
            ?>

            <?php
            if (isset($_POST['cambiar_nombre'])) {
                ?>

                <?php
            } else
            ?>

            <?php
            if (isset($_POST['cambiar_correo'])) {
                ?>

                <?php
            } else
            ?>

            <?php
            if (isset($_POST['cambiar_contra'])) {
                ?>

                <?php
            } else
            ?>



            <?php
            // Verificar si se recibió un pedido para editar un usuario
            if (isset($_GET['editarPublicidad'])) {
                // Obtener el ID del producto a editar
                $publicidadId = $_GET['editarPublicidad'];

                // Obtener el nuevo valor del producto
                $nuevoValor = $_POST['nuevoValor'];

                // Obtener el nombre de la columna a actualizar (puede venir como parámetro en la solicitud)
                $columna = $_POST['columna']; // Asegúrate de validar y sanitizar este valor
        
                // Por ejemplo, supongamos que tienes una tabla llamada "productos"
                // Puedes utilizar una consulta SQL para actualizar el valor del producto en la columna específica
                // Ejemplo con MySQLi:
                $conexion = conectar();
                $columna = $conexion->real_escape_string($columna); // Escapar el nombre de la columna para evitar inyección de SQL
                $consulta = "UPDATE publicidades SET $columna = '$nuevoValor' WHERE id_publicidad = $publicidadId";
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
            if (isset($_GET['editarPublicidad'])) {
                // Obtener el ID del producto a editar
                $publicidadId = $_GET['editarPublicidad'];

                // Obtener el nuevo valor del producto
                $nuevoValor = $_POST['nuevoValor'];

                // Obtener el nombre de la columna a actualizar (puede venir como parámetro en la solicitud)
                $columna = $_POST['columna']; // Asegúrate de validar y sanitizar este valor
        
                // Por ejemplo, supongamos que tienes una tabla llamada "productos"
                // Puedes utilizar una consulta SQL para actualizar el valor del producto en la columna específica
                // Ejemplo con MySQLi:
                $conexion = conectar();
                $columna = $conexion->real_escape_string($columna); // Escapar el nombre de la columna para evitar inyección de SQL
                $consulta = "UPDATE publicidades SET $columna = '$nuevoValor' WHERE id_publicidad = $publicidadId";
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
        <?php
    } else {
        echo ('Acceso denegado');
        print '<a href ="../index.php"><button>Volver</button></a>';
        session_destroy();
    }
    ?>

    <script>
        administradorPublicidades();
    </script>
</body>

</html>