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
    $conn = conectar();
    $sql = "SELECT * FROM usuarios WHERE nombre='$username'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $errors[] = "El nombre de usuario ya existe!";
    }

    // Verificar si el correo electrónico ya existe en la base de datos
    $sql = "SELECT * FROM usuarios WHERE email='$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $errors[] = "El correo electrónico ya existe!";
    }
    mysqli_close($conn);

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

        try {
            //conexion
            $conn = conectar();
            $sql = "INSERT INTO usuarios (nombre, email, password, telefono)"
                . "VALUES ('$username', '$email', '$password_hash', '$telefono')";

            //Ejecutar inset
            mysqli_query($conn, $sql);
            echo "Su usuario ha sido registrado correctamente!";

            return true;
        } catch (Exception $e) {
            echo "Hay un fallo " . $e;
        } finally {
            // Cerrar la conexión y liberar recursos
            mysqli_close($conn);
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
        try {
            //conexion
            $conn = conectar();
            //consulta
            $sql = "SELECT * FROM usuarios WHERE email='$email'";
            //Ejecutar
            $result = mysqli_query($conn, $sql);

            if ($row = mysqli_fetch_assoc($result)) {
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

        } catch (Exception $e) {
            echo "Hay un fallo " . $e;
        } finally {
            // Cerrar la conexión y liberar recursos
            mysqli_close($conn);
        }
    }
}
































