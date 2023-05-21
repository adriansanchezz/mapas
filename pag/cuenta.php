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
                            <button type="submit" name="pricipal" class="btn btn-link nav-link text-white">Principal
                            </button>
                        </form>
                    </li>

                    <li>
                        <form action="cuenta.php" method="post">
                            <button type="submit" name="cambiar_nombre" class="btn btn-link nav-link text-white">Cambiar
                                nombre</button>
                        </form>
                    </li>
                    <li>
                        <form action="cuenta.php" method="post">
                            <button type="submit" name="cambiar_correo" class="btn btn-link nav-link text-white">Cambiar
                                correo</button>
                        </form>
                    </li>
                    <li>
                        <form action="cuenta.php" method="post">
                            <button type="submit" name="cambiar_contra" class="btn btn-link nav-link text-white">Cambiar
                                constraseña</button>
                        </form>
                    </li>
                </ul>
            </div>

            <div class="flex-grow-1">
                <div id="seccion1" class="p-3" style="display: block;">
                    <h2>Informacion personal</h2><br>
                    <!-- get hace falta -->
                    <h3>Correo</h3>
                    <?php echo $_SESSION['usuario']['email']; ?> <br><br>

                    <h3>Nombre</h3>
                    <?php echo $_SESSION['usuario']['nombre']; ?> <br><br>

                    <h3>Propiedades</h3>
                    <?php listarPublicidades($_SESSION['usuario']['id_usuario']); ?>
                </div>
            </div>



            <?php
            if (isset($_POST['cambiar_nombre'])) {
                ?>
                <div class="flex-grow-1">
                    <div id="seccion1" class="p-3" style="display: block;">
                        <form action="cuenta.php" method="post">
                            <h3>Modificar el Nombre</h3><br />
                            <input type="text" name="nuevoNombre" placeholder="Nuevo nombre" required><br /><br />

                            <input type="submit" name="guardarNombre" value="Guardar" />
                        </form>
                    </div>
                </div>
                <?php
            } else if (isset($_POST['guardarNombre'])) {
                guardarNombre($_POST['nuevoNombre'], $_SESSION['usuario']['id_usuario']);
            }
            ?>

            <?php
            if (isset($_POST['cambiar_correo'])) {
                ?>
                <div class="flex-grow-1">
                    <div id="seccion1" class="p-3" style="display: block;">
                        <form action="cuenta.php" method="post">
                            <h3>Modificar el Correo</h3><br />
                            <input type="email" name="nuevoCorreo" placeholder="Nuevo correo" required><br />
                            <input type="email" name="nuevoCorreo2" placeholder="Confirmar correo" required><br /><br />

                            <input type="submit" name="confirmarCorreo" value="Confirmar" />
                        </form>
                    </div>
                </div>
                <?php
            } else if (isset($_POST['confirmarCorreo'])) {
                guardarCorreo($_POST['nuevoCorreo'], $_POST['nuevoCorreo2'], $_SESSION['usuario']['id_usuario']);
            }
            ?>

            <?php
            if (isset($_POST['cambiar_contra'])) {
                ?>
                <div class="flex-grow-1">
                    <div id="seccion1" class="p-3" style="display: block;">
                        <form action="cuenta.php" method="post">
                            <h3>Modificar la Constraseña</h3><br />
                            <input type="password" name="nuevoPass" placeholder="Nuevo contraseña" required><br />
                            <input type="password" name="nuevoPass2" placeholder="Confirmar contraseña" required><br /><br />

                            <input type="submit" name="cambioContra" value="Confirmar" />
                        </form>
                    </div>
                </div>
                <?php
            } else if (isset($_POST['cambioContra'])) {
                guardarPassword($_POST['nuevoPass'], $_POST['nuevoPass2'], $_SESSION['usuario']['id_usuario']);
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