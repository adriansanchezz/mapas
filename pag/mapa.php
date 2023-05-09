<!DOCTYPE html>
<?php
require_once '../lib/functiones.php';
require_once '../lib/modulos.php';
?>
<html>

<head>
    <title>DisplayADS - Menú</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

</head>

<body>


    <?php
    if (isset($_REQUEST['empresaMapa'])) {

        ?>
        <div class="flex-grow-1">
            <div class="p-3" style="display: block;">
                <h1>Aquí estará el mapa</h1>
                ¿Quieres buscar una ubicación?<input type="text" id="direccion" placeholder="Buscar ubicación...">
                <button type="button" onclick="buscarDireccion()">Buscar</button>

                <div id="map" style="height: 100vh;"></div>
                <div id="infoContainer"></div>
                <script>
                    var map = L.map('map').setView([43.3828500, -3.2204300], 13);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors',
                        maxZoom: 18,
                    }).addTo(map);

                    <?php

                    // Establecer la conexión con la base de datos
                    $conn = conectar();

                    // Consultar los marcadores existentes
                    $sql = "SELECT * FROM marcadores";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $lat = $row['lat'];
                            $lng = $row['lng'];
                            $titulo = $row['titulo'];
                            $texto = $row['texto'];
                            $apiKey = 'AIzaSyADr5gpzLPePzkWwz8C94wBQ21DzQ4GGVU'; // Reemplaza con tu propia API Key de Google Maps Static
                
                            // Construir la URL de la imagen de Google Maps Static
                            $imageUrl = 'https://maps.googleapis.com/maps/api/streetview?size=400x300&location=' . $lat . ',' . $lng . '&key=' . $apiKey;
                            ?>

                            // Crear un marcador para cada registro de la base de datos
                            var marker = L.marker([<?php echo $lat; ?>, <?php echo $lng; ?>]).addTo(map);
                            marker.bindPopup("<h3><?php echo $titulo; ?></h3><p><?php echo $texto; ?></p><img src='<?php echo $imageUrl; ?>' alt='Imagen de la ubicación'><br><form action='./confirmarCompra.php' method='POST'><input type='hidden' name='lat' value='<?php echo $lat; ?>'><input type='hidden' name='lng' value='<?php echo $lng; ?>'><input type='hidden' name='titulo' value='<?php echo $titulo; ?>'><input type='hidden' name='texto' value='<?php echo $texto; ?>'><button type='submit' name='compraUbicacion' value='1'>Seleccionar</button></form>");

                            function seleccionarUbicacion(lat, lng, titulo, texto) {
                                // Redireccionar a confirmarCompra.php con los datos de la ubicación seleccionada
                                window.location.href = "confirmarCompra.php?lat=" + lat + "&lng=" + lng + "&titulo=" + encodeURIComponent(titulo) + "&texto=" + encodeURIComponent(texto);
                            }
                            <?php
                        }
                    }

                    // Cerrar la conexión y liberar recursos
                    //errarConexion($conn);
                    ?>


                    function buscarDireccion() {
                        var direccion = document.getElementById('direccion').value;

                        // Realizar la petición de geocodificación
                        fetch('https://nominatim.openstreetmap.org/search?format=json&q=' + direccion)
                            .then(function (response) {
                                return response.json();
                            })
                            .then(function (data) {
                                if (data.length > 0) {
                                    var latitud = parseFloat(data[0].lat);
                                    var longitud = parseFloat(data[0].lon);

                                    // Centrar el mapa en la ubicación encontrada
                                    map.setView([latitud, longitud], 13);

                                    if (marker) {
                                        map.removeLayer(marker);
                                    }

                                    marker = L.marker([latitud, longitud]).addTo(map);
                                    marker.bindPopup("Ubicación encontrada").openPopup();

                                    // Actualizar campos ocultos en el formulario con las coordenadas
                                    document.getElementById('lat').value = latitud;
                                    document.getElementById('lng').value = longitud;
                                } else {
                                    alert("No se encontró la dirección especificada.");
                                }
                            })
                            .catch(function (error) {
                                console.log('Error:', error);
                            });
                    }

                </script>
            </div>
        </div>
        <?php
    } else if (isset($_REQUEST['usuarioMapa'])) {
        ?>



        <?php
    } else if (isset($_REQUEST['vigiaMapa'])) {

    }

    ?>

</body>
