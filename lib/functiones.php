<?php
session_start();

// conexion BD
function conectar() {
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
function validarUser($username, $email, $telefono, $password, $password2) {

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
        $errors[]="El nombre de usuario ya existe!";
    }

    // Verificar si el correo electrónico ya existe en la base de datos
    $sql = "SELECT * FROM usuarios WHERE email='$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $errors[]="El correo electrónico ya existe!";
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
            echo "Hay un fallo ".$e;
        } finally {
            mysqli_close($conn);
        }
    }
}


// Para hacer autenticacion login del usuario
function autenticarUser($email, $password) {
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
        try{
            //conexion
            $conn = conectar();
            $sql = "SELECT * FROM usuarios WHERE email='$email'";
            $result = mysqli_query($conn,$sql);

            if ($row = mysqli_fetch_assoc($result)) {
                $password_hash = $row['password'];
        
                // Verifica la contraseña
                if (password_verify($password, $password_hash)) {
                    header("location:pag/principal.php");
                    exit; // Es importante poner exit después del header para asegurarnos de que el script no siga ejecutándose
                } else {
                    echo "Password Incorrecto!";
                }
            } else {
                echo "El usuario no existe!";
            }
            
        } catch (Exception $e) {
            echo "Hay un fallo ".$e;
        } finally {
            mysqli_close($conn);
        }
    }
}


function guardarMarcador($lat, $lng, $titulo, $texto) {


    
    try{
        // Aquí puedes manejar la imagen guardándola en el servidor y obteniendo su ruta

        // Establecer la conexión con la base de datos
        $conn = conectar();

        // Preparar y ejecutar la consulta para insertar los datos en la base de datos
        $sql = "INSERT INTO marcadores (lat, lng, titulo, texto) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ddss", $lat, $lng, $titulo, $texto);
        $stmt->execute();

        // Verificar si la inserción fue exitosa
        if ($stmt->affected_rows > 0) {
            echo "El marcador se guardó correctamente.";
        } else {
            echo "Error al guardar el marcador.";
        }
    } catch (Exception $e) {
        echo "Hay un fallo ".$e;
    } finally {
        // Cerrar la conexión y liberar recursos
        $stmt->close();
        mysqli_close($conn);
    }
}















?>

