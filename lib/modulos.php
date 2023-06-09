<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
// Menú para el index.php.
function menu_index()
{
    ?>
    <div class="separar">

        <header>
            <a class="logo" href="index.php"><img src="img/icono/logo.png" class="logo" alt="Logo"
                    style="width: 70px; height: 70px;"></a>
            <ul class="cont-ul">
                <a href="index.php">
                    <li>Inicio</li>
                </a>
                <li class="nosotros">
                    Nosotros
                    <ul class="ul-second">
                        <a href="nosotros.php?informacion">
                            <li>Informacion</li>
                        </a>
                        <a href="nosotros.php?politicasPrivacidad">
                            <li>Política de Privacidad</li>
                        </a>
                    </ul>
                </li>
                <a href="login.php">
                    <li><i class="fa-solid fa-right-to-bracket"></i></li>
                </a>
            </ul>
        </header>
    </div>
    <?php
}
?>

<?php
// Menú para el principal.php.
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
                        // Se valida qué roles tiene el usuario logueado.
                        if (validarUsuario($_SESSION['usuario']['id_usuario'])) {
                            ?>
                            <li><a href="usuario.php?usuarioMapa">Usuario</a>
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
                            <li><a href="vigilante.php?misiones">Vigilante</a>
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
                            <li><a href="empresa.php?empresaMapa">Empresa</a>
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
                                    <li><a href="administrador.php?administradorSoportes">Soportes</a></li>

                                </ul>
                            </li>
                            <?php
                        }
                        ?>
                        <li><a href="cuenta.php?cuentaInformacion">Cuenta</a>
                            <ul>
                                <li><a href="cuenta.php?cuentaInformacion">Informacion</a></li>
                                <li><a href="cuenta.php?publicidades">Publicidades</a></li>
                                <li><a href="cuenta.php?usuarioSoportes">Soportes</a></li>
                                <li><a href="cuenta.php?suscripcion">Suscripción</a></li>

                            </ul>
                        </li>
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

                            // Array para almacenar las notificaciones.
                            $notificaciones = array(); 

                            // Creación de la consulta para la notificación de una empresa compradora de la ubicación de un usuario.
                            $sql = "SELECT * FROM publicidades WHERE comprador IS NOT NULL AND id_usuario = " . $_SESSION['usuario']['id_usuario'] . " AND id_usuario <> comprador AND caducidad_compra IS NULL";
                            $result = sqlSELECT($sql);

                            // Si la consulta da resultado entonces entra.
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {

                                    // Se crea una segunda consulta.
                                    $sql2 = "SELECT * FROM empresas WHERE id_empresa = " . $row['comprador'];
                                    $result2 = sqlSELECT($sql2);
                                    if ($result2->num_rows > 0) {
                                        if ($row['ocupado'] == 0) {
                                            while ($row2 = $result2->fetch_assoc()) {
                                                // Se le notifica al usuario qué empresa quiere comprar qué ubicación.
                                                $notificacion = "<div class='notification'>Una empresa: " . $row2['nombre'] . " quiere publicitarse en tu ubicacion: " . $row['ubicacion'] . "<form action='usuario.php' method='POST'><input type='hidden' name='id_publicidad' value='" . $row['id_publicidad'] . "'><input type='submit' name='aceptarEmpresa' value='aceptar'></form><form action='usuario.php' method='POST'><input type='hidden' name='id_publicidad' value='" . $row['id_publicidad'] . "'><input type='submit' name='rechazarEmpresa' value='rechazar'></form></div>";
                                                array_push($notificaciones, $notificacion);
                                            }
                                        } else {
                                            while ($row2 = $result2->fetch_assoc()) {
                                                // Si acepta a la empresa entonces pondrá "aceptada" hasta que la empresa compre la ubicación.
                                                $notificacion = "<div class='notification'>Una empresa: " . $row2['nombre'] . " quiere publicitarse en tu ubicacion: " . $row['ubicacion'] . " <span style='color: red;'>Aceptada.</span></div>";
                                                // La $notificacion se mete en el array de notificaciones.
                                                array_push($notificaciones, $notificacion);
                                            }
                                        }
                                    }
                                }
                            }

                            // Se crea una consulta para saber si los productos pedidos por un usuario han sido enviados y revisados por un administrador.
                            $sql = "SELECT * FROM productos as p, lineas_pedidos as lp, pedidos as ped WHERE ped.id_usuario = " . $_SESSION['usuario']['id_usuario'] . " AND ped.revision = 1 AND lp.id_producto = p.id_producto AND lp.id_pedido = ped.id_pedido AND lp.id_publicidad IS NULL";
                            // Se crea un result para la consulta.
                            $result = sqlSELECT($sql);
                            if ($result->num_rows > 0) {
                                // Se crea la notificación.
                                $notificacion = "<div class='notification'>";
                                while ($row = $result->fetch_assoc()) {
                                    // Dentro del div de notificación se indica que los productos han sido enviados.
                                    $notificacion .= "Los productos han sido enviados: " . $row['nombre'] . "<form action='usuario.php' method='POST'><input type='hidden' name='id_pedido' value='" . $row['id_pedido'] . "'>";
                                }
                                // Y finalmente se cierra el div tras crear un input para que el usuario indique que ha visto la notificación y esta desaparezca.
                                $notificacion .= "<br><input type='submit' name='vistoPedido' value='Visto'></form></div>";
                                // Y se mete la notificación en el array.
                                array_push($notificaciones, $notificacion);
                            }

                            // Se crea una consulta para que la empresa sepa que el cartel ha sido enviado al usuario.
                            $sql = "SELECT * FROM publicidades WHERE comprador = " . $_SESSION['usuario']['id_usuario'] . " AND revision = 5";


                            $result = sqlSELECT($sql);
                            if ($result->num_rows > 0) {
                                
                                
                                while ($row = $result->fetch_assoc()) {
                                    $notificacion = "<div class='notification'>El cartel ha sido enviado a la ubicación " . $row['ubicacion']  ." y recibido.<form action='usuario.php' method='POST'><input type='hidden' name='id_publicidad' value='" . $row['id_publicidad'] . "'><br><input type='submit' name='vistoPedidoEmpresa' value='Visto'></form></div>";
                                    array_push($notificaciones, $notificacion);
                                }
                                
                                
                                    
                                
                                
                                
                            }

                            // Se crea una consulta para que el usuario sepa que el cartel ha sido enviado y cuando lo tenga le da a visto para que la notificación anterior llegue a la empresa.
                            $sql = "SELECT p.id_publicidad AS id_publicidad, p.ubicacion AS ubicacion, ped.id_pedido AS id_pedido 
                            FROM publicidades AS p, lineas_pedidos AS lp, pedidos AS ped 
                            WHERE p.id_usuario = " . $_SESSION['usuario']['id_usuario'] . "
                            AND p.revision = 4
                            AND lp.id_publicidad = p.id_publicidad 
                            AND lp.id_pedido = ped.id_pedido 
                            AND lp.id_producto IS NULL";
                            $result = sqlSELECT($sql);
                            if ($result->num_rows > 0) {
                                
                                
                                while ($row = $result->fetch_assoc()) {
                                    $notificacion = "<div class='notification'>El cartel ha sido enviado a la ubicación " . $row['ubicacion']  ."<form action='usuario.php' method='POST'><input type='hidden' name='id_publicidad' value='" . $row['id_publicidad'] . "'><br><p>advertencia: esperar a que lo recibas antes de darle a visto.</p><input type='submit' name='vistoPedidoUsuario' value='Visto'></form></div>";
                                    array_push($notificaciones, $notificacion);
                                }
                                
                                
                                
                                
                            }


                            // Se crea una consulta para que el usuario sepa si la solicitud de su piso ha sido aceptada, rechazada o sigue en espera.
                            $sql = "SELECT * FROM publicidades WHERE id_usuario = " . $_SESSION['usuario']['id_usuario'];
                            $result = sqlSELECT($sql);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    if (is_null($row['revision'])) {
                                    }
                                    elseif ($row['revision'] == 0) {
                                        
                                        $notificacion = "<div class='notification'>Tu solicitud de publicar la ubicación del piso ha sido rechazada: ". $row['ubicacion'] . "<form action='usuario.php' method='POST'><input type='hidden' name='id_publicidad' value='" . $row['id_publicidad'] . "'><input type='submit' name='vistoPiso' value='Visto'></form></div>";
                                        array_push($notificaciones, $notificacion);
                                        
                                    } elseif ($row['revision'] == 1) {
                                        
                                        
                                        
                                        $notificacion = "<div class='notification'>Tu solicitud de publicar la ubicación del piso ha sido aceptada: ". $row['ubicacion'] . "<form action='usuario.php' method='POST'><input type='hidden' name='id_publicidad' value='" . $row['id_publicidad'] . "'><input type='submit' name='vistoPisoAceptado' value='Visto'></form></div>";
                                        array_push($notificaciones, $notificacion);
                                    } elseif ($row['revision'] == 2) {
                                        
                                        
                                        $notificacion = "<div class='notification'>Tu solicitud de publicar la ubicación del piso está a la espera: ". $row['ubicacion'];
                                        array_push($notificaciones, $notificacion);
                                    }
                                    
                                }
                            }


                            // Consulta para que el usuario sepa si la empresa tras tener la publicidad en el carrito la ha eliminado o no.
                            $sql = "SELECT * FROM publicidades WHERE comprador IS NULL AND id_usuario = " . $_SESSION['usuario']['id_usuario'] . " AND ocupado = 1";
                            $result = sqlSELECT($sql);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $notificacion = "<div class='notification'>La empresa compradora de la ubicacion: " . $row['ubicacion'] . " ha decidido rechazar la publicidad. <form action='usuario.php' method='POST'><input type='hidden' name='id_publicidad' value='" . $row['id_publicidad'] . "'><input type='submit' name='rechazarEmpresa' value='Visto'></form></div>";
                                    array_push($notificaciones, $notificacion);
                                }
                            }

                            // Consulta para que la empresa sepa si el usuario de una publicidad ha aceptado o rechazado su propuesta de alquilarla.
                            $sql = "SELECT * FROM publicidades WHERE comprador = " . $_SESSION['usuario']['id_usuario'] . " AND ocupado = 1 AND estado = 1 AND caducidad_compra IS NULL";
                            $result = sqlSELECT($sql);

                            if ($result->num_rows > 0) {
                                $notificacion = "<div class='notification'>Un usuario ha aceptado la solicitud de compra. Revisa el carrito de la sección empresa para poder ver la ubicación y confirmar la compra.</div>";
                                array_push($notificaciones, $notificacion);
                            }

                            // Consulta para que la empresa sepa cuál es su último día de una publicidad y el penúltimo.
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
                                            $notificacion = "<div class='notification'>La fecha de su publicidad alquilada ha terminado: " . $row['ubicacion'] . " " . $row['codigo_postal'] . "</div>";
                                            array_push($notificaciones, $notificacion);
                                            $sql = "UPDATE publicidades SET ocupado = 0, comprador = NULL, revision = NULL, caducidad_compra = NULL WHERE id_publicidad = " . $row['id_publicidad'];
                                            sqlUPDATE($sql);
                                        }
                                    }
                                }
                            }

                            // Consulta para que la empresa sepa cuando es el último día de su publicidad y cuando su alquiler ha caducado completamente.
                            $sql = "SELECT * FROM publicidades as p, empresas as e WHERE p.id_usuario = " . $_SESSION['usuario']['id_usuario'] . " AND p.ocupado = 1 AND p.estado = 1 AND p.comprador = e.id_empresa";
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
                                            $notificacion = "<div class='notification'>Hoy es el último día de en el que tendrás que tener puesto el cartel en la ubicacion: " . $row['ubicacion'] . " " . $row['codigo_postal'] . " para la empresa ". $row['nombre'] ."</div>";
                                            array_push($notificaciones, $notificacion);
                                        } else {
                                            $notificacion = "<div class='notification'>Hoy deberás quitar el cartel en la ubicacion: " . $row['ubicacion'] . " " . $row['codigo_postal'] . " para la empresa ". $row['nombre'] . "</div>";
                                            array_push($notificaciones, $notificacion);
                                            $sql = "UPDATE publicidades SET ocupado = 0, comprador = NULL, revision = NULL, caducidad_compra = NULL";
                                            sqlUPDATE($sql);
                                        }
                                    }
                                }
                            }


                            // Consulta para que los usuarios reciban notificaciones enviadas por los administradores.
                            $sql = "SELECT * FROM alertas WHERE usuario = " . $_SESSION['usuario']['id_usuario'] . " AND estado = 0";
                            $result = sqlSELECT($sql);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $notificacion = "<div class='notification'><h5 style='color: red;'>Notificación del administrador: " . $row['titulo'] . "</h5><p>" . $row['descripcion'] . "</p><form action='usuario.php' method='post'><input type='hidden' name='id_alerta' value='" . $row['id_alerta'] . "'><input type='submit' value='Visto' name='vistoAdmin'></form></div>";
                                    array_push($notificaciones, $notificacion);
                                }
                            }



                            // Que los usuarios VIP sepan cuando es su último día y cuando ya se ha caducado.
                            $sql = "SELECT * FROM usuarios WHERE id_usuario = " . $_SESSION['usuario']['id_usuario'];
                            $result = sqlSELECT($sql);
                            $fechaBD = null;

                            if ($result->num_rows > 0) {

                                while ($row = $result->fetch_assoc()) {
                                    // Obtener la fecha de la base de datos
                                    $fechaBD = $row['VIP'];


                                    // Obtener la fecha actual
                                    $fechaActual = date("Y-m-d");
                                    // Verificar si la fecha es anterior a hoy
                                    if ($fechaBD !== null) {
                                        if ($fechaActual < $fechaBD) {

                                        } elseif ($fechaBD == $fechaActual) {
                                            $notificacion = "<div class='notification'>Hoy es el último día de su suscripción VIP.</div>";
                                            array_push($notificaciones, $notificacion);
                                        } else {
                                            $notificacion = "<div class='notification'>Su suscripción VIP ha caducado.</div><form action='usuario.php'><input type='submit' value='Visto' name='vistoVIP'></form>";
                                            array_push($notificaciones, $notificacion);


                                            $sqlRol = "DELETE FROM usuarios_roles WHERE id_usuario = " . $_SESSION['usuario']['id_usuario'] . " AND id_rol = 4";
                                            sqlDELETE($sqlRol);
                                        }
                                    }
                                }
                            }



                            // Si se ha pulsado vistoPedido se setea la revisión a 0.
                            if(isset($_REQUEST['vistoPedido']))
                            {
                                // 
                                $id_pedido = $_POST['id_pedido'];
                                $sql = "UPDATE pedidos SET revision = 0 WHERE id_usuario = " . $_SESSION['usuario']['id_usuario'] . " AND id_pedido = " . $id_pedido;
                                sqlUPDATE($sql);
                                echo "<script>window.location.href = 'principal.php';</script>";
                                exit();
                            }

                            if(isset($_REQUEST['vistoAdmin']))
                            {
                                 
                                $id_alerta = $_POST['id_alerta'];
                                $sql = "UPDATE alertas SET estado = 1 WHERE usuario = " . $_SESSION['usuario']['id_usuario'] . " AND id_alerta = " . $id_alerta;
                                sqlUPDATE($sql);
                                echo "<script>window.location.href = 'principal.php';</script>";
                                exit();
                            }

                            // Si el administrador ha marcado como visto el pedido del usuario.
                            if(isset($_REQUEST['vistoPedidoUsuario']))
                            {
                                $id_publicidad = $_POST['id_publicidad'];
                                $sql = "UPDATE publicidades AS pub
                                        SET pub.revision = 5
                                        WHERE pub.id_usuario = " . $_SESSION['usuario']['id_usuario'] . " AND pub.id_publicidad = " . $id_publicidad;

                                sqlUPDATE($sql);
                                echo "<script>window.location.href = 'principal.php';</script>";
                                exit();
                            }

                            // Si el administrador indica como visto el pedido de la empresa se setea la revisión a 2.
                            if(isset($_REQUEST['vistoPedidoEmpresa']))
                            {
                                $id_publicidad = $_POST['id_publicidad'];
                                $sql = "UPDATE publicidades SET revision = 6 WHERE comprador = " . $_SESSION['usuario']['id_usuario'] . " AND id_publicidad = " . $id_publicidad;
                                sqlUPDATE($sql);
                                echo "<script>window.location.href = 'principal.php';</script>";
                                exit();
                            }

                            // Si el usuario ha visto el piso rechazado.
                            if (isset($_REQUEST['vistoPiso'])) {
                                $id_publicidad = $_POST['id_publicidad'];
                                $sql = "DELETE FROM publicidades WHERE id_usuario = " . $_SESSION['usuario']['id_usuario'] . " AND id_publicidad = " . $id_publicidad;

                                sqlDELETE($sql);
                                echo "<script>window.location.href = 'principal.php';</script>";
                                exit();
                            }

                            // Si el usuario ha visto el piso aceptado.
                            if (isset($_REQUEST['vistoPisoAceptado'])) {
                                $id_publicidad = $_POST['id_publicidad'];
                                $sql = "UPDATE publicidades SET revision = 3 WHERE id_usuario = " . $_SESSION['usuario']['id_usuario'] . " AND id_publicidad = " . $id_publicidad;

                                sqlDELETE($sql);
                                echo "<script>window.location.href = 'principal.php';</script>";
                                exit();
                            }

                            // Si el usuario le da al botón de vistoVIP entonces se sete su fecha de VIP en null.
                            if (isset($_REQUEST['vistoVIP'])) {
                                $sql = "UPDATE usuarios SET VIP = NULL WHERE id_usuario = " . $_SESSION['usuario']['id_usuario'];
                                sqlUPDATE($sql);
                                echo "<script>window.location.href = 'usuario.php?usuarioMapa';</script>";
                                exit();
                            }

                            // Si el usuario acepta a la empresa que se quiere publicitar en su publicidad.
                            if (isset($_POST['aceptarEmpresa'])) {
                                $id_publicidad = $_POST['id_publicidad'];
                                $sql = "UPDATE publicidades SET ocupado = 1 WHERE id_publicidad = " . $id_publicidad;
                                if (sqlUPDATE($sql)) {
                                    echo "<script>window.location.href = 'usuario.php?usuarioMapa';</script>";
                                    exit();
                                }
                            }

                            // Si el usuario rechaza a la empresa que se quiere publicitar en su publicidad.
                            if (isset($_POST['rechazarEmpresa'])) {
                                $id_publicidad = $_POST['id_publicidad'];
                                $sql = "UPDATE publicidades SET ocupado = 0, comprador = NULL WHERE id_publicidad = " . $id_publicidad;
                                if (sqlUPDATE($sql)) {
                                    echo "<script>window.location.href = 'usuario.php?usuarioMapa';</script>";
                                    exit();
                                }
                            }

                            // Imprimir las notificaciones en orden inverso.
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