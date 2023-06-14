<?php
require_once '../lib/funciones.php';
require_once '../lib/modulos.php';
require_once '../lib/mapa.php';

// Se realiza un select de los marcadores, las imágenes y sus parámetros deben ser estado en 1, ocupado en 0, comprador en null y que el usuario sea distinto el que ha 
// posteado la publicación del que se quiere publicitar.
$sql = "SELECT p.*, t.nombre AS nombre_tipo, f.foto AS imagen
        FROM publicidades AS p
        INNER JOIN tipospublicidades AS t ON p.id_tipo_publicidad = t.id_tipo_publicidad
        LEFT JOIN fotos AS f ON p.id_publicidad = f.id_publicidad
        WHERE (p.revision IS NULL OR p.revision = 1) AND p.estado = 1 AND p.ocupado = 0 AND p.comprador IS NULL AND p.id_usuario <> " . $_SESSION['usuario']['id_usuario'];

$result = sqlSELECT($sql);

// Creación de array de publicidades.
$publicidades = array();

// Si da resultados entonces se construye el array de publicidades.
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Se crean variables con los datos de la consulta que interesa sacar en pantalla u operar con ellos.
        $publicidad = array(
            'id_publicidad' => $row['id_publicidad'],
            'latitud' => $row['latitud'],
            'longitud' => $row['longitud'],
            'descripcion' => $row['descripcion'],
            'ubicacion' => $row['ubicacion'],
            'precio' => $row['precio'],
            'nombre_tipo' => $row['nombre_tipo'],
            'seleccionada' => false,
            'imageUrl' => '',
            'mostrarImagen' => ''
        );

        // Si imagen en distinto de null entonces se crea mostrarImagen que será la imagen que el usuario ha subido.
        if ($row['imagen'] !== null) {
            $imagen = $row['imagen'];
            $publicidad['mostrarImagen'] = "<img src='data:image/jpeg;base64," . base64_encode($imagen) . "' alt='Imagen de la mapa'>";
        }

        // Se obtiene la id del tipo de propiedad.
        $tipo = $row['id_tipo_publicidad'];

        // La api key de google. Para poder usar el google static map.
        $apiKey = 'AIzaSyADr5gpzLPePzkWwz8C94wBQ21DzQ4GGVU'; // Reemplaza con tu propia API Key de Google Maps Static

        // Se obtiene una imagen de la localización mediante coordenadas.
        $publicidad['imageUrl'] = 'https://maps.googleapis.com/maps/api/streetview?size=400x300&location=' . $publicidad['latitud'] . ',' . $publicidad['longitud'] . '&key=' . $apiKey;

        $publicidades[] = $publicidad;
    }
}

// Se devuelve en json al js.
echo json_encode($publicidades);
?>