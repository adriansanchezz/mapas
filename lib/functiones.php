<?php

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
function validarUser($username, $correo, $telefono, $password, $password2) {

    // Verificar si hay campo vacio.
    if (empty($username) || empty($correo) || empty($username) || empty($telefono) || empty($password) || empty($password2)) {
        $errors[] = "Hay campos vacios.";
    }

    // Verificar el usuario tenga al menos 4 caracteres.
    if (strlen($username) < 4) {
        $errors[] = "El nombre de usuario debe tener al menos 4 caracteres.";
    }

    // Verificar que la contraseña tenga al menos 8 caracteres.
    if (strlen($password2) < 8) {
        $errors[] = "La contraseña debe tener al menos 8 caracteres.";
    }

    if ($password != $password2) {
        $errors[] = "Las contraseñas no son iguales.";
    }

    if (!empty($errors)) {
        echo "<ul name='l2'>";
        foreach ($errors as $error) {
            echo '<li>' . $error . '</li>';
        }
        echo '</ul>';
    } else {
        // Si no hay errores, los datos del formulario son válidos.
        // Guardar los datos del usuario en la base de datos.
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        try {
            $conn = conectar();
            $sql = "INSERT INTO usuarios (nombre, email, password, telefono, provincia, ciudad, ubicacion, codigo_postal) "
                    . "VALUES ('$username', '$correo', '$password_hash', '$telefono', '1', '1', '1', '1')";
            //Ejecutar inset
            if (mysqli_query($conn, $sql)) {
                echo "Se ha completado el registro correctamente.";
            } else {
                echo "Sadwwadad.";
                throw new Exception('Error al ejecutar: ' . mysqli_error($conexion));
            }
            mysqli_close($conn);
            return true;
        } catch (Exception $e) {
            echo "Fallo " . $e;
            return false;
        }
    }
}
?>

