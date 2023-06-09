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
    <link href="../css/cuenta.css" rel="stylesheet" type="text/css">
</head>

<body>
    <?php
    if (isset($_SESSION['usuario'])) {
        // Menu general
        menu_general(); ?>

        <!-- Menu horizontal -->
        <div class="d-flex vh-100">
            <div id="sidebar">
                <div class="p-2">
                    <a href="cuenta.php?cuentaInformacion"
                        class="navbar-brand text-center text-light w-100 p-4 border-bottom">
                        Cuenta
                    </a>
                </div>
                <div id="sidebar-accordion" class="accordion">
                    <div class="list-group">
                        <a href="cuenta.php?cuentaInformacion" class="list-group-item list-group-item-action text-light"
                            id="sidebar2">
                            <i class="fa fa-address-card mr-3" aria-hidden="true"></i>Informacion
                        </a>

                        <a href="cuenta.php?publicidades" class="list-group-item list-group-item-action text-light"
                            id="sidebar2">
                            <i class="fa fa-window-maximize mr-3" aria-hidden="true"></i>Publicidades
                        </a>

                        <a href="cuenta.php?usuarioSoportes" class="list-group-item list-group-item-action text-light"
                            id="sidebar2">
                            <i class="fa fa-ticket mr-3" aria-hidden="true"></i>Soportes
                        </a>

                        <a href="cuenta.php?suscripcion" class="list-group-item list-group-item-action text-light"
                            id="sidebar2">
                            <i class="fa fa-plus mr-3" aria-hidden="true"></i>Suscripción
                        </a>
                    </div>
                </div>
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
            if (isset($_REQUEST['cuentaInformacion'])) {
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
                                    <div class="input-group mb-3">
                                        <input type="email" class="form-control" name="nuevoCorreo"
                                            placeholder="Introducir el correo" required>
                                        <div class="input-group-append">
                                            <input type="submit" class="btn btn-outline-primary" name="confirmarCorreo"
                                                value="Confirmar">
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div><br><br>


                        <h3>Nombre</h3><br>
                        <?php echo "<b>Nombre actual: </b>" . $_SESSION['usuario']['nombre']; ?> <br><br>
                        <div class="flex-grow-1">
                            <form action="cuenta.php" method="post">
                                <div class="form-group">
                                    <label for="nombre">Nombre nuevo</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" name="nuevoNombre"
                                            placeholder="Introduce el nombre" required>
                                        <div class="input-group-append">
                                            <input type="submit" class="btn btn-outline-primary" name="guardarNombre"
                                                value="Confirmar">
                                        </div>
                                    </div>
                                </div>
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
                                    <div class="input-group mb-3">
                                        <input type="password" class="form-control" name="nuevoPass2"
                                            placeholder="Confirmar la contraseña" required>
                                        <div class="input-group-append">
                                            <input type="submit" class="btn btn-outline-primary" name="cambioContra"
                                                value="Cambiar">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div><br><br>
                    </div>
                </div>
                <?php
            }

            if (isset($_REQUEST['guardarNombre'])) {
                guardarNombre($_POST['nuevoNombre'], $_SESSION['usuario']['id_usuario']);
            }

            if (isset($_POST['confirmarCorreo'])) {
                guardarCorreo($_POST['nuevoCorreo'], $_SESSION['usuario']['id_usuario']);
            }

            if (isset($_POST['cambioContra'])) {
                guardarPassword($_POST['antiguoPass'], $_POST['nuevoPass'], $_POST['nuevoPass2'], $_SESSION['usuario']['id_usuario']);
            }
            ?>


            <?php
            // Verificar si se recibió un pedido para editar un usuario
            if (isset($_REQUEST['suscripcion'])) {
                ?>
                <div class="flex-grow-1">
                    <div class='col-md-6 col-lg-4'>
                        <div class='card my-3'>
                            <div class='card-body'>
                                <h3 class='card-title'>Suscripción Mensual</h3>
                                <p class='card-text'>Suscripción mesual, cuenta vip en 30 dias, con ventaja de 5% descuento en
                                    las compras</p>

                                <form>
                                    <button type="button" id="btnMensual" class="btn btn-primary btn-lg"
                                        data-suscripcion="mensual">Suscripción Mensual</button>
                                </form>
                            </div>
                        </div>
                    </div>


                    <div class='col-md-6 col-lg-4'>
                        <div class='card my-3'>
                            <div class='card-body'>
                                <h3 class='card-title'>Suscripción Anual</h3>
                                <p class='card-text'>Suscripción anual, cuenta vip en 1 año, con ventaja de 5% descuento en
                                    las compras</p>
                                <form>
                                    <button type="button" id="btnAnual" class="btn btn-primary btn-lg"
                                        data-suscripcion="anual">Suscripción Anual</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="paypal-button-container"></div>
                <script
                    src="https://www.paypal.com/sdk/js?client-id=Ae-QOggCqT3W10C1Q7U1lTDaYwmgEsmPuPxDuQEOD4uHZK0DMvJb2brCahcG-HMPPBti9IsX8pCsB-Db&currency=EUR"></script>
                <script>
                    var selectedSuscripcion = ""; // Variable para almacenar la suscripción seleccionada

                    // Evento click del botón de suscripción mensual
                    document.getElementById("btnMensual").addEventListener("click", function () {
                        selectedSuscripcion = "mensual";
                        updateButtonStyle(this);
                    });

                    // Evento click del botón de suscripción anual
                    document.getElementById("btnAnual").addEventListener("click", function () {
                        selectedSuscripcion = "anual";
                        updateButtonStyle(this);
                    });

                    function updateButtonStyle(button) {
                        // Remover la clase 'active' de todos los botones
                        var buttons = document.getElementsByClassName("btn btn-primary btn-lg");
                        for (var i = 0; i < buttons.length; i++) {
                            buttons[i].classList.remove("active");
                        }
                        // Agregar la clase 'active' al botón seleccionado
                        button.classList.add("active");
                    }

                    paypal.Buttons({
                        style: {
                            color: 'blue',
                            shape: 'pill',
                            label: 'pay'
                        },
                        createOrder: function (data, actions) {
                            var value = (selectedSuscripcion === "mensual") ? 4.99 : 49.99;
                            return actions.order.create({
                                purchase_units: [{
                                    amount: {
                                        value: value
                                    }
                                }]
                            });
                        },
                        onApprove: function (data, actions) {
                            return actions.order.capture().then(function (details) {
                                // Redirigir a la página 'cuenta.php' con el parámetro 'suscrito'
                                var suscrito = (selectedSuscripcion === "mensual") ? "mensual" : "anual";
                                window.location.href = "cuenta.php?suscrito=" + suscrito;
                            });
                        },
                        onCancel: function (data) {
                            alert("Pago cancelado");
                        }
                    }).render('#paypal-button-container');
                </script>

                <?php
            }
            if (isset($_GET['suscrito'])) {
                $suscrito = $_GET['suscrito'];
                $id_usuario = $_SESSION['usuario']['id_usuario'];
                // Obtener la fecha actual
                $fechaActual = date('Y-m-d');

                // Obtener la fecha actual más un mes o un año según la suscripción
                if ($suscrito === 'mensual') {
                    $fechaVencimiento = date('Y-m-d', strtotime('+1 month'));
                } elseif ($suscrito === 'anual') {
                    $fechaVencimiento = date('Y-m-d', strtotime('+1 year'));
                }

                // Construir la consulta SQL
                $sql = "UPDATE usuarios SET VIP = '$fechaVencimiento' WHERE id_usuario = '$id_usuario'";
                sqlUPDATE($sql);
                agregarRoles($_SESSION['usuario']['id_usuario'], "VIP");

                // Puedes realizar cualquier acción adicional que necesites aquí
        
                // Enviar una respuesta de éxito
                echo "Suscripción actualizada correctamente";
            }
            ?>


            <?php
            // Verificar si se recibió un pedido para editar un usuario
            if (isset($_REQUEST['suscripcion'])) {
                ?>
                <div class="flex-grow-1">
                    <div class="p-3" style="display: block;">



                    </div>
                </div>
                <?php
            }
            ?>

            <?php
            // Verificar si se recibió un pedido para editar un usuario
            if (isset($_REQUEST['publicidades'])) {
                ?>
                <div class="flex-grow-1">
                    <div class="p-3" style="display: block;">

                        <h3>Propiedades</h3>
                        <?php listarPublicidades($_SESSION['usuario']['id_usuario']); ?>

                    </div>
                </div>
                <?php
            }

            if (isset($_REQUEST['usuarioSoportes'])) {
                ?>
                <div class="flex-grow-1">
                    <div id="seccion1" class="p-3" style="display: block;">
                        <h3>Soportes</h3>
                        <?php verSoporte($_SESSION['usuario']['id_usuario']); ?> <br><br>

                        <h3>Solucitud de empresa</h3>
                        <?php verSolicitudEmpresa($_SESSION['usuario']['id_usuario']); ?> <br><br>
                    </div>
                </div>
                <?php
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