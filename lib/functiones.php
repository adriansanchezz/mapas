<?php
// Abrir session
session_start();

// conexion BD 
function conectar()
{
    //Dato 
    $host = "localhost";
    $basededatos = "mapa_promocion";
    $usuariodb = "root";
    $clavedb = "";

    // crear connection
    $conn = mysqli_connect($host, $usuariodb, $clavedb, $basededatos);

    if (!$conn) {
        throw new Exception('Error de conexión: ' . mysqli_connect_error());
    } else {
        return $conn;
    }
}


// Para hacer Registro del usuario
function registrarUser($username, $email, $telefono, $password, $password2)
{

    // Verificar si hay campo vacio.
    if (empty($username) || empty($email) || empty($telefono) || empty($password) || empty($password2)) {
        $errors[] = "Hay campos vacios.";
    }

    // Verificar el usuario tenga al menos 4 caracteres.
    if (strlen($username) < 4) {
        $errors[] = "El nombre de usuario debe tener al menos 4 caracteres.";
    }

    // Verificar que la contraseña tenga al menos 8 caracteres.
    if (strlen($password) < 8) {
        $errors[] = "La contraseña debe tener al menos 8 caracteres.";
    }

    // Verificar si la contraseña y la repeticon es igual o no
    if ($password != $password2) {
        $errors[] = "Las contraseñas no son iguales.";
    }

    // Verificar si el nombre de usuario ya existe en la base de datos
    $sql = "SELECT * FROM usuarios WHERE nombre='$username'";
    if (mysqli_num_rows(sqlSELECT($sql)) > 0) {
        $errors[] = "El nombre de usuario ya existe!";
    }

    // Verificar si el correo electrónico ya existe en la base de datos
    $sql = "SELECT * FROM usuarios WHERE email='$email'";
    if (mysqli_num_rows(sqlSELECT($sql)) > 0) {
        $errors[] = "El correo electrónico ya existe!";
    }

    //Lanza alerta si hay erro en el array de $errors
    if (!empty($errors)) {
        echo "<ul name='l2'>";
        foreach ($errors as $error) {
            echo '<li>' . $error . '</li>';
        }
        echo '</ul>';
        return false;
    } else {
        // Si no hay errores, los datos del formulario son válidos.
        // Guardar los datos del usuario en la base de datos.
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO usuarios (nombre, email, password, telefono)"
            . "VALUES ('$username', '$email', '$password_hash', '$telefono')";

        if (sqlINSERT($sql)) {
            echo "Su usuario ha sido registrado correctamente!";
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
        echo "<ul name='l2'>";
        foreach ($errors as $error) {
            echo '<li>' . $error . '</li>';
        }
        echo '</ul>';
        return false;
    } else {
        // Si no hay errores, los datos del formulario son válidos.

        //Consulta
        $sql = "SELECT * FROM usuarios WHERE email='$email'";

        //Verificar si el usuario existe o no
        if ($row = mysqli_fetch_assoc(sqlSELECT($sql))) {
            $password_hash = $row['password'];

            // Verifica la contraseña
            if (password_verify($password, $password_hash)) {
                $_SESSION['usuario'] = $row;
                $_SESSION['validarEstado'] = true;
                header("location:pag/principal.php");
                exit; // Es importante poner exit después del header para asegurarnos de que el script no siga ejecutándose
            } else {
                echo "Password Incorrecto!";
            }
        } else {
            echo "El usuario no existe!";
        }
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
        $result = mysqli_query($conn, $sql);

        // Devolver el resultado
        return $result;

    } catch (Exception $e) {
        echo "Hay un fallo en la consulta: " . $e;
    } finally {
        // Cerrar la conexión y liberar recursos
        mysqli_close($conn);
    }
}

// Sirve para las actualizacones de UPDATE
// Duelve true o false
function sqlUPDATE($sql)
{
    try {
        //conexion
        $conn = conectar();

        if (mysqli_query($conn, $sql)) {
            // Si la actualización fue exitosa, retorna un true
            return true;
        } else {
            // Si la actualización falló, retorna un false
            return false;
        }
    } catch (Exception $e) {
        // Lanzar erro si falla
        echo "Error al actualizar registro: " . $e;
    } finally {
        // Cerrar la conexión a la base de datos
        mysqli_close($conn);
    }
}

// Sirve para las actualizaciones de INSET
// Duelve true o false
function sqlINSERT($sql)
{
    try {
        //conexion
        $conn = conectar();

        if (mysqli_query($conn, $sql)) {
            // Si la insercion fue exitosa, retorna un true
            return true;
        } else {
            // Si la insercion falló, retorna un false
            return false;
        }
    } catch (Exception $e) {
        // Lanzar erro si falla
        echo "Error al insertar registro: " . $e;
    } finally {
        // Cerrar la conexión a la base de datos
        mysqli_close($conn);
    }
}

// Velificar si existe el rol de Usuario
function validarUsuario($id_user)
{
    //Consultas
    $sql = "SELECT * FROM usuarios_roles WHERE id_usuario='$id_user' AND id_rol=(SELECT id_rol FROM roles WHERE nombre='Usuario')";

    // Devuelve si true o false, segun si tiene o no tiene este rol
    if (mysqli_fetch_assoc(sqlSELECT($sql))) {
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
    if (mysqli_fetch_assoc(sqlSELECT($sql))) {
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
    if (mysqli_fetch_assoc(sqlSELECT($sql))) {
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
    if (mysqli_fetch_assoc(sqlSELECT($sql))) {
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
    if (mysqli_fetch_assoc(sqlSELECT($sql))) {
        return true;
    } else {
        return false;
    }
}

// Listar usuario y sus datos con rol, para todo los usuraio que existe
function listarRoles($id_user)
{
    // Consulta
    $sql = "SELECT u.nombre, u.email, r.nombre as nombre_rol
        FROM usuarios u
        LEFT JOIN usuarios_roles ur ON u.id_usuario = ur.id_usuario
        LEFT JOIN roles r ON ur.id_rol = r.id_rol";

    // Guardar el resulatdo devulto
    $result = sqlSELECT($sql);

    // Comprobar si existe el compo de la consulta, y listar los datos
    while ($row = mysqli_fetch_assoc($result)) {
        echo $row['nombre'] . "    -------    " . $row['email'] . "    -------    " . $row['nombre_rol'] . "<br>";
    }
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
            </tr>
        </thead>
        <tbody>
    ";

    while ($row = mysqli_fetch_assoc($result)) {
        $id_publicidad = $row['id_publicidad'];
        $tipo = $row['tipo'];
        $provincia = $row['provincia'];
        $ubicacion = $row['ubicacion'];
        $codigo_postal = $row['codigo_postal'];
        $descripcion = $row['descripcion'];
        $precio = $row['precio'];
        $estado = $row['estado'];

        echo"
        <tr>
            <td>$id_publicidad</td>
            <td>$tipo</td>
            <td>$provincia, $codigo_postal, $ubicacion</td>
            <td>$descripcion</td>
            <td>$precio</td>
            <td>$estado</td>
        </tr>
        ";
    }
    echo "
        </tbody>
    </table>
    ";
}

// Agregar rol de Usuario
function agregarUsuario($id_user)
{

}

// Agregar rol de Admin
function agregarAdmin($id_user)
{

}

// Agregar rol de Empresa
function agregarEmpresa($id_user)
{

}

// Agregar rol de VIP
function agregarVIP($id_user)
{

}

// Agregar rol de Vigilante
function agregarVigilante($id_user)
{

}

// Eliminar rol de Usuario
function eliminarUsuario($id_user)
{

}

// Eliminar rol de Admin
function eliminarAdmin($id_user)
{

}

// Eliminar rol de Empresa
function eliminarEmpresa($id_user)
{

}

// Eliminar rol de VIP
function eliminarVIP($id_user)
{

}

// Eliminar rol de Vigilante
function eliminarVigilante($id_user)
{

}

// Actualizar dato de nombre, un parametro de nuevo nombre y id usuario
function guardarNombre($nombre, $id_user)
{
    //consulta
    $sql = "SELECT COUNT(*) as count FROM usuarios WHERE nombre = '$nombre'";

    // Meter el resultado devulto para un valor;
    $datos = mysqli_fetch_assoc(sqlSELECT($sql));

    // Comproba mediante count si existe ya ese nombre o es un nombre nuevo
    if ($datos["count"] > 0) {
        // El nuevo nombre de usuario ya existe en la base de datos, mostrar un mensaje de error
        echo "<div class='flex-grow-1'>El nuevo nombre de usuario ya existe.</div>";
    } else {
        // El nuevo nombre de usuario no existe en la base de datos, actualizar el registro correspondiente
        $sql = "UPDATE usuarios SET nombre='$nombre' WHERE id_usuario = '$id_user'";

        if (sqlUPDATE($sql)) {
            echo "<div class='flex-grow-1'>El nombre de usuario se ha modificado correctamente.</div>";
        } else {
            // Se produjo un error al actualizar el registro, mostrar un mensaje de error
            echo "Error al modificar el nombre de usuario!";
        }
    }
}


// Actualizar dato de correo, y pasa parametro de nuevo correo y correo repetido, mas su id usuario
function guardarCorreo($correo, $correo2, $id_user)
{
    // Verificar si los datos repite o no
    if (repetirValor($correo, $correo2)) {
        // Consulta
        $sql = "SELECT COUNT(*) as count FROM usuarios WHERE email = '$correo'";

        // Meter el resultado devulto para un valor;
        $datos = mysqli_fetch_assoc(sqlSELECT($sql));

        // Comproba mediante count si existe ya ese correo o no
        if ($datos["count"] > 0) {
            // El nuevo correo de usuario ya existe en la base de datos, mostrar un mensaje de error
            echo "<div class='flex-grow-1'>El nuevo correo de usuario ya existe.</div>";
        } else {
            // El nuevo correo de usuario no existe en la base de datos, actualizar el registro correspondiente
            $sql = "UPDATE usuarios SET email='$correo' WHERE id_usuario = '$id_user'";
            if (sqlUPDATE($sql)) {
                echo "<div class='flex-grow-1'>El correo de usuario se ha modificado correctamente.</div>";
            } else {
                // Se produjo un error al actualizar el registro, mostrar un mensaje de error
                echo "Error al modificar el correo de usuario!";
            }
        }
    } else {
        echo "<div class='flex-grow-1'>Los correo no son iguales!</div>";
    }
}


// Actualizar dato de password, pasa parametro de nuevo pass y pass repetido, mas su id usuario
function guardarPassword($pass, $pass2, $id_user)
{
    // Verificar si los datos repite o no
    if (repetirValor($pass, $pass2)) {

        //Hashar la contra
        $password_hash = password_hash($pass, PASSWORD_DEFAULT);

        //consulta
        $sql = "UPDATE usuarios SET password='$password_hash' WHERE id_usuario = '$id_user'";

        if (sqlUPDATE($sql)) {
            echo "<div class='flex-grow-1'>La contraseña de usuario se ha modificado correctamente.</div>";
        } else {
            // Se produjo un error al actualizar el registro, mostrar un mensaje de error
            echo "Error al modificar la contraseña de usuario!";
        }
    } else {
        echo "<div class='flex-grow-1'>Las contraseñas no son iguales!</div>";
    }
}

// Pasar los valores para comprovar si repeti o no
function repetirValor($valo, $valo2)
{
    // Verificar si los correo es igual o no
    if ($valo == $valo2) {
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
    $datos = mysqli_fetch_assoc(sqlSELECT($sql));

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
        sqlSELECT($sql);
    }
}



function verVisitaTotal()
{
    // Consulta
    $sql = "SELECT numero FROM pagina_info WHERE titulo = 'visitas'";

    // Meter el resultado devulto para un valor;
    $datos = mysqli_fetch_assoc(sqlSELECT($sql));
    echo $datos["numero"];
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


        // Establecer la conexión con la base de datos.
        $conn = conectar();

        // Realización de la consulta a la base de datos a través de un bind param.
        $sql = "INSERT INTO publicidades (latitud, longitud, provincia, ciudad, ubicacion, codigo_postal, descripcion, precio, id_tipo_publicidad, id_usuario) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        // Se comprueba que la consulta sea adecuada.
        $stmt = $conn->prepare($sql);
        // Y mediante un bind_param se establecen los valores.
        $stmt->bind_param('ddssssssii', $lat, $lng, $provincia, $ciudad, $ubicacion, $codigo_postal, $descripcion, $precio, $tipoPublicidad, $idUser);
        // Se ejecuta la consulta.
        $stmt->execute();

        // Verificar si la inserción fue exitosa.
        if ($stmt->affected_rows > 0) {
            // Si lo fue, se muestra que el marcador fue guardado de manera correcta y un botón para confirmar.
            echo "El marcador se guardó correctamente.";
            echo "<form action='usuario.php'><button type='submit'>Aceptar</button></form>";
        } else {
            // Si no lo fue, se indica un error.
            echo "Error al guardar el marcador.";
        }

        // Se cierra la conexión sql.
        mysqli_close($conn);
    }
}



function debug_to_console($data) {
    $output = $data;
    if (is_array($output))
        $output = implode(',', $output);

    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}

?>

