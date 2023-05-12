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

        if (sqlSELECT($sql)) {
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

        $sql = "SELECT * FROM usuarios WHERE email='$email'";

        if ($row = mysqli_fetch_assoc(sqlSELECT($sql))) {
            $password_hash = $row['password'];

            // Verifica la contraseña
            if (password_verify($password, $password_hash)) {
                $_SESSION['usuario'] = $row;
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
        echo "Error al actualizar registro: " . $e;
    } finally {
        // Cerrar la conexión a la base de datos
        mysqli_close($conn);
    }
}

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
        echo "Error al insertar registro: " . $e;
    } finally {
        // Cerrar la conexión a la base de datos
        mysqli_close($conn);
    }
}








function validarUsuario($id_user)
{
    $sql = "SELECT * FROM usuarios_roles WHERE id_usuario='$id_user' AND id_rol=(SELECT id_rol FROM roles WHERE nombre='Usuario')";

    if (mysqli_fetch_assoc(sqlSELECT($sql))) {
        return true;
    } else {
        return false;
    }
    /*    try {
    //conexion
    $conn = conectar();
    //consulta
    $sql = "SELECT * FROM usuarios_roles WHERE id_usuario='$id_user' AND id_rol=(SELECT id_rol FROM roles WHERE nombre='Usuario')";
    //Ejecutar
    $result = mysqli_query($conn, $sql);
    //Comprobar si existe el compo de la consulta
    if (mysqli_fetch_assoc($result)) {
    echo "Usuario SI";
    return true;
    } else {
    echo "Usuario NO";
    return false;
    }
    } catch (Exception $e) {
    echo "Hay un fallo " . $e;
    } finally {
    // Cerrar la conexión y liberar recursos
    mysqli_close($conn);
    }
    */
}


function validarAdmin($id_user)
{
    //consulta
    $sql = "SELECT * FROM usuarios_roles WHERE id_usuario='$id_user' AND id_rol=(SELECT id_rol FROM roles WHERE nombre='Admin')";

    if (mysqli_fetch_assoc(sqlSELECT($sql))) {
        return true;
    } else {
        return false;
    }
}

function validarEmpresa($id_user)
{
    $sql = "SELECT * FROM usuarios_roles WHERE id_usuario='$id_user' AND id_rol=(SELECT id_rol FROM roles WHERE nombre='Empresa')";

    if (mysqli_fetch_assoc(sqlSELECT($sql))) {
        return true;
    } else {
        return false;
    }
}

function validarVIP($id_user)
{
    $sql = "SELECT * FROM usuarios_roles WHERE id_usuario='$id_user' AND id_rol=(SELECT id_rol FROM roles WHERE nombre='VIP')";

    if (mysqli_fetch_assoc(sqlSELECT($sql))) {
        return true;
    } else {
        return false;
    }
}

function validarVigilante($id_user)
{
    $sql = "SELECT * FROM usuarios_roles WHERE id_usuario='$id_user' AND id_rol=(SELECT id_rol FROM roles WHERE nombre='Vigilante')";

    if (mysqli_fetch_assoc(sqlSELECT($sql))) {
        return true;
    } else {
        return false;
    }
}

function listarRoles($id_user)
{
    $sql = "SELECT u.nombre, u.email, r.nombre as nombre_rol
        FROM usuarios u
        LEFT JOIN usuarios_roles ur ON u.id_usuario = ur.id_usuario
        LEFT JOIN roles r ON ur.id_rol = r.id_rol";

    $result = sqlSELECT($sql);

    while ($row = mysqli_fetch_assoc($result)) {
        echo $row['nombre'] . "    -------    " . $row['email'] . "    -------    " . $row['nombre_rol'] . "<br>";
    }
}

function listarPropiedades($id_user)
{
    $sql = "SELECT p.id_propiedad, p.provincia, p.ciudad, p.ubicacion, p.codigo_postal, p.descripcion, p.precio, t.nombre
    FROM propiedades p
    LEFT JOIN tipospropiedades t ON p.id_tipo_propiedad = t.id_tipo_propiedad 
    WHERE p.id_usuario = '$id_user'";

    $result = sqlSELECT($sql);

    //Comprobar si existe el compo de la consulta
    while ($row = mysqli_fetch_assoc($result)) {
        echo $row['id_propiedad'] . "    -------    " . $row['nombre'] . "    -------    " . $row['provincia'] . "    -------    " . $row['ubicacion'] . "    -------    " . $row['codigo_postal'] . "    -------    " . $row['descripcion'] . "    -------    " . $row['precio'] . "<br>";
    }
}


function agregarUsuario($id_user)
{

}


function agregarAdmin($id_user)
{

}

function agregarEmpresa($id_user)
{

}

function agregarVIP($id_user)
{

}

function agregarVigilante($id_user)
{

}

function eliminarUsuario($id_user)
{

}


function eliminarAdmin($id_user)
{

}

function eliminarEmpresa($id_user)
{

}

function eliminarVIP($id_user)
{

}

function eliminarVigilante($id_user)
{

}

function guardarNombre($nombre, $id_user)
{
    //consulta
    $sql = "SELECT COUNT(*) as count FROM usuarios WHERE nombre = '$nombre'";

    $datos = mysqli_fetch_assoc(sqlSELECT($sql));

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


function guardarCorreo($correo, $correo2, $id_user)
{
    if (repetirValor($correo, $correo2)) {
        $sql = "SELECT COUNT(*) as count FROM usuarios WHERE email = '$correo'";

        $datos = mysqli_fetch_assoc(sqlSELECT($sql));

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


        //     try {
        //         //conexion
        //         $conn = conectar();

        //         //consulta
        //         $sql = "SELECT COUNT(*) as count FROM usuarios WHERE email = '$correo'";

        //         //Ejecutar
        //         $result = mysqli_query($conn, $sql);

        //         $datos = mysqli_fetch_assoc($result);

        //         if ($datos["count"] > 0) {
        //             // El nuevo correo de usuario ya existe en la base de datos, mostrar un mensaje de error
        //             echo "<div class='flex-grow-1'>El nuevo correo de usuario ya existe.</div>";
        //         } else {
        //             // El nuevo correo de usuario no existe en la base de datos, actualizar el registro correspondiente
        //             $sql = "UPDATE usuarios SET email='$correo' WHERE id_usuario = '$id_user'";
        //             if (mysqli_query($conn, $sql)) {
        //                 echo "<div class='flex-grow-1'>El correo de usuario se ha modificado correctamente.</div>";
        //             } else {
        //                 // Se produjo un error al actualizar el registro, mostrar un mensaje de error
        //                 echo "Error al modificar el correo de usuario: " . mysqli_error($conn);
        //             }
        //         }
        //     } catch (Exception $e) {
        //         echo "Hay un fallo " . $e;
        //     } finally {
        //         // Cerrar la conexión y liberar recursos
        //         mysqli_close($conn);
        //     }
    } else {
        echo "<div class='flex-grow-1'>Los correo no son iguales!</div>";
    }
}

function guardarPassword($pass, $pass2, $id_user)
{
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

// MODIFICAR
function repetirValor($valo, $valo2)
{
    // Verificar si los correo es igual o no
    if ($valo == $valo2) {
        return true;
    } else {
        return false;
    }
}




function guardarMarcador()
{

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $lat = $_POST['lat'];
        $lng = $_POST['lng'];

        $descripcion = $_POST['descripcion'];
        $provincia = $_POST['provincia'];
        $ciudad = $_POST['ciudad'];
        $ubicacion = $_POST['ubicacion'];
        $codigo_postal = $_POST['codigo_postal'];
        $tipoPropiedad = $_POST['tipoPropiedad'];
        $precio = $_POST['precio'];
        $idUser = $_SESSION['usuario']['id_usuario'];


        // Establecer la conexión con la base de datos
        $conn = conectar();

        $sql = "INSERT INTO propiedades (latitud, longitud, provincia, ciudad, ubicacion, codigo_postal, descripcion, precio, id_tipo_propiedad, id_usuario) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ddssssssii', $lat, $lng, $provincia, $ciudad, $ubicacion, $codigo_postal, $descripcion, $precio, $tipoPropiedad, $idUser);
        $stmt->execute();

        // Verificar si la inserción fue exitosa
        if ($stmt->affected_rows > 0) {
            echo "El marcador se guardó correctamente.";
            echo "<form action='usuario.php'><button type='submit'>Aceptar</button></form>";
        } else {
            echo "Error al guardar el marcador.";
        }


        mysqli_close($conn);
    }
}



?>