<?php
require_once '../lib/funciones.php';

// Si entra es porque se intenta subir una misión. 
//Es decir, un usuario con el rol "vigilante" ha subido una foto del lugar en el que va a intentar sacarla.
if (isset($_GET['subirMision'])) {
    // Verifica si el usuario ha iniciado sesión y obtén su ID de usuario
    if (isset($_SESSION['usuario']['id_usuario'])) {
        // Obtiene la id de usuario.
        $id_usuario = $_SESSION['usuario']['id_usuario'];
        // Obtiene la descripción de la misión.
        $descripcion = $_POST['descripcion'];
        // Obtiene la id de publicidad.
        $id_publicidad = $_POST['id_publicidad'];
        // El tipo de misión de momento siempre será 1 porque solo hay 1 tipo que es el de sacar foto.
        $id_tipo = 1;
        // La recompensa siempre será 10. 
        $recompensa = 10;
        // La aceptación comenzará en 0, cuando un admin la acepte se pondrá en 1.
        $aceptacion = 0;
        // El estado comienza en 0 porque la misión no está completada.
        $estado = 0;
        $conn = conectar();
        // Consulta para seleccionar las misiones en las que id publicidad es la pasada por solicitud, la aceptación es 0 y la id de usuario es la del usuario logueado.
        $sql = "SELECT * FROM misiones WHERE id_publicidad='$id_publicidad' AND aceptacion = 0 AND id_usuario=". $_SESSION['usuario']['id_usuario'] .";";
        // Se almacena en una variable result.
        $result = sqlSELECT($sql);
        // Si el resultado tiene más de 0 lineas.
        if ($result->num_rows > 0) {
            // Devuelve true.
            echo "true";
        } else {
            // Si sucede un error de conexión se notifica.
            if ($conn->connect_error) {
                die("Error de conexión: " . $conn->connect_error);
            }
            // Se declara un statement con la consulta que se va a preparar. En este caso un insert en la tabla misiones de la base de datos.
            $stmt2 = $conn->prepare("INSERT INTO misiones (descripcion, recompensa, aceptacion, estado, id_tipo_mision, id_usuario, id_publicidad) VALUES (?, ?, ?, ?, ?, ?, ?)");
            // Mediante un bind_param se indican las variables que van en cada interrogación.
            $stmt2->bind_param("siiiiii", $descripcion, $recompensa, $aceptacion, $estado, $id_tipo, $id_usuario, $id_publicidad);
            // Se ejecuta y si lo hace correctamente devuelve false.
            if ($stmt2->execute()) {
                echo "false";
            } else {
                // Ocurrió un error al insertar los datos en la base de datos.
                echo "Error al guardar los datos en la base de datos: " . $stmt2->error;
            } 
        }
    } else {
        echo "El usuario no ha iniciado sesión.";
    }
}
?>