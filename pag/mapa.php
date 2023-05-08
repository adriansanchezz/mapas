
    <div class="flex-grow-1">
        <div class="p-3" style="display: block;">
            <h1>Aquí estará el mapa</h1>
            <div id="map" style="height: 100vh;"></div>
            <div id="infoContainer"></div>
            <script>
                var map = L.map('map').setView([43.3828500, -3.2204300], 13);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors',
                    maxZoom: 18,
                }).addTo(map);

                <?php
                require_once 'lib/functiones.php';

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
                        marker.bindPopup("<h3><?php echo $titulo; ?></h3><p><?php echo $texto; ?></p><img src='<?php echo $imageUrl; ?>' alt='Imagen de la ubicación'>");
                        <?php
                    }
                }

                // Cerrar la conexión y liberar recursos
                //errarConexion($conn);
                ?>

                // Resto del código del mapa...
            </script>

        </div>
    </div>
</div>