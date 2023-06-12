<?php
require_once '../lib/funciones.php';
if (isset($_GET['subirMision'])) {
    // Verifica si el usuario ha iniciado sesión y obtén su ID de usuario
    if (isset($_SESSION['usuario']['id_usuario'])) {
        $id_usuario = $_SESSION['usuario']['id_usuario'];
        // Obtén los datos enviados por AJAX
        $descripcion = $_POST['descripcion'];
        $id_publicidad = $_POST['id_publicidad'];
        $id_tipo = 1;
        $recompensa = 10;
        $aceptacion = 0;
        $estado = 0;
        $conn = conectar();
        $sql = "SELECT * FROM misiones WHERE id_publicidad='$id_publicidad' AND aceptacion = 0 AND id_usuario=". $_SESSION['usuario']['id_usuario'] .";";
        $result = sqlSELECT($sql);
        if ($result->num_rows > 0) {
            echo "true";
        } else {
            if ($conn->connect_error) {
                die("Error de conexión: " . $conn->connect_error);
            }
            $stmt2 = $conn->prepare("INSERT INTO misiones (descripcion, recompensa, aceptacion, estado, id_tipo_mision, id_usuario, id_publicidad) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt2->bind_param("siiiiii", $descripcion, $recompensa, $aceptacion, $estado, $id_tipo, $id_usuario, $id_publicidad);
            if ($stmt2->execute()) {
                echo "false";
            } else {
                // Ocurrió un error al insertar los datos en la base de datos
                echo "Error al guardar los datos en la base de datos: " . $stmt2->error;
            } 
        }
    } else {
        echo "El usuario no ha iniciado sesión.";
    }
}
?>