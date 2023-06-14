<?php
// Abrir session.
session_start();

// Función para conectarse a la base de datos.
function conectar()
{
    // Datos para la base de datos.
    $host = "localhost";
    $basededatos = "mapa_promocion";
    $usuariodb = "root";
    $clavedb = "";

    // Base de datos para el despliegue.
    // $basededatos = "id20705558_mapa_promocion";
    // $usuariodb = "id20705558_root";
    // $clavedb = "({wQ>S|E[~75z*eA";

    // Creando la conexión.
    $conn = mysqli_connect($host, $usuariodb, $clavedb, $basededatos);

    // Si no se crea la conexión se envía un error.
    if (!$conn) {
        throw new Exception('Error de conexión: ' . mysqli_connect_error());
    } else {
        // Si se establece entonces devuelve la conexión.
        return $conn;
    }
}


// Función para registrar un usuario.
function registrarUser($username, $email, $telefono, $password, $password2)
{

    // Verificar si hay campo vacio.
    if (empty($username) || empty($email) || empty($telefono) || empty($password) || empty($password2)) {
        $errors[] = "Hay campos vacios.";
    }

    // Verificar el usuario tenga al menos 4 caracteres.
    if (strlen($username) < 3) {
        $errors[] = "El nombre debe tener al menos 3 caracteres.";
    }

    // Verificar que la contraseña tenga al menos 8 caracteres.
    if (strlen($password) < 6) {
        $errors[] = "La contraseña debe tener al menos 6 caracteres.";
    }

    // Convertir el dominio a minúsculas
    $email = strtolower($email);

    // Patrón de expresión regular para verificar el dominio
    $pattern = '/@(gmail\.com|outlook\.com)$/';

    // Comprobar si el correo electrónico cumple con el patrón
    if (!preg_match($pattern, $email)) {
        $errors[] = "El dominio del correo no es válido.";
    }

    // Verificar si la contraseña y la repeticon es igual o no
    if ($password != $password2) {
        $errors[] = "Las contraseñas no son iguales.";
    }

    // Verificar si el nombre de usuario ya existe en la base de datos
    $sql = "SELECT * FROM usuarios WHERE nombre='$username'";
    if (sqlSELECT($sql)->num_rows > 0) {
        $errors[] = "El nombre de usuario ya existe!";
    }

    // Verificar si el correo electrónico ya existe en la base de datos
    $sql = "SELECT * FROM usuarios WHERE email='$email'";
    if (sqlSELECT($sql)->num_rows > 0) {
        $errors[] = "El correo electrónico ya existe!";
    }

    //Lanza alerta si hay erro en el array de $errors
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo '<div class="alert alert-warning" role="alert">' . $error . '</div>';
        }
        return false;
    } else {
        // Si no hay errores, los datos del formulario son válidos.
        // Guardar los datos del usuario en la base de datos.
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO usuarios (nombre, email, password, telefono)"
            . "VALUES ('$username', '$email', '$password_hash', '$telefono')";

        if (sqlINSERT($sql)) {
            echo "Su usuario ha sido registrado correctamente!";

            $sql = "SELECT id_usuario FROM usuarios WHERE email='$email'";
            $result = sqlSELECT($sql);
            while ($row = $result->fetch_assoc()) {
                agregarRoles($row["id_usuario"], "Usuario");
            }
        }
    }
}

// Para hacer autenticacion login del usuario
function autenticarUser($email, $password)
{
    // Verificar si hay campo vacio.
    if (empty($email) || empty($password)) {
        $errors[] = "Hay campos vacios.";
    }

    //Lanza alerta si hay erro en el array de $errors
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo '<div class="alert alert-warning" role="alert">' . $error . '</div>';
        }
        return false;
    } else {
        // Si no hay errores, los datos del formulario son válidos.

        //Consulta
        $sql = "SELECT * FROM usuarios WHERE email='$email'";

        //Verificar si el usuario existe o no
        if ($row = sqlSELECT($sql)->fetch_assoc()) {
            $password_hash = $row['password'];

            // Verifica la contraseña
            if (password_verify($password, $password_hash)) {
                $_SESSION['usuario'] = $row;
                $_SESSION['validarEstado'] = true;
                // echo "<script>window.location.href = 'pag/principal.php';</script>";
                echo "<script> location.replace('pag/principal.php'); </script>";
                exit; // Es importante poner exit después del header para asegurarnos de que el script no siga ejecutándose
            } else {
                echo '<div class="alert alert-warning" role="alert">Password Incorrecto!</div>';
            }
        } else {
            echo '<div class="alert alert-warning" role="alert">El usuario no existe!</div>';
        }
    }
}

function validarCorreo($email)
{
    // Verificar si el correo electrónico ya existe en la base de datos
    $sql = "SELECT * FROM usuarios WHERE email='$email'";
    if (sqlSELECT($sql)->num_rows > 0) {
        // existe el usuario
        return true;
    } else {
        // no existe el usaruio
        return false;
    }
}

// Función para comprobar la contraseña de un usuario que se loguea.
function comprobarPassword($password, $id_user)
{
    //Consulta para identificar al usuario.
    $sql = "SELECT * FROM usuarios WHERE id_usuario='$id_user'";

    //Verificar si el usuario existe o no
    if ($row = sqlSELECT($sql)->fetch_assoc()) {
        $password_hash = $row['password'];

        // Verifica la contraseña
        if (password_verify($password, $password_hash)) {
            return true;
        } else {
            return false;
        }
    } else {
        echo "El usuario no existe!";
    }
}

// Para realizar consulta, recordar que para while hace falta meterlo a un atributo, luego meter a mysqli_fetch_assoc($result)
// De vuelve el resultado de la consulta
function sqlSELECT($sql)
{
    try {
        //conexion
        $conn = conectar();

        //Ejecutar
        $result = $conn->query($sql);

        // Devolver el resultado
        return $result;

    } catch (Exception $e) {

        echo "Hay un fallo en la consulta: " . $e->getMessage();
    } finally {
        // Cerrar la conexión y liberar recursos
        $conn->close();
    }
}

// Sirve para las actualizacones de UPDATE
// Duelve true o false
function sqlUPDATE($sql)
{
    try {
        // Establecer conexión
        $conn = conectar();

        // Ejecutar consulta de actualización
        if ($conn->query($sql)) {
            // Verificar el número de filas afectadas
            if ($conn->affected_rows > 0) {
                // Si la actualización fue exitosa y se afectaron filas, retorna true
                return true;
            } else {
                // Si la actualización fue exitosa pero no se afectaron filas, retorna false
                return false;
            }
        } else {
            // Si la actualización falló, retorna false
            return false;
        }
    } catch (Exception $e) {
        // Lanzar error si falla
        echo "Error al actualizar registro: " . $e->getMessage();
    } finally {
        // Cerrar la conexión a la base de datos
        $conn->close();
    }
}

// Sirve para las actualizaciones de INSET
// Duelve true o false
function sqlINSERT($sql)
{
    try {
        // Establecer conexión
        $conn = conectar();

        // Ejecutar consulta de inserción
        if ($conn->query($sql)) {
            // Verificar el número de filas afectadas
            if ($conn->affected_rows > 0) {
                // Si la inserción fue exitosa y se afectaron filas, retorna true
                return true;
            } else {
                // Si la inserción fue exitosa pero no se afectaron filas, retorna false
                return false;
            }
        } else {
            // Si la inserción falló, retorna false
            return false;
        }
    } catch (Exception $e) {
        // Lanzar error si falla
        echo "Error al insertar registro: " . $e->getMessage();
    } finally {
        // Cerrar la conexión a la base de datos
        $conn->close();
    }
}


// Sirve para las eliminar una columna
// Duelve true o false
function sqlDELETE($sql)
{
    try {
        // Establecer conexión
        $conn = conectar();

        // Ejecutar consulta de eliminación
        if ($conn->query($sql)) {
            // Verificar el número de filas afectadas
            if ($conn->affected_rows > 0) {
                // Si la eliminación fue exitosa y se afectaron filas, retorna true
                return true;
            } else {
                // Si la eliminación fue exitosa pero no se afectaron filas, retorna false
                return false;
            }
        } else {
            // Si la eliminación falló, retorna false
            return false;
        }
    } catch (Exception $e) {
        // Lanzar error si falla
        echo "Error al eliminar registro: " . $e->getMessage();
    } finally {
        // Cerrar la conexión a la base de datos
        $conn->close();
    }
}



// Velificar si existe el rol de Usuario
function validarUsuario($id_user)
{
    //Consultas
    $sql = "SELECT * FROM usuarios_roles WHERE id_usuario='$id_user' AND id_rol=(SELECT id_rol FROM roles WHERE nombre='Usuario')";

    // Devuelve si true o false, segun si tiene o no tiene este rol
    if (sqlSELECT($sql)->num_rows > 0) {
        return true;
    } else {
        return false;
    }
}

// Velificar si existe el rol de Admin
function validarAdmin($id_user)
{
    // Consulta
    $sql = "SELECT * FROM usuarios_roles WHERE id_usuario='$id_user' AND id_rol=(SELECT id_rol FROM roles WHERE nombre='Admin')";

    // Devuelve si true o false, según si tiene o no tiene este rol
    if (sqlSELECT($sql)->num_rows > 0) {
        return true;
    } else {
        return false;
    }
}

// Velificar si existe el rol de Empresa
function validarEmpresa($id_user)
{
    //Consulta
    $sql = "SELECT * FROM usuarios_roles WHERE id_usuario='$id_user' AND id_rol=(SELECT id_rol FROM roles WHERE nombre='Empresa')";

    // Devuelve si true o false, según si tiene o no tiene este rol
    if (sqlSELECT($sql)->num_rows > 0) {
        return true;
    } else {
        return false;
    }
}

// Velificar si existe el rol de VIP
function validarVIP($id_user)
{
    // Consulta
    $sql = "SELECT * FROM usuarios_roles WHERE id_usuario='$id_user' AND id_rol=(SELECT id_rol FROM roles WHERE nombre='VIP')";

    // Devuelve si true o false, según si tiene o no tiene este rol
    if (sqlSELECT($sql)->num_rows > 0) {
        return true;
    } else {
        return false;
    }
}

// Velificar si existe el rol de Vigilante
function validarVigilante($id_user)
{
    // Consulta
    $sql = "SELECT * FROM usuarios_roles WHERE id_usuario='$id_user' AND id_rol=(SELECT id_rol FROM roles WHERE nombre='Vigilante')";

    // Devuelve si true o false, según si tiene o no tiene este rol
    if (sqlSELECT($sql)->num_rows > 0) {
        return true;
    } else {
        return false;
    }
}

// Velificar bloqueo de un usuario.
function validarBloqueo($id_user)
{
    // Consulta
    $sql = "SELECT * FROM usuarios WHERE id_usuario='$id_user' AND fecha_bloqueo IS NULL";

    // Devuelve si true o false
    if (sqlSELECT($sql)->num_rows > 0) {
        return true;
    } else {
        return false;
    }
}

// Ver las ubicaciones que han sido compradas.
function ubicacionesCompradas()
{
    $conn = conectar();
    $sql = "SELECT * FROM publicidades as p, usuarios as u WHERE p.comprador IS NOT NULL AND p.id_usuario <> p.comprador AND p.ocupado = 1 AND p.id_usuario = u.id_usuario AND caducidad_compra IS NOT NULL";
    $result = sqlSELECT($sql);

    if ($result->num_rows > 0) {
        echo "<br><br><h1>Ubicaciones compradas</h1><br>";
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
            // Select para comprobar la empresa.
            $sql2 = "SELECT * FROM empresas WHERE id_empresa = ?";
            try {
                $stmt2 = $conn->prepare($sql2);
                $stmt2->bind_param("i", $row['comprador']);
                $stmt2->execute();
                $result2 = $stmt2->get_result();

                if ($result2->num_rows > 0) {
                    while ($row2 = $result2->fetch_assoc()) {
                        echo "<tr>
                            <td>" . $row['email'] . "</td>
                            <td>" . $row['ubicacion'] . "</td>
                            <td>" . $row['codigo_postal'] . "</td>
                            <td>" . $row2['nombre'] . "</td>
                            <td>" . $row['precio'] . "</td>
                            <td>" . $row['caducidad_compra'] . "</td>";

                        // Consulta para conseguir los pedidos para ver el precio.
                        $sql3 = "SELECT * FROM pedidos as p, lineas_pedidos as lp WHERE p.id_pedido = lp.id_pedido AND lp.id_publicidad = ?";
                        try {
                            $stmt3 = $conn->prepare($sql3);
                            $stmt3->bind_param("i", $row['id_publicidad']);
                            $stmt3->execute();
                            $result3 = $stmt3->get_result();

                            if ($result3->num_rows > 0) {
                                while ($row3 = $result3->fetch_assoc()) {
                                    echo "<td>" . $row3['precio'] . "</td>";
                                }
                            }
                            $stmt3->close();
                        } catch (Exception $e) {
                            echo "Error al ejecutar la consulta de pedidos: " . $e->getMessage();
                        }

                        echo "<td>";


                        // Si la revisión está en null entonces significa que el administrador no lo ha revisado.
                        if ($row['revision'] == null) {
                            echo "<form action='administrador.php' method='POST'>
                                    <input type='hidden' name='id_publicidad' value='" . $row['id_publicidad'] . "'>
                                    <input type='submit' name='revisarCompraUbicacion' value='Revisado'>
                                    </form>";
                        } else {
                            echo "<p>Enviado.</p>";
                        }


                        echo "</td>
                            </tr>";
                    }
                }
                $stmt2->close();
            } catch (Exception $e) {
                echo "Error al ejecutar la consulta de empresas: " . $e->getMessage();
            }
        }
        echo "</table>";
    }
}


// Ver los productos comprados o reclamados mediante recompensas.
function productosComprados()
{
    $conn = conectar();
    $sql4 = "SELECT * FROM pedidos WHERE fecha_fin IS NOT NULL AND ubicacion IS NOT NULL";

    $result = sqlSELECT($sql4);

    if ($result->num_rows > 0) {
        echo "<br><br><h1>Productos comprados</h1><br>";
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
            $sql5 = "SELECT * FROM usuarios WHERE id_usuario = ?";
            try {
                $stmt2 = $conn->prepare($sql5);
                $stmt2->bind_param("i", $row3['id_usuario']);
                $stmt2->execute();
                $result3 = $stmt2->get_result();

                if ($result3->num_rows > 0) {
                    while ($row4 = $result3->fetch_assoc()) {
                        echo "<td>" . $row4['email'] . "</td>";
                    }
                }
                $stmt2->close();
            } catch (Exception $e) {
                echo "Error al ejecutar la consulta de usuarios: " . $e->getMessage();
            }

            $sql6 = "SELECT * FROM lineas_pedidos as lp, productos as p WHERE lp.id_producto = p.id_producto AND lp.id_pedido = ?";
            try {
                $stmt3 = $conn->prepare($sql6);
                $stmt3->bind_param("i", $row3['id_pedido']);
                $stmt3->execute();
                $result4 = $stmt3->get_result();

                if ($result4->num_rows > 0) {
                    echo "<td>";
                    while ($row5 = $result4->fetch_assoc()) {
                        echo $row5['nombre'] . "<br>";
                    }
                    echo "</td>";
                }
                $stmt3->close();
            } catch (Exception $e) {
                echo "Error al ejecutar la consulta de lineas_pedidos: " . $e->getMessage();
            }

            if ($row3['importe'] > 0) {
                echo "<td>" . $row3['importe'] . "</td>";
                echo "<td>Dinero</td>";
            } else {
                echo "<td>" . $row3['puntos'] . "</td>";
                echo "<td>Puntos</td>";
            }

            echo "<td>" . $row3['ubicacion'] . "</td>
                <td>" . $row3['fecha_fin'] . "</td>";
            echo "<td>";
            if ($row3['revision'] != NULL) {
                echo "<p>Enviado.</p>";
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
}

// Ver las solicitudes de pisos.
function solicitudesPisos()
{
    $conn = conectar();
    $sql5 = "SELECT * FROM publicidades as p, usuarios as u WHERE p.revision = 2 AND p.id_usuario = u.id_usuario";
    $result = sqlSELECT($sql5);
    if ($result->num_rows > 0) {
        echo "<br><br><h1>Solicitudes de Pisos</h1><br>";
        echo "<table>";
        echo "<tr>
            <th>Usuario</th>
            <th>Certificado</th>
            <th>Aceptación</th>
            </tr>";

        while ($row5 = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row5['email'] . "</td>";


            $sql6 = "SELECT * FROM fotos WHERE id_publicidad = ?";
            try {
                $stmt2 = $conn->prepare($sql6);
                $stmt2->bind_param("i", $row5['id_publicidad']);
                $stmt2->execute();
                $result2 = $stmt2->get_result();
                if ($result2->num_rows > 0) {
                    echo "<td>";
                    while ($row6 = $result2->fetch_assoc()) {
                        $imagen = $row6["foto"];
                        // Mostrar la imagen en la página
                        $mostrarImagen = "<img src='data:image/jpeg;base64," . base64_encode($imagen) . "' alt='Imagen del producto' class='imagen_tabla'>";
                        echo $mostrarImagen;
                    }
                    echo "</td>";
                }
                $stmt2->close();
            } catch (Exception $e) {
                // Manejar la excepción en caso de un error en la consulta
                echo "Error al ejecutar la consulta de fotos: " . $e->getMessage();
            }

            echo "<td><form action='administrador.php' method='POST'>
                <input type='hidden' name='id_publicidad' value='" . $row5['id_publicidad'] . "'>
                <input type='submit' name='aceptarCertificado' value='Aceptar'>
                </form>
                <form action='administrador.php' method='POST'>
                <input type='hidden' name='id_publicidad' value='" . $row5['id_publicidad'] . "'>
                <input type='submit' name='rechazarCertificado' value='Rechazar'>
                </form>
                </td>";
            echo "</tr>";
        }
        echo "</table>";
    }
}


// Lanzar alertas.
function lanzarAlertas()
{

    echo "<br><br><h1>Lanzar alerta</h1><br>";
    echo "<form action='administrador.php' method='POST'>";
    echo "<div class='form-group'>";
    echo "<label for='usuarioSeleccionado'>Selecciona un usuario:</label>";
    echo "<select class='form-control' name='usuarioSeleccionado'>";
    $sql6 = "SELECT * FROM usuarios";

    $result = sqlSELECT($sql6);

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

// Se listan los productos que existen y se da la posibilidad de crear nuevos.
function listarProductos()
{
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
                echo "<img src='data:image/jpeg;base64," . base64_encode($imagen) . "' alt='Imagen del producto' class='imagen_tabla'>";
            } else {
                echo "No se encontró la imagen asociada.";
            }
            echo "</td>";

            $recompensa = ($row["recompensa"] == 1) ? "Recompensa" : "Tienda";

            echo "<td><span id='recompensa'>" . $recompensa . "</span></td>";

            $estado = ($row["estado"] == 1) ? "Activado" : "Desactivado";

            echo "<td><span id='estado'><b>" . $estado . "</b></span></td>";
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
}

// Se listan las misiones que no han sido aceptadas por el admin y le da la posibilidad de aceptarla o rechazarla.
function misionesSinAceptar()
{
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

// Listar usuario y sus datos con rol, para todo los usuraio que existe.
function listarUsuarios($id_user)
{
    // Consulta.
    $sql = "SELECT u.id_usuario , u.nombre, u.email, u.saldo, u.fecha_bloqueo, r.nombre as nombre_rol
        FROM usuarios u
        LEFT JOIN usuarios_roles ur ON u.id_usuario = ur.id_usuario
        LEFT JOIN roles r ON ur.id_rol = r.id_rol
        ORDER BY u.id_usuario";

    // Guardar el resulatdo devulto
    $result = sqlSELECT($sql);

    // Comprobar si existe el compo de la consulta, y listar los datos.
    echo "
        <table border='1' style='border-collapse: collapse;'>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Saldo</th>
                    <th>Rol</th>
                    <th>Bloqueo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
        ";

    while ($row = $result->fetch_assoc()) {
        $id_usuario = $row['id_usuario'];
        $nombre = $row['nombre'];
        $email = $row['email'];
        $saldo = $row['saldo'];
        $fecha_bloqueo = $row['fecha_bloqueo'];
        $nombre_rol = $row['nombre_rol'];

        echo "
            <tr>
            
                <td>$id_usuario</td>
                <td><span class='editableUsuario nombre $ id_usuario' id='nombre' data-usuario-id='$id_usuario'>$nombre</span></td>
                <td>$email</td>
                <td><span class='editableUsuario' id='saldo' data-usuario-id='$id_usuario'>$saldo</span></td>
                <td>$nombre_rol</td>
                <td>$fecha_bloqueo</td>
                <td>
                    <form action='administrador.php?administradorUsuarios' method='POST'>
                        <input type='hidden' name='id_usuario' value='$id_usuario'>
                        <input type='hidden' name='nombre_rol' value='$nombre_rol'>
                        <button type='submit' name='bloquearUsuario' class='btn btn-warning'>Bloquear</button>
                        <button type='submit' name='eliminarRolUsuario' class='btn btn-danger'>Eliminar rol</button>
                    </form>
                    <form action='administrador.php?administradorUsuarios' method='POST'>
                        <input type='hidden' name='id_usuario' value='$id_usuario'>
                        <div class='input-group'>
                            <select name='nombre_rol' class='custom-select'>
                                <option selected disabled>Selecciona un rol</option>
                                <option value='Admin'>Admin</option>
                                <option value='Usuario'>Usuario</option>
                                <option value='Empresa'>Empresa</option>
                                <option value='VIP'>VIP</option>
                                <option value='Vigilante'>Vigilante</option>
                            </select>
                            <div class='input-group-append'>
                            <button type='submit' name='agregarRolUsuario' class='btn btn-success'>Agregar rol</button>
                            </div>
                        </div>
                    </form>
                </td>
            </tr>
            ";
    }
    echo "
            </tbody>
        </table>
        ";
}

// Función para listar usuario y sus datos con rol, para todo los usuraio que existe.
function listarSoporte()
{
    // Consulta.
    $sql = "SELECT * FROM soportes
            WHERE fecha_fin IS NULL";

    // Guardar el resulatdo devulto.
    $result = sqlSELECT($sql);

    // Comprobar si existe el compo de la consulta, y listar los datos.
    echo "
        <table border='1' style='border-collapse: collapse;'>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tipo</th>
                    <th>Usuario</th>
                    <th>Asunto</th>
                    <th>Reportar</th>
                    <th>Mensaje</th>
                    <th>Prueba</th>
                    <th>Respuesta</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
        ";

    while ($row = $result->fetch_assoc()) {
        $id_soporte = $row['id_soporte'];
        $id_tipo_soporte = $row['id_tipo_soporte'];

        $sql = "SELECT nombre FROM tipossoportes
                WHERE id_tipo_soporte = $id_tipo_soporte";
        $resultado2 = sqlSELECT($sql)->fetch_assoc();
        $tipo_soporte = $resultado2["nombre"];

        $sql = "SELECT * FROM soportes";
        $asunto = $row['asunto'];
        $reportar = $row['reportar'];
        $mensaje = $row['mensaje'];

        $sql = "SELECT foto FROM fotos
                WHERE id_soporte = $id_soporte";
        $result3 = sqlSELECT($sql)->fetch_assoc();

        $id_usuario = $row['id_usuario'];
        $sql = "SELECT nombre FROM usuarios
                WHERE id_usuario = $id_usuario";
        $result4 = sqlSELECT($sql)->fetch_assoc();
        $usuario = $result4["nombre"];


        echo "
            <tr>
                <td>$id_soporte</td>
                <td>$tipo_soporte</td>
                <td>$usuario</td>
                <td>$asunto</td>
                <td>$reportar</td>
                <td>$mensaje</td>
                ";

        if ($result3 == null) {
            echo "<td>No hay foto</td>";
        } else {
            $image = $result3["foto"];
            echo "<td><img src='data:image/jpeg;base64," . base64_encode($image) . "' alt='Imagen del soporte' style='max-width: 100px; max-height: 100px;'></td>";
        }

        if ($row['respuesta'] == null) {
            echo "<td>No hay respuesta</td>";
        } else {
            $respuesta = $row['respuesta'];
            echo "<td>$respuesta</td>";
        }

        echo "
                <td>
                    <form action='administrador.php?administradorSoportes' method='POST'>
                        <input type='hidden' name='id_soporte' value='$id_soporte'>
                        <p>Responder: </p>
                        <textarea name='responderSoporte' rows='3' cols='30'></textarea><br><br>
                        <button type='submit' name='revisarSoporte' class='btn btn-primary'>Revisado</button>
                    </form>
                </td>
            </tr>
            ";
    }
    echo "
            </tbody>
        </table>
        ";
}

// Función para listar el soporte de una empresa.
function listarSoporteEmpresa()
{
    // Consulta
    $sql = "SELECT * FROM empresas
    ORDER BY solicitud ASC";

    // Guardar el resulatdo devulto
    $result = sqlSELECT($sql);

    // Comprobar si existe el compo de la consulta, y listar los datos
    echo "
        <table border='1' style='border-collapse: collapse;'>
            <thead>
                <tr>
                    <th>Tipo</th>
                    <th>Usuario</th>
                    <th>CIF</th>
                    <th>Nombre</th>
                    <th>Telefono</th>
                    <th>Correo</th>
                    <th>Direcicion</th>
                    <th>Logo</th>
                    <th>Respuesta</th>
                    <th>Solicitud</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
        ";

    while ($row = $result->fetch_assoc()) {

        $id_tipo_empresa = $row['id_tipo_empresa'];
        $sql = "SELECT nombre FROM tiposempresas
                WHERE id_tipo_empresa = $id_tipo_empresa";
        $resultado2 = sqlSELECT($sql)->fetch_assoc();
        $tipo_empresa = $resultado2["nombre"];

        $id_empresa = $row['id_empresa'];
        $sql = "SELECT nombre FROM usuarios
                WHERE id_usuario = $id_empresa";
        $resultado = sqlSELECT($sql)->fetch_assoc();
        $usuario = $resultado['nombre'];

        $cif = $row["cif"];
        $nombre = $row['nombre'];
        $telefono = $row['telefono'];
        $email = $row['email'];
        $direccion = $row['direccion'];


        if ($row['solicitud'] == null) {
            $solicitud = "Pediente";
        } else if ($row['solicitud'] == 1) {
            $solicitud = "Aprovado";
        } else if ($row['solicitud'] == 0) {
            $solicitud = "Rechazado";
        }


        echo "
            <tr>
                <td>$tipo_empresa</td>
                <td>$usuario ($id_empresa)</td>
                <td>$cif</td>
                <td>$nombre</td>
                <td>$telefono</td>
                <td>$email</td>
                <td>$direccion</td>
                ";
        if ($row['logo'] == null) {
            echo "<td>No hay foto</td>";
        } else {
            $logo = $row['logo'];
            echo "<td><img src='data:image/jpeg;base64," . base64_encode($logo) . "' alt='Imagen del soporte' style='max-width: 100px; max-height: 100px;'></td>";
        }

        if ($row['respuesta'] == null) {
            echo "<td>No hay respuesta</td>";
        } else {
            $respuesta = $row['respuesta'];
            echo "<td>$respuesta</td>";
        }

        echo "                
                <td>$solicitud</td>
                <td>
                    <form action='administrador.php?administradorSoportes' method='POST'>
                        <input type='hidden' name='id_empresa' value='$id_empresa'>
                        <p>Responder: </p>
                        <textarea name='responderSoporteEmpresa' rows='3' cols='30'></textarea><br><br>
                        <button type='submit' name='aprovarEmpresa' class='btn btn-success'>Aprovar</button>
                        <button type='submit' name='rechazarEmpresa' class='btn btn-danger'>Rechazar</button>
                    </form>
                </td>
            </tr>
            ";
    }
    echo "
            </tbody>
        </table>
        ";
}

// Función para que el administrador pueda ver las empresas que han solicitado obtener dicho rol.
function verSolicitudEmpresa($id)
{
    // Consulta
    $sql = "SELECT * FROM empresas WHERE id_empresa = $id";

    // Guardar el resulatdo devulto
    $result = sqlSELECT($sql);

    // Comprobar si existe el compo de la consulta, y listar los datos
    echo "
        <table border='1' style='border-collapse: collapse;'>
            <thead>
                <tr>
                    <th>Tipo</th>
                    <th>CIF</th>
                    <th>Nombre</th>
                    <th>Telefono</th>
                    <th>Correo</th>
                    <th>Direcicion</th>
                    <th>Logo</th>
                    <th>Respuesta</th>
                    <th>Solicitud</th>
                </tr>
            </thead>
            <tbody>
        ";

    while ($row = $result->fetch_assoc()) {

        $id_tipo_empresa = $row['id_tipo_empresa'];
        $sql = "SELECT nombre FROM tipossoportes
                WHERE id_tipo_soporte = $id_tipo_empresa";
        $resultado2 = sqlSELECT($sql)->fetch_assoc();
        $tipo_empresa = $resultado2["nombre"];

        $cif = $row["cif"];
        $nombre = $row['nombre'];
        $telefono = $row['telefono'];
        $email = $row['email'];
        $direccion = $row['direccion'];


        if ($row['solicitud'] == null) {
            $solicitud = "Pediente";
        } else if ($row['solicitud'] == 1) {
            $solicitud = "Aprovado";
        } else if ($row['solicitud'] == 0) {
            $solicitud = "Rechazado";
        }


        echo "
            <tr>
                <td>$tipo_empresa</td>
                <td>$cif</td>
                <td>$nombre</td>
                <td>$telefono</td>
                <td>$email</td>
                <td>$direccion</td>
                ";
        if ($row['logo'] == null) {
            echo "<td>No hay foto</td>";
        } else {
            $logo = $row['logo'];
            echo "<td><img src='data:image/jpeg;base64," . base64_encode($logo) . "' alt='Imagen del soporte' style='max-width: 100px; max-height: 100px;'></td>";
        }

        if ($row['respuesta'] == null) {
            echo "<td>No hay respuesta</td>";
        } else {
            $respuesta = $row['respuesta'];
            echo "<td>$respuesta</td>";
        }

        echo "<td><b>$solicitud</b></td></tr>";
    }
    echo "
            </tbody>
        </table>
        ";
}

// Listar usuario y sus datos con rol, para todo los usuraio que existe
function verSoporte($id)
{
    // Consulta
    $sql = "SELECT * FROM soportes
            WHERE id_usuario = $id
            ORDER BY id_soporte DESC";

    // Guardar el resulatdo devulto
    $result = sqlSELECT($sql);

    // Comprobar si existe el compo de la consulta, y listar los datos
    echo "
        <table border='1' style='border-collapse: collapse;'>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tipo</th>
                    <th>Asunto</th>
                    <th>Reportar</th>
                    <th>Mensaje</th>
                    <th>Prueba</th>
                    <th>Respuesta</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
        ";

    while ($row = $result->fetch_assoc()) {
        $id_soporte = $row['id_soporte'];
        $id_tipo_soporte = $row['id_tipo_soporte'];

        $sql = "SELECT nombre FROM tipossoportes
                WHERE id_tipo_soporte = $id_tipo_soporte";
        $resultado2 = sqlSELECT($sql)->fetch_assoc();
        $tipo_soporte = $resultado2["nombre"];

        $sql = "SELECT * FROM soportes";
        $asunto = $row['asunto'];
        $reportar = $row['reportar'];
        $mensaje = $row['mensaje'];

        $sql = "SELECT foto FROM fotos
                WHERE id_soporte = $id_soporte";
        $result3 = sqlSELECT($sql)->fetch_assoc();


        echo "
            <tr>
                <td>$id_soporte</td>
                <td>$tipo_soporte</td>
                <td>$asunto</td>
                <td>$reportar</td>
                <td>$mensaje</td>
                ";

        if ($result3 == null) {
            echo "<td>No hay foto</td>";
        } else {
            $image = $result3["foto"];
            echo "<td><img src='data:image/jpeg;base64," . base64_encode($image) . "' alt='Imagen del soporte' style='max-width: 100px; max-height: 100px;'></td>";
        }

        if ($row['respuesta'] == null) {
            echo "<td>No hay respuesta</td>";
        } else {
            $respuesta = $row['respuesta'];
            echo "<td>$respuesta</td>";
        }

        if ($row['fecha_fin'] == null) {
            $estado = "Pediente";
        } else {
            $estado = "Cerrado";
        }

        echo "
                <td>$estado</td>
            </tr>
            ";
    }
    echo "
            </tbody>
        </table>
        ";
}

// Listar Propiedad y sus datos, para todo los usuraio que existe
function listarPublicidades($id_user)
{
    // Consulta
    $sql = "SELECT p.id_publicidad, p.provincia, p.ciudad, p.ubicacion, p.codigo_postal, p.descripcion, p.precio, p.estado, t.nombre as tipo
    FROM publicidades p
    LEFT JOIN tipospublicidades t ON p.id_tipo_publicidad = t.id_tipo_publicidad
    WHERE p.id_usuario = '$id_user'";

    // Guardar el resulatdo devulto
    $result = sqlSELECT($sql);

    // Comprobar si existe el compo de la consulta, y listar los datos
    echo "
    <table border='1'  style='border-collapse: collapse;'>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tipo</th>
                <th>Direccion</th>
                <th>Descripcion</th>
                <th>Precio</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
    ";

    while ($row = $result->fetch_assoc()) {
        $id_publicidad = $row['id_publicidad'];
        $tipo = $row['tipo'];
        $provincia = $row['provincia'];
        $ubicacion = $row['ubicacion'];
        $codigo_postal = $row['codigo_postal'];
        $descripcion = $row['descripcion'];
        $precio = $row['precio'];

        $estado = ($row['estado'] == 1) ? "Activado" : "Desactivado";

        echo "
        <tr>
            <td>$id_publicidad</td>
            <td>$tipo</td>
            <td>$provincia, $codigo_postal, $ubicacion</td>
            <td><span class='editablePublicidad' id='descripcion' data-publicidad-id='$id_publicidad'>$descripcion</span></td>
            <td><span class='editablePublicidad' id='precio' data-publicidad-id='$id_publicidad'>$precio</span></td>
            <td>$estado</td>
            <td>
                <form action='cuenta.php?administradorSoportes' method='POST'>
                    <input type='hidden' name='id_publicidad' value='$id_publicidad'>
                    <button type='submit' name='activarPublicidad' class='btn btn-success'>Activar</button>
                    <button type='submit' name='desactivarPublicidad' class='btn btn-secondary'>Desacticar</button>
                    <button type='submit' name='borrarPublicidad' class='btn btn-danger'>Borrar</button>
                </form>
            </td>
        </tr>
        ";
    }
    echo "
        </tbody>
    </table>
    ";
}

// Listar Propiedad y sus datos, para todo los usuraio que existe.
function listarTiposSoportes()
{
    // Consulta
    $sql = 'SELECT * FROM tipossoportes';

    // Guardar el resulatdo devulto
    $result = sqlSELECT($sql);

    // Comprobar si existe el compo de la consulta, y listar los datos
    echo '
    
        <select name="opcion" class="custom-select">
            <option selected disabled>Selecciona un consulta</option>
    ';
    while ($row = $result->fetch_assoc()) {
        $tipo_soporte = $row['nombre'];
        echo "
            <option value='$tipo_soporte'>$tipo_soporte</option>
        ";
    }
    echo '
        </select>
    ';
}

// Función para listar los tipos de empresa a la hora de seleccionarlas cuando realizas el formulario de empresa.
function listarTiposEmpresas()
{
    // Consulta
    $sql = 'SELECT * FROM tiposempresas';

    // Guardar el resulatdo devulto
    $result = sqlSELECT($sql);

    // Comprobar si existe el compo de la consulta, y listar los datos
    echo '
        <select class="form-control" id="tipoEmpresa" name="tipoEmpresa" required>
            <option selected value="" disabled>Selecciona un tipo de empresa</option>
    ';
    while ($row = $result->fetch_assoc()) {
        $tipo_empresa = $row['nombre'];
        $id_empresa = $row['id_tipo_empresa'];

        echo "
            <option value='$id_empresa'>$tipo_empresa</option>
        ";
    }
    echo '
        </select>
    ';
}

// Agregar rol de Usuario.
function agregarRoles($id_user, $nombre_rol)
{
    switch ($nombre_rol) {
        case "Admin":
            $id_rol = 1;
            break;
        case "Usuario":
            $id_rol = 2;
            break;
        case "Empresa":
            $id_rol = 3;
            break;
        case "VIP":
            $id_rol = 4;
            break;
        case "Vigilante":
            $id_rol = 5;
            break;
        default:
            $id_rol = 0;
            break;
    }
    if ($id_rol != 0) {
        // Consulta
        $sql = "INSERT INTO usuarios_roles (id_usuario, id_rol)
        VALUES ($id_user, $id_rol)";

        // Ejecutar la consulta
        sqlINSERT($sql);
    } else {
        return false;
    }

}

// Agregar rol de Admin
function eliminarRoles($id_user, $nombre_rol)
{
    switch ($nombre_rol) {
        case "Admin":
            $id_rol = 1;
            break;
        case "Usuario":
            $id_rol = 2;
            break;
        case "Empresa":
            $id_rol = 3;
            break;
        case "VIP":
            $id_rol = 4;
            break;
        case "Vigilante":
            $id_rol = 5;
            break;
        default:
            $id_rol = 0;
            break;
    }


    if ($id_rol != 0) {
        // Consulta
        $sql = "DELETE FROM usuarios_roles
    WHERE id_rol = $id_rol
    AND id_usuario = $id_user";

        // Ejecutar la consulta
        sqlDELETE($sql);
    } else {
        return false;
    }
}

// Actualizar dato de nombre, un parametro de nuevo nombre y id usuario.
function guardarNombre($nombre, $id_user)
{
    //consulta
    $sql = "SELECT COUNT(*) as count FROM usuarios WHERE nombre = '$nombre'";

    // Meter el resultado devulto para un valor;
    $datos = sqlSELECT($sql)->fetch_assoc();

    // Comproba mediante count si existe ya ese nombre o es un nombre nuevo
    if ($datos["count"] > 0) {
        // El nuevo nombre de usuario ya existe en la base de datos, mostrar un mensaje de error
        echo "<div class='flex-grow-1'><div class='alert alert-danger' role='alert'>El nuevo nombre de usuario ya existe.</div></div>";
    } else {
        // El nuevo nombre de usuario no existe en la base de datos, actualizar el registro correspondiente
        $sql = "UPDATE usuarios SET nombre='$nombre' WHERE id_usuario = '$id_user'";

        if (sqlUPDATE($sql)) {
            echo "<div class='flex-grow-1'><div class='alert alert-success' role='alert'>El nombre de usuario se ha modificado correctamente.</div></div>";
        } else {
            // Se produjo un error al actualizar el registro, mostrar un mensaje de error
            echo "Error al modificar el nombre de usuario!";
        }
    }
}

// Actualizar dato de correo, y pasa parametro de nuevo correo y correo repetido, mas su id usuario.
function guardarCorreo($correo, $id_user)
{
    // Consulta
    $sql = "SELECT COUNT(*) as count FROM usuarios WHERE email = '$correo'";

    // Meter el resultado devulto para un valor;
    $datos = sqlSELECT($sql)->fetch_assoc();

    // Comproba mediante count si existe ya ese correo o no
    if ($datos["count"] > 0) {
        // El nuevo correo de usuario ya existe en la base de datos, mostrar un mensaje de error
        echo "<div class='flex-grow-1'><div class='alert alert-danger' role='alert'>El nuevo correo de usuario ya existe.</div></div>";
    } else {
        // El nuevo correo de usuario no existe en la base de datos, actualizar el registro correspondiente
        $sql = "UPDATE usuarios SET email='$correo' WHERE id_usuario = '$id_user'";
        if (sqlUPDATE($sql)) {
            echo "<div class='flex-grow-1'><div class='alert alert-success' role='alert'>El correo de usuario se ha modificado correctamente.</div></div>";
        } else {
            // Se produjo un error al actualizar el registro, mostrar un mensaje de error
            echo "Error al modificar el correo de usuario!";
        }
    }
}

// Actualizar dato de password, pasa parametro de nuevo pass y pass repetido, mas su id usuario
function guardarPassword($antiguoPass, $pass, $pass2, $id_user)
{
    if (comprobarPassword($antiguoPass, $id_user)) {
        // Verificar si los datos repite o no
        if (repetirValor($pass, $pass2)) {

            //Hashar la contra
            $password_hash = password_hash($pass, PASSWORD_DEFAULT);

            //consulta
            $sql = "UPDATE usuarios SET password='$password_hash' WHERE id_usuario = '$id_user'";

            if (sqlUPDATE($sql)) {
                echo "<div class='flex-grow-1'><div class='alert alert-success' role='alert'>La contraseña de usuario se ha modificado correctamente.</div></div>";
            } else {
                // Se produjo un error al actualizar el registro, mostrar un mensaje de error
                echo "Error al modificar la contraseña de usuario!";
            }
        } else {
            echo "<div class='flex-grow-1'><div class='alert alert-danger' role='alert'>Las contraseñas no son iguales!</div></div>";
        }
    } else {
        echo "<div class='flex-grow-1'><div class='alert alert-danger' role='alert'>Las contraseñas actual es incorrecta!</div></div>";
    }

}

// Pasar los valores para comprovar si repeti o no
function repetirValor($valor, $valor2)
{
    // Verificar si los correo es igual o no
    if ($valor == $valor2) {
        return true;
    } else {
        return false;
    }
}

// Actualizar dato de password, pasa parametro de nuevo pass y pass repetido, mas su id usuario
function sumarVisitaTotal()
{
    // Consulta
    $sql = "SELECT COUNT(*) as count, numero FROM pagina_info WHERE titulo = 'visitas'";

    // Meter el resultado devulto para un valor;
    $datos = sqlSELECT($sql)->fetch_assoc();

    // Comproba mediante count si existe ya ese correo o no
    if ($datos["count"] > 0) {
        // ya existe en la base de datos
        $n = $datos["numero"] + 1;

        //consulta
        $sql = "UPDATE pagina_info SET numero='$n' WHERE titulo = 'visitas'";
        sqlUPDATE($sql);
    } else {
        // no existe en la base de datos
        $sql = "INSERT INTO pagina_info (titulo, numero, descripcion)"
            . "VALUES ('visitas', 1, 'Visitas totales de la pagina')";
        sqlINSERT($sql);
    }
}

// Función que sirve para que el administrador pueda ver las visitas totales a la página.
function verVisitaTotal()
{
    // Consulta
    $sql = "SELECT numero FROM pagina_info WHERE titulo = 'visitas'";

    // Meter el resultado devulto para un valor;
    $datos = sqlSELECT($sql)->fetch_assoc();
    echo $datos["numero"];
}

// Función que sirve para que el administrador pueda ver los pedidos totales realizados en la página.
function verPedidoTotal()
{
    // Consulta
    $sql = "SELECT COUNT(*) as total_pedidos FROM pedidos";

    // Meter el resultado devulto para un valor;
    $datos = sqlSELECT($sql)->fetch_assoc();
    echo $datos["total_pedidos"];
}

function verImporteTotal()
{
    // Consulta
    $sql = "SELECT SUM(importe) as total_importe FROM pedidos";

    // Meter el resultado devulto para un valor;
    $datos = sqlSELECT($sql)->fetch_assoc();
    echo $datos["total_importe"];
}

// Función que sirve para que los usuarios puedan activar sus publicidades.
function activarPublicidad($id_publicidad)
{
    // Consulta
    $sql = "UPDATE publicidades SET estado='1' WHERE id_publicidad = $id_publicidad";

    // Actualizar los datos
    sqlUPDATE($sql);
}

// Función que sirve para que los usuarios puedan desactivar sus publicidades.
function desactivarPublicidad($id_publicidad)
{
    // Consulta
    $sql = "UPDATE publicidades SET estado='0' WHERE id_publicidad = $id_publicidad";

    // Actualizar los datos
    sqlUPDATE($sql);
}

// Función que sirve para que los usuarios puedan borrar sus publicidades.
function borrarPublicidad($id_publicidad)
{
    // Consulta
    $sql = "DELETE FROM publicidades WHERE id_publicidad = $id_publicidad";

    // Actualizar los datos
    sqlDELETE($sql);
}

// Función que sirve para que los administradores puedan activar los productos.
function activarProducto($id_producto)
{
    // Consulta
    $sql = "UPDATE productos SET estado='1' WHERE id_producto = $id_producto";

    // Actualizar los datos
    sqlUPDATE($sql);
}

// Función que sirve para que los administradores puedan desactivar los productos.
function desactivarProducto($id_producto)
{
    // Consulta
    $sql = "UPDATE productos SET estado='0' WHERE id_producto = $id_producto";

    // Actualizar los datos
    sqlUPDATE($sql);
}

// Función que sirve para que los administradores puedan borrar los productos.
function borrarProducto($id_producto)
{
    // Consulta
    $sql = "DELETE FROM productos WHERE id_producto = $id_producto";

    // Actualizar los datos
    sqlDELETE($sql);
}

// Función que sirve para que los administradores puedan bloquear usuarios.
function bloquearUsuario($id_usuario)
{
    // 'Y': Representa el año con cuatro dígitos (ejemplo: 2023).
    // 'y': Representa el año con dos dígitos (ejemplo: 23).
    $fecha_actual = date('Y-m-d');
    // Consulta
    $sql = "UPDATE usuarios SET fecha_bloqueo = '$fecha_actual' WHERE id_usuario  = $id_usuario";

    // Actualizar los datos
    sqlUPDATE($sql);
}

function finalizarSoporte($id, $cometario)
{
    // 'Y': Representa el año con cuatro dígitos (ejemplo: 2023).
    // 'y': Representa el año con dos dígitos (ejemplo: 23).
    $fecha_actual = date('Y-m-d');
    // Consulta
    $sql = "UPDATE soportes SET fecha_fin = '$fecha_actual', respuesta = '$cometario' WHERE id_soporte  = $id";

    // Actualizar los datos
    sqlUPDATE($sql);
}

function aprovarSolicitudEmpresa($id, $cometario)
{
    // Consulta
    $sql = "UPDATE empresas SET solicitud = '1', respuesta = '$cometario' WHERE id_empresa  = $id";
    agregarRoles($id, "Empresa");
    // Actualizar los datos
    sqlUPDATE($sql);
}

function rechazarSolicitudEmpresa($id, $cometario)
{
    // Consulta
    $sql = "UPDATE empresas SET solicitud = '0', respuesta = '$cometario' WHERE id_empresa  = $id";
    eliminarRoles($id, "Empresa");
    // Actualizar los datos
    sqlUPDATE($sql);
}

// Función utilizada para guardar un marcador en el mapa del menú de usuario. 
function guardarMarcador()
{
    // Se verifica que la solicitud sea un método post.
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // Atrapa todos los valores situados en la página mediante post.
        $lat = $_POST['lat'];
        $lng = $_POST['lng'];

        $descripcion = $_POST['descripcion'];
        $provincia = $_POST['provincia'];
        $ciudad = $_POST['ciudad'];
        $ubicacion = $_POST['ubicacion'];
        $codigo_postal = $_POST['codigo_postal'];
        $tipoPublicidad = $_POST['tipoPublicidad'];
        $precio = $_POST['precio'];
        $idUser = $_SESSION['usuario']['id_usuario'];
        $estado = 1;
        $revisionPiso = 2;
        $ocupado = 0;
        // Establecer la conexión con la base de datos.
        $conn = conectar();

        if ($tipoPublicidad == 5) {
            // Realización de la consulta a la base de datos a través de un bind param.
            $sql = "INSERT INTO publicidades (latitud, longitud, provincia, ciudad, ubicacion, codigo_postal, descripcion, precio, ocupado, estado, revision, id_tipo_publicidad, id_usuario) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            // Se comprueba que la consulta sea adecuada.
            $stmt = $conn->prepare($sql);
            // Y mediante un bind_param se establecen los valores.
            $stmt->bind_param('ddsssssdiiiii', $lat, $lng, $provincia, $ciudad, $ubicacion, $codigo_postal, $descripcion, $precio, $ocupado, $estado, $revisionPiso, $tipoPublicidad, $idUser);
            // Se ejecuta la consulta.
            $stmt->execute();
        } else {
            // Realización de la consulta a la base de datos a través de un bind param.
            $sql = "INSERT INTO publicidades (latitud, longitud, provincia, ciudad, ubicacion, codigo_postal, descripcion, precio, ocupado, estado, id_tipo_publicidad, id_usuario) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            // Se comprueba que la consulta sea adecuada.
            $stmt = $conn->prepare($sql);
            // Y mediante un bind_param se establecen los valores.
            $stmt->bind_param('ddsssssdiiii', $lat, $lng, $provincia, $ciudad, $ubicacion, $codigo_postal, $descripcion, $precio, $ocupado, $estado, $tipoPublicidad, $idUser);
            // Se ejecuta la consulta.
            $stmt->execute();
        }

        $id_publicidad = $conn->insert_id;

        // Verificar si la inserción fue exitosa.
        if ($stmt->affected_rows > 0) {

        } else {

            throw new Exception("Error al guardar el marcador.");

        }

        if (isset($_FILES['imagen'])) {

            $conn = conectar();

            // Iterar sobre cada imagen enviada
            for ($i = 0; $i < count($_FILES['imagen']['name']); $i++) {
                if ($_FILES['imagen']['error'][$i] === UPLOAD_ERR_NO_FILE) {
                    throw new Exception("Error: No se seleccionó una imagen.");
                }
                // Verificar si no hubo errores en la subida del archivo
                if ($_FILES['imagen']['error'][$i] === UPLOAD_ERR_OK) {
                    $imagen = $_FILES['imagen']['tmp_name'][$i];
                    $contenidoImagen = file_get_contents($imagen);

                    $sql = "INSERT INTO `fotos`(`foto`, `id_publicidad`) VALUES (?, ?)";

                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("si", $contenidoImagen, $id_publicidad);

                    // Ejecutar la consulta para cada imagen
                    if ($stmt->execute()) {

                    } else {
                        throw new Exception("Error al subir la imagen.");
                    }
                } else {
                    throw new Exception("Error en la subida del archivo.");
                }
            }

            // Redirigir o realizar alguna acción adicional después de procesar todas las imágenes
            echo "<script>window.location.href = 'usuario.php?usuarioMapa=';</script>";
            exit();
        }

        // Se cierra la conexión sql.
        mysqli_close($conn);
    }


}

function generarCode()
{
    // Generar un número aleatorio de 6 dígitos
    $numeroAleatorio = mt_rand(100000, 999999);
    // Generar una cadena de texto aleatoria de 6 caracteres
    $caracteres = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $textoAleatorio = substr(str_shuffle($caracteres), 0, 6);

    $concatenacion = $numeroAleatorio . $textoAleatorio;
    $conjunto = substr(str_shuffle($concatenacion), 0, 6);

    $_SESSION['recuperarCode'] = $conjunto;
}

function obtenerUltimoIdPedido()
{
    $conn = conectar();
    // Realiza la consulta para obtener el último ID de pedido insertado
    $sql = "SELECT MAX(p.id_pedido) AS ultimo_id FROM pedidos as p, lineas_pedidos as lp WHERE p.id_usuario = " . $_SESSION['usuario']['id_usuario'] . " AND p.id_pedido = lp.id_pedido AND id_publicidad IS NULL";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            return $row['ultimo_id'];
        }
    }
    // Si no se pudo obtener el último ID de pedido, retorna un valor predeterminado
    return 0;
}

function obtenerUltimoIdPedidoPublicidad()
{
    $conn = conectar();
    // Realiza la consulta para obtener el último ID de pedido insertado
    $sql = "SELECT MAX(p.id_pedido) AS ultimo_id FROM pedidos as p, lineas_pedidos as lp WHERE p.id_usuario = " . $_SESSION['usuario']['id_usuario'] . " AND p.id_pedido = lp.id_pedido AND id_producto IS NULL";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            return $row['ultimo_id'];
        }
    }
    // Si no se pudo obtener el último ID de pedido, retorna un valor predeterminado
    return 0;
}


function recuperarCuenta($pass, $email)
{
    //Hashar la contra
    $password_hash = password_hash($pass, PASSWORD_DEFAULT);

    //consulta
    $sql = "UPDATE usuarios SET password='$password_hash' WHERE email = '$email'";

    if (sqlUPDATE($sql)) {
        echo "
        <div class='alert alert-success' role='alert'>Se ha modificado correctamente.</div>";
    } else {
        // Se produjo un error al actualizar el registro, mostrar un mensaje de error
        echo "<div class='alert alert-danger' role='alert'>Error al modificar la contraseña!</div>";
    }
}

function procesarPagos()
{
    // Selecciona todos los pedidos que no tienen fecha de finalización
    $sql = "SELECT * FROM pedidos WHERE fecha_fin IS NULL";
    $resultadoResultado = sqlSELECT($sql);

    // Itera a través de los pedidos sin finalizar
    while ($rowPedidos = $resultadoResultado->fetch_assoc()) {
        $id_pedido = $rowPedidos["id_pedido"];

        // Obtiene información de las líneas de pedido y las publicidades asociadas
        $sql = "SELECT p.id_usuario, lp.id_publicidad, lp.precio, p.caducidad_compra FROM lineas_pedidos lp
    INNER JOIN publicidades p ON lp.id_publicidad = p.id_publicidad
    WHERE lp.id_publicidad IS NOT NULL 
    AND p.caducidad_compra IS NOT NULL
    AND p.caducidad_compra > CURDATE()
    AND lp.id_pedido = $id_pedido";
        $resultadoLinea = sqlSELECT($sql);

        // Itera a través de las líneas de pedido
        while ($rowLinea = $resultadoLinea->fetch_assoc()) {
            $id_usuario = $rowLinea["id_usuario"];
            $precio = $rowLinea["precio"];

            // Resta un mes a la fecha de caducidad de la compra
            $caducidad_compra = strtotime($rowLinea['caducidad_compra']);
            $nueva_fecha = date("Y-m-d", strtotime("-1 month", $caducidad_compra));

            // Actualiza la base de datos con la nueva fecha de caducidad
            $sql = "UPDATE publicidades SET caducidad_compra='$nueva_fecha' 
            WHERE id_publicidad=" . $rowLinea['id_publicidad'];
            sqlUPDATE($sql);

            // Obtiene el saldo actual del usuario
            $sql = "SELECT saldo FROM usuarios 
            WHERE id_usuario = $id_usuario";
            $datos = sqlSELECT($sql)->fetch_assoc();
            $saldoFinal = $datos["saldo"] + $precio;

            // Actualiza la base de datos con el nuevo saldo del usuario
            $sql = "UPDATE usuarios SET saldo='$saldoFinal' 
        WHERE id_usuario=" . $id_usuario;
            sqlUPDATE($sql);
        }
    }
}

?>