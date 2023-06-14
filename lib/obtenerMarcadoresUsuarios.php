<?php
require_once '../lib/funciones.php';
require_once '../lib/modulos.php';
require_once '../lib/mapa.php';
// Establecer la conexión con la base de datos.
$conn = conectar();

// Consultar los marcadores existentes en el mapa.
$sql = "SELECT * FROM publicidades WHERE id_usuario = ?";
$stmt = $conn->prepare($sql);

if ($stmt) {
    // Asignar el valor del parámetro
    $id_usuario = $_SESSION['usuario']['id_usuario'];

    // Vincular el parámetro a la sentencia preparada
    $stmt->bind_param("i", $id_usuario);

    // Ejecutar la consulta
    $stmt->execute();

    // Obtener los resultados
    $result = $stmt->get_result();

    // Crear un array para almacenar los datos de los marcadores
    $marcadores = array();

    // Si da resultados entonces entra en el if.
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Se crean variables con los datos de la consulta que interesa sacar en pantalla u operar con ellos.
            $latitud = $row['latitud'];
            $longitud = $row['longitud'];
            $descripcion = $row['descripcion'];
            $ubicacion = $row['ubicacion'];
            $precio = $row['precio'];
            if($row['caducidad_compra'] !== null)
            {
                $compra = 1;
            }
            else
            {
                $compra = 0;
            }
            // Se obtiene la id del tipo de propiedad.
            $tipo = $row['id_tipo_publicidad'];

            // Y mediante una consulta a la tabla tipospublicidades se obtiene el nombre del tipo de propiedad que es.
            $sql2 = "SELECT nombre FROM tipospublicidades WHERE id_tipo_publicidad = $tipo";
            $result2 = $conn->query($sql2);

            // Si se obtiene resultado entonces se obtiene el nombre.
            if ($result2) {
                $row2 = $result2->fetch_assoc();
                $nombre_tipo = $row2['nombre'];
            } else {
                // Si no, pone que no se ha encontrado.
                $nombre_tipo = "Tipo de publicidad no encontrado";
            }

            $sql3 = "SELECT * FROM fotos WHERE id_publicidad =" . $row['id_publicidad'];
            $result3 = $conn->query($sql3);
            $mostrarImagen = '';
            if ($result3->num_rows > 0) {
                // Recuperar la información de la imagen
                $row3 = $result3->fetch_assoc();
                $imagen = $row3["foto"];
                // Mostrar la imagen en la página
                $mostrarImagen = "<img src='data:image/jpeg;base64," . base64_encode($imagen) . "' alt='Imagen de la mapa usuario' class='imagen_mapa'>";
            }

            // La api key de google. Para poder usar el google static map.
            $apiKey = 'AIzaSyADr5gpzLPePzkWwz8C94wBQ21DzQ4GGVU'; // Reemplaza con tu propia API Key de Google Maps Static

            // Se obtiene una imagen de la localización mediante coordenadas.
            $imageUrl = 'https://maps.googleapis.com/maps/api/streetview?size=400x300&location=' . $latitud . ',' . $longitud . '&key=' . $apiKey;

            // Agregar los datos del marcador al array
            $marcador = array(
                'latitud' => $latitud,
                'longitud' => $longitud,
                'nombre_tipo' => $nombre_tipo,
                'ubicacion' => $ubicacion,
                'precio' => $precio,
                'descripcion' => $descripcion,
                'imageUrl' => $imageUrl,
                'mostrarImagen' => $mostrarImagen,
                'id_publicidad' => $row['id_publicidad'],
                'compra' => $compra
            );

            $marcadores[] = $marcador;
        }
    }

    // Convertir el array a formato JSON
    echo json_encode($marcadores);
}
?>