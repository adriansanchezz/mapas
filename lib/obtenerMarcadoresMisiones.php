<?php
require_once '../lib/funciones.php';
require_once '../lib/modulos.php';
require_once '../lib/mapa.php';

// Consulta para obtener los datos de publicidades y misiones
$sql = "SELECT * FROM publicidades AS p, misiones AS m WHERE p.id_publicidad = m.id_publicidad AND p.estado = 1 AND p.ocupado = 1 AND p.caducidad_compra IS NOT NULL AND m.aceptacion = 0 AND m.id_usuario = " . $_SESSION['usuario']['id_usuario'];
$result = sqlSELECT($sql);

$markers = array(); // Array para almacenar los marcadores

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $markers[] = $row; // Agregar cada fila como un marcador al array
    }
}

// Convertir el array a formato JSON
echo json_encode($markers);
?>