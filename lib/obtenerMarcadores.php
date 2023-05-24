<?php

    require_once '../lib/functiones.php';
    require_once '../lib/modulos.php';
    // Obtener los valores enviados desde JavaScript
    $data = json_decode(file_get_contents('php://input'), true);
    $latMin = $data['latMin'];
    $lngMin = $data['lngMin'];
    $latMax = $data['latMax'];
    $lngMax = $data['lngMax'];

    // Establecer la conexión con la base de datos
    $conn = conectar();

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Error de conexión a la base de datos: " . $conn->connect_error);
    }

    // Consultar los marcadores existentes dentro del límite
    $sql = "SELECT * FROM publicidades WHERE latitud BETWEEN ? AND ? AND longitud BETWEEN ? AND ? AND estado = 0 AND ocupado = 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("dddd", $latMin, $latMax, $lngMin, $lngMax);
    $stmt->execute();
    $result = $stmt->get_result();

    $markers = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $markers[] = $row;
        }
    }

    // Cerrar la conexión a la base de datos
    $stmt->close();
    $conn->close();

    // Devolver los marcadores como respuesta en formato JSON
    header('Content-Type: application/json');
    echo json_encode($markers);

?>