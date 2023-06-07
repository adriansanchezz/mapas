<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
// Menu index
function menu_index()
{
    ?>
    <header>
        <a class="logo" href="index.php">DisplayAds</a>
        <ul class="cont-ul">
            <a href="index.php">
                <li>Inicio</li>
            </a>
            <li class="nosotros">
                Nosotros
                <ul class="ul-second">
                    <a href="nosotros.php">
                        <li>Contacto</li>
                    </a>
                    <a href="nosotros.php">
                        <li>Ayuda</li>
                    </a>
                </ul>
            </li>
            <a href="login.php">
                <li><i class="fa-solid fa-right-to-bracket"></i></li>
            </a>
        </ul>
    </header>
    <?php
}
?>

<?php
//Menu luego de iniciar sesión
function menu_general()
{
    ?>
    <div class="separar">
        <header>
            <div class="cuerpo-menu">
                <nav class="menu">
                    <ul>
                        <li><a href="principal.php" id="selected"></a></li>
                        <?php
                        if (validarUsuario($_SESSION['usuario']['id_usuario'])) {
                            ?>
                            <li><a href="usuario.php">Usuario</a>
                                <ul>
                                    <li><a href="usuario.php?usuarioMapa">Mapa</a></li>
                                    <li><a href="usuario.php?usuarioTienda">Tienda</a></li>
                                </ul>
                            </li>
                            <?php
                        }
                        ?>

                        <?php
                        if (validarVigilante($_SESSION['usuario']['id_usuario'])) {
                            ?>
                            <li><a href="vigilante.php?vigilantePrincipal">Vigilante</a>
                                <ul>
                                    <li><a href="vigilante.php?misiones">Misiones</a></li>
                                    <li><a href="vigilante.php?recompensas">Recompensas</a></li>
                                </ul>
                            </li>
                            <?php
                        }
                        ?>

                        <?php
                        if (validarEmpresa($_SESSION['usuario']['id_usuario'])) {
                            ?>
                            <li><a href="empresa.php?empresaPrincipal">Empresa</a>
                                <ul>
                                    <li><a href="empresa.php?empresaMapa">Publicitarse</a></li>
                                    <li><a href="empresa.php?">Informacion</a></li>
                                </ul>
                            </li>
                            <?php
                        }
                        ?>

                        <?php
                        if (validarAdmin($_SESSION['usuario']['id_usuario'])) {
                            ?>
                            <li><a href="administrador.php?administradorPanel">Administrador</a>
                                <ul>
                                    <li><a href="administrador.php?administradorUsuarios">Usuario</a></li>
                                    <li><a href="administrador.php?administradorProductos">Productos</a></li>
                                    <!-- <li><a href="administrador.php?administradorNoticias">Noticias</a></li> -->
                                    <li><a href="administrador.php?administradorMisiones">Misiones</a></li>
                                </ul>
                            </li>
                            <?php
                        }
                        ?>
                        <li><a href="cuenta.php?cuentaPrincipal">Cuenta</a></li>
                        <li><a href="soporte.php">Soporte</a></li>

                        <li>
                            <div class="nav-link">
                                <i class="fa-sharp fa-solid fa-bell bell" style="color: #ffffff;"></i>
                            </div>
                        </li>


                        <li>
                            <form class="form-inline my-2 my-lg-0" action="../index.php" method="post">
                                <button class="btn nav-link" name="cerrarSesion" type="submit">
                                    <i class="fa-solid fa-right-from-bracket" style="color: #ffffff;"></i>
                                </button>
                            </form>
                        </li>
                        <style>
                            .notification-bar {
                                margin-right: 20vh;
                            }
                        </style>
                        <div class="notification-bar shadow-sm p-3 mb-5 bg-white rounded border border-primary float-left">
                            <h4>Notificaciones</h4>
                            <?php
                            $sql = "SELECT * FROM publicidades WHERE comprador IS NOT NULL AND id_usuario = " . $_SESSION['usuario']['id_usuario'] . " AND id_usuario <> comprador";
                            $result = sqlSELECT($sql);

                            $notificaciones = array(); // Array para almacenar las notificaciones
                        
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $sql2 = "SELECT * FROM empresas WHERE id_empresa = " . $row['comprador'];
                                    $result2 = sqlSELECT($sql2);
                                    if ($result2->num_rows > 0) {
                                        if ($row['ocupado'] == 0) {
                                            while ($row2 = $result2->fetch_assoc()) {
                                                $notificacion = "<div class='notification'>Una empresa: " . $row2['nombre'] . " quiere publicitarse en tu ubicacion: " . $row['ubicacion'] . "<form action='usuario.php' method='POST'><input type='hidden' name='id_publicidad' value='" . $row['id_publicidad'] . "'><input type='submit' name='aceptarEmpresa' value='aceptar'></form><form action='usuario.php' method='POST'><input type='hidden' name='id_publicidad' value='" . $row['id_publicidad'] . "'><input type='submit' name='rechazarEmpresa' value='rechazar'></form></div>";
                                                array_push($notificaciones, $notificacion);
                                            }
                                        } else {
                                            while ($row2 = $result2->fetch_assoc()) {
                                                $notificacion = "<div class='notification'>Una empresa: " . $row2['nombre'] . " quiere publicitarse en tu ubicacion: " . $row['ubicacion'] . " <span style='color: red;'>Aceptada.</span></div>";
                                                array_push($notificaciones, $notificacion);
                                            }
                                        }
                                    }
                                }
                            }

                            $sql = "SELECT * FROM publicidades WHERE comprador IS NULL AND id_usuario = " . $_SESSION['usuario']['id_usuario'] . " AND ocupado = 1";
                            $result = sqlSELECT($sql);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $notificacion = "<div class='notification'>La empresa compradora de la ubicacion: " . $row['ubicacion'] . " ha decidido rechazar la publicidad. <form action='usuario.php' method='POST'><input type='hidden' name='id_publicidad' value='" . $row['id_publicidad'] . "'><input type='submit' name='rechazarEmpresa' value='Visto'></form></div>";
                                    array_push($notificaciones, $notificacion);
                                }
                            }

                            $sql = "SELECT * FROM publicidades WHERE comprador = " . $_SESSION['usuario']['id_usuario'] . " AND ocupado = 1 AND estado = 1 AND caducidad_compra IS NULL";
                            $result = sqlSELECT($sql);

                            if ($result->num_rows > 0) {
                                $notificacion = "<div class='notification'>Un usuario ha aceptado la solicitud de compra. Revisa el carrito de la sección empresa para poder ver la ubicación y confirmar la compra.</div>";
                                array_push($notificaciones, $notificacion);
                            }

                            if (isset($_POST['aceptarEmpresa'])) {
                                $id_publicidad = $_POST['id_publicidad'];
                                $sql = "UPDATE publicidades SET ocupado = 1 WHERE id_publicidad = " . $id_publicidad;
                                if (sqlUPDATE($sql)) {
                                    echo "<script>window.location.href = 'usuario.php?usuarioMapa';</script>";
                                    exit();
                                }
                            }
                            if (isset($_POST['rechazarEmpresa'])) {
                                $id_publicidad = $_POST['id_publicidad'];
                                $sql = "UPDATE publicidades SET ocupado = 0, comprador = NULL WHERE id_publicidad = " . $id_publicidad;
                                if (sqlUPDATE($sql)) {
                                    echo "<script>window.location.href = 'usuario.php?usuarioMapa';</script>";
                                    exit();
                                }
                            }


                            $sql = "SELECT * FROM publicidades WHERE comprador = " . $_SESSION['usuario']['id_usuario'] . " AND ocupado = 1 AND estado = 1";
                            $result = sqlSELECT($sql);
                            $fechaBD = null;

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    // Obtener la fecha de la base de datos
                                    $fechaBD = $row['caducidad_compra'];


                                    // Obtener la fecha actual
                                    $fechaActual = date("Y-m-d");
                                    // Verificar si la fecha es anterior a hoy
                                    if ($fechaBD !== null) {
                                        if ($fechaActual < $fechaBD) {

                                        } elseif ($fechaBD == $fechaActual) {
                                            $notificacion = "<div class='notification'>Hoy es el último día de su publicidad alquilada: " . $row['ubicacion'] . " " . $row['codigo_postal'] . "</div>";
                                            array_push($notificaciones, $notificacion);
                                        } else {
                                            $notificacion = "<div class='notification'>La fecha ya no es válida.</div>";
                                            array_push($notificaciones, $notificacion);
                                            $sql = "UPDATE publicidades SET ocupado = 0, comprador = NULL, revision = NULL, caducidad_compra = NULL";
                                            sqlUPDATE($sql);
                                        }
                                    } else {
                                        
                                    }
                                }
                            } 

                            $sql = "SELECT * FROM alertas WHERE usuario = " . $_SESSION['usuario']['id_usuario'] . " AND estado = 0";
                            $result = sqlSELECT($sql);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $notificacion = "<h5 style='color: red;'>Notificación del administrador: " . $row['titulo'] . "</h5><p>". $row['descripcion'] ."</p>";
                                    array_push($notificaciones, $notificacion);
                                }
                            }

                            // Imprimir las notificaciones en orden inverso
                            for ($i = count($notificaciones) - 1; $i >= 0; $i--) {
                                echo $notificaciones[$i];
                            }




                            


                            ?>
                            <div class="notification">¡Bienvenido a DisplayAds</div>
                        </div>


                        <script>
                            const bell = document.querySelector('.bell'); const notificationBar = document.querySelector('.notification-bar');
                            bell.addEventListener('click', () => { notificationBar.style.display = notificationBar.style.display === 'none' ? 'block' : 'none'; });
                        </script>

                    </ul>
                </nav>
            </div>
        </header>
    </div>
    <?php
}
?>

<?php
// Link de head
function head_info()
{
    ?>
    <meta charset="UTF-8">
    <meta name="description"
        content="Aplicación web que facilita a las empresas publicitarse a un precio asequible y a las personas ganar dinero por hacer de publicitadores">
    <meta name="keywords"
        content="anuncios, empresas, pequeñas empresas, recompensas, publicidad, publicitadores, crecer, carteles, ubicaciones">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="../js/funciones.js"></script>
    <link href="../css/style.css" rel="stylesheet" type="text/css">
    <link href="../css/menu_general.css" rel="stylesheet" type="text/css">
    <title>DisplayAds</title>
    <?php
}
?>

<?php
// Link de head index
function head_index()
{
    ?>
    <meta charset="UTF-8">
    <meta name="description"
        content="Aplicación web que facilita a las empresas publicitarse a un precio asequible y a las personas ganar dinero por hacer de publicitadores">
    <meta name="keywords"
        content="anuncios, empresas, pequeñas empresas, recompensas, publicidad, publicitadores, crecer, carteles, ubicaciones">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="css/menu_index.css" rel="stylesheet" type="text/css">
    <link href="css/principal.css" rel="stylesheet" type="text/css">
    <link href="css/style.css" rel="stylesheet" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <title>DisplayAds</title>
    <?php
}
?>