function validarUsuario($id_user)
{
    try {
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
}


function validarAdmin($id_user)
{
    try {
        //conexion
        $conn = conectar();
        //consulta
        $sql = "SELECT * FROM usuarios_roles WHERE id_usuario='$id_user' AND id_rol=(SELECT id_rol FROM roles WHERE nombre='Admin')";
        //Ejecutar
        $result = mysqli_query($conn, $sql);

        //Comprobar si existe el compo de la consulta
        if (mysqli_fetch_assoc($result)) {
            echo "Admin SI";
            return true;
        } else {
            echo "Admin NO";
            return false;
        }

    } catch (Exception $e) {
        echo "Hay un fallo " . $e;
    } finally {
        // Cerrar la conexión y liberar recursos
        mysqli_close($conn);
    }
}

function validarEmpresa($id_user)
{
    try {
        //conexion
        $conn = conectar();
        //consulta
        $sql = "SELECT * FROM usuarios_roles WHERE id_usuario='$id_user' AND id_rol=(SELECT id_rol FROM roles WHERE nombre='Empresa')";
        //Ejecutar
        $result = mysqli_query($conn, $sql);

        //Comprobar si existe el compo de la consulta
        if (mysqli_fetch_assoc($result)) {
            echo "Empresa SI";
            return true;
        } else {
            echo "Empresa NO";
            return false;
        }

    } catch (Exception $e) {
        echo "Hay un fallo " . $e;
    } finally {
        // Cerrar la conexión y liberar recursos
        mysqli_close($conn);
    }
}

function validarVIP($id_user)
{
    try {
        //conexion
        $conn = conectar();
        //consulta
        $sql = "SELECT * FROM usuarios_roles WHERE id_usuario='$id_user' AND id_rol=(SELECT id_rol FROM roles WHERE nombre='VIP')";
        //Ejecutar
        $result = mysqli_query($conn, $sql);

        //Comprobar si existe el compo de la consulta
        if (mysqli_fetch_assoc($result)) {
            echo "VIP SI";
            return true;
        } else {
            echo "VIP NO";
            return false;
        }

    } catch (Exception $e) {
        echo "Hay un fallo " . $e;
    } finally {
        // Cerrar la conexión y liberar recursos
        mysqli_close($conn);
    }
}

function validarVigilante($id_user)
{
    try {
        //conexion
        $conn = conectar();
        //consulta
        $sql = "SELECT * FROM usuarios_roles WHERE id_usuario='$id_user' AND id_rol=(SELECT id_rol FROM roles WHERE nombre='Vigilante')";
        //Ejecutar
        $result = mysqli_query($conn, $sql);

        //Comprobar si existe el compo de la consulta
        if (mysqli_fetch_assoc($result)) {
            echo "Vigilante SI";
            return true;
        } else {
            echo "Vigilante NO";
            return false;
        }

    } catch (Exception $e) {
        echo "Hay un fallo " . $e;
    } finally {
        // Cerrar la conexión y liberar recursos
        mysqli_close($conn);
    }
}

function listarRol($id_user)
{
    try {
        //conexion
        $conn = conectar();
        //consulta
        $sql = "SELECT u.nombre, u.email, r.nombre as nombre_rol
                FROM usuarios u
                LEFT JOIN usuarios_roles ur ON u.id_usuario = ur.id_usuario
                LEFT JOIN roles r ON ur.id_rol = r.id_rol";
        //Ejecutar
        $result = mysqli_query($conn, $sql);

        //Comprobar si existe el compo de la consulta
        while ($row = mysqli_fetch_assoc($result)) {
            echo $row['nombre'] ."    -------    ". $row['email'] ."    -------    ". $row['nombre_rol'] . "<br>";
        }
    } catch (Exception $e) {
        echo "Hay un fallo " . $e;
    } finally {
        // Cerrar la conexión y liberar recursos
        mysqli_close($conn);
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
    try {
        //conexion
        $conn = conectar();

        //consulta
        $sql = "SELECT COUNT(*) as count FROM usuarios WHERE nombre = '$nombre'";

        //Ejecutar
        $result = mysqli_query($conn, $sql);

        $datos = mysqli_fetch_assoc($result);

        if ($datos["count"] > 0) {
            // El nuevo nombre de usuario ya existe en la base de datos, mostrar un mensaje de error
            echo "<div class='flex-grow-1'>El nuevo nombre de usuario ya existe.</div>";
        } else {
            // El nuevo nombre de usuario no existe en la base de datos, actualizar el registro correspondiente
            $sql = "UPDATE usuarios SET nombre='$nombre' WHERE id_usuario = '$id_user'";
            if (mysqli_query($conn, $sql)) {
                echo "<div class='flex-grow-1'>El nombre de usuario se ha modificado correctamente.</div>";
            } else {
                // Se produjo un error al actualizar el registro, mostrar un mensaje de error
                echo "Error al modificar el nombre de usuario: " . mysqli_error($conn);
            }
        }
    } catch (Exception $e) {
        echo "Hay un fallo " . $e;
    } finally {
        // Cerrar la conexión y liberar recursos
        mysqli_close($conn);
    }
}


function guardarCorreo($correo, $correo2, $id_user)
{
    if (repetirCorreo($correo, $correo2)) {
        try {
            //conexion
            $conn = conectar();

            //consulta
            $sql = "SELECT COUNT(*) as count FROM usuarios WHERE email = '$correo'";

            //Ejecutar
            $result = mysqli_query($conn, $sql);

            $datos = mysqli_fetch_assoc($result);

            if ($datos["count"] > 0) {
                // El nuevo correo de usuario ya existe en la base de datos, mostrar un mensaje de error
                echo "<div class='flex-grow-1'>El nuevo correo de usuario ya existe.</div>";
            } else {
                // El nuevo correo de usuario no existe en la base de datos, actualizar el registro correspondiente
                $sql = "UPDATE usuarios SET email='$correo' WHERE id_usuario = '$id_user'";
                if (mysqli_query($conn, $sql)) {
                    echo "<div class='flex-grow-1'>El correo de usuario se ha modificado correctamente.</div>";
                } else {
                    // Se produjo un error al actualizar el registro, mostrar un mensaje de error
                    echo "Error al modificar el correo de usuario: " . mysqli_error($conn);
                }
            }
        } catch (Exception $e) {
            echo "Hay un fallo " . $e;
        } finally {
            // Cerrar la conexión y liberar recursos
            mysqli_close($conn);
        }
    } else {
        echo "<div class='flex-grow-1'>Los correo no son iguales!</div>";
    }
}

function guardarPassword($pass, $pass2, $id_user)
{
    if (repetirPassword($pass, $pass2)) {
        try {
            //conexion
            $conn = conectar();

            //Hashar la contra
            $password_hash = password_hash($pass, PASSWORD_DEFAULT);

            //consulta
            $sql = "UPDATE usuarios SET password='$password_hash' WHERE id_usuario = '$id_user'";

            if (mysqli_query($conn, $sql)) {
                echo "<div class='flex-grow-1'>La contraseña de usuario se ha modificado correctamente.</div>";
            } else {
                // Se produjo un error al actualizar el registro, mostrar un mensaje de error
                echo "Error al modificar la contraseña de usuario: " . mysqli_error($conn);
            }

        } catch (Exception $e) {
            echo "Hay un fallo " . $e;
        } finally {
            // Cerrar la conexión y liberar recursos
            mysqli_close($conn);
        }
    } else {
        echo "<div class='flex-grow-1'>Las contraseñas no son iguales!</div>";
    }
}

// MODIFICAR
function repetirCorreo($correo, $correo2)
{
    // Verificar si los correo es igual o no
    if ($correo == $correo2) {
        return true;
    } else {
        return false;
    }
}

function repetirPassword($pass, $pass2)
{
    // Verificar si los correo es igual o no
    if ($pass == $pass2) {
        return true;
    } else {
        return false;
    }
}















// sin hacer

if (isset($_POST['compraUbicacion']) && $_POST['compraUbicacion'] == '1') {
    $lat = $_POST['lat'];
    $lng = $_POST['lng'];
    $titulo = $_POST['titulo'];
    $texto = $_POST['texto'];

    // Mostrar los datos de la ubicación seleccionada
    echo "<p><strong>Ubicación:</strong> $titulo</p>";
    echo "<p><strong>Descripción:</strong> $texto</p>";
    echo "<p><strong>Latitud:</strong> $lat</p>";
    echo "<p><strong>Longitud:</strong> $lng</p>";

    ?>
    <p>¿Confirmar compra?</p>
    <form method="post" action="confirmarCompra.php">
        <input type="hidden" name="compraUbicacion" value="true">
        <input type="hidden" name="lat" value="<?php echo $lat; ?>">
        <input type="hidden" name="lng" value="<?php echo $lng; ?>">
        <input type="hidden" name="titulo" value="<?php echo $titulo; ?>">
        <input type="hidden" name="texto" value="<?php echo $texto; ?>">
        <input type="submit" value="Confirmar">
    </form>
    <?php
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
    
    
        // Establecer la conexión con la base de datos
        $conn = conectar();
    
        $sql = "INSERT INTO propiedades (latitud, longitud, provincia, ciudad, ubicacion, codigo_postal, descripcion, id_tipo_propiedad, id_usuario) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 4)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ddsssssi", $lat, $lng, $provincia, $ciudad, $ubicacion, $codigo_postal, $descripcion, $tipoPropiedad);
        $stmt->execute();
    
        // Verificar si la inserción fue exitosa
        if ($stmt->affected_rows > 0) {
            echo "El marcador se guardó correctamente.";
            echo "<form action='usuario.php'><button type='submit'>Aceptar</button></form>";
        } else {
            echo "Error al guardar el marcador.";
        }
    
        // Cerrar la conexión y liberar recursos
        //$stmt->close();
        //cerrarConexion($conn);
    }
}














?>