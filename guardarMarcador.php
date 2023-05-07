<?php
require_once 'lib/functiones.php';

// Obtener los datos del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lat = $_POST['lat'];
    $lng = $_POST['lng'];
    $titulo = $_POST['titulo'];
    $texto = $_POST['texto'];
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

    // Cerrar la conexión y liberar recursos
    //$stmt->close();
    //cerrarConexion($conn);
}
?>
