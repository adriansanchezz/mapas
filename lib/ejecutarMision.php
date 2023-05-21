<?php
require_once '../lib/functiones.php';
if (isset($_GET['subirMision'])) {
    // Verifica si el usuario ha iniciado sesión y obtén su ID de usuario
    if (isset($_SESSION['usuario']['id_usuario'])) {
        $id_usuario = $_SESSION['usuario']['id_usuario'];
        // Obtén los datos enviados por AJAX
        $descripcion = $_POST['descripcion'];
        $id_publicidad = $_POST['id_publicidad'];
        $id_tipo = 1;
        $conn = conectar();
        $sql = "SELECT * FROM misiones WHERE id_publicidad='$id_publicidad'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            echo "true";
        } else {
            if ($conn->connect_error) {
                die("Error de conexión: " . $conn->connect_error);
            }
            $stmt2 = $conn->prepare("INSERT INTO misiones (descripcion, id_tipo_mision, id_usuario, id_publicidad) VALUES (?, ?, ?, ?)");
            $stmt2->bind_param("siii", $descripcion, $id_tipo, $id_usuario, $id_publicidad);
            if ($stmt2->execute()) {
                echo "false";
            } else {
                // Ocurrió un error al insertar los datos en la base de datos
                echo "Error al guardar los datos en la base de datos: " . $stmt2->error;
            }
            $stmt2->close();
            $conn->close();
            
        }
    } else {
        echo "El usuario no ha iniciado sesión.";
    }
}
?>