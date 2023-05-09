<!DOCTYPE html>
<?php
require_once '../lib/functiones.php';
require_once '../lib/modulos.php';
?>
<html>
    <head>
        <?php head_info(); ?>
        <title>DisplayAds</title>
    </head>
    <body>
        <?php menu_general(); ?>
        
        <div class="d-flex vh-100">
            <div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 100px;">
                <br><br>
                
                <ul class="nav nav-pills flex-column mb-auto">
                    <li class="nav-item">
                        <form action="usuario.php" method="post">
                            <button type="submit" name="usuarioInicio" class="btn btn-link nav-link text-white">Inicio</button>
                        </form>
                    </li>
                    <li>
                        <form action="usuario.php" method="post">
                            <button type="submit" name="usuarioVigia" class="btn btn-link nav-link text-white">Vigía</button>
                        </form>
                    </li>
                </ul>
            </div>

            <?php
            if (isset($_POST['usuarioInicio'])) {
            ?>
                <div class="flex-grow-1">
                    <form class="form-inline my-2 my-lg-0" action="usuario.php" method="post">
                        <button class="btn btn-outline-success my-2 my-sm-0" name="usuarioInicioMapa" type="submit">Mapa</button>
                    </form>
                    <form class="form-inline my-2 my-lg-0" action="usuario.php" method="post">
                        <button class="btn btn-outline-success my-2 my-sm-0" name="usuarioInicioTienda" type="submit">Tienda</button>
                    </form>
                    
                </div>
            <?php
            } 
            ?>

            <?php
            if (isset($_POST['usuarioVigia'])) {
            ?>
                <div class="flex-grow-1">
                    <form class="form-inline my-2 my-lg-0" action="usuario.php" method="post">
                        <button class="btn btn-outline-success my-2 my-sm-0" name="usuarioVigiaMisiones" type="submit">Misiones</button>
                    </form>
                    <form class="form-inline my-2 my-lg-0" action="usuario.php" method="post">
                        <button class="btn btn-outline-success my-2 my-sm-0" name="usuarioVigiaPuntos" type="submit">Puntos</button>
                    </form>
                </div>
            <?php
            } 
            ?>
            <?php
            if (isset($_REQUEST['usuarioInicioMapa'])) {
                ?>
                
                    <div class="flex-grow-1">
                        <form class="form-inline my-2 my-lg-0" action="menu.php">
                            <button class="btn btn-outline-success my-2 my-sm-0" name="usuarioInicioMapa" type="submit">Mapa</button>
                        </form>
                        <form class="form-inline my-2 my-lg-0" action="menu.php">
                            <button class="btn btn-outline-success my-2 my-sm-0" name="usuarioInicioTienda" type="submit">Tienda</button>
                        </form>

                        <h1>MAPA</h1>
                        <input type="text" id="direccion" placeholder="Buscar dirección">
                        <button onclick="buscarDireccion()">Buscar</button>
                        <div id="map" style="height: 400px;"></div>
                        <script>
                        var map = L.map('map').setView([43.3828500, -3.2204300], 13);

                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors',
                            maxZoom: 18,
                        }).addTo(map);

                        var marker;

                        map.on('click', function(e) {
                            if (marker) {
                                map.removeLayer(marker);
                            }

                            marker = L.marker(e.latlng).addTo(map);
                            marker.bindPopup("Ubicación seleccionada").openPopup();

                            // Actualizar campos ocultos en el formulario con las coordenadas
                            document.getElementById('lat').value = e.latlng.lat;
                            document.getElementById('lng').value = e.latlng.lng;
                        });

                        function buscarDireccion() {
                            var direccion = document.getElementById('direccion').value;

                            // Realizar la petición de geocodificación
                            fetch('https://nominatim.openstreetmap.org/search?format=json&q=' + direccion)
                                .then(function(response) {
                                    return response.json();
                                })
                                .then(function(data) {
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
                                .catch(function(error) {
                                    console.log('Error:', error);
                                });
                        }

                        function validarFormulario() {
                            var titulo = document.getElementById('titulo').value;
                            var texto = document.getElementById('texto').value;
                            var latitud = parseFloat(document.getElementById('lat').value);
                            var longitud = parseFloat(document.getElementById('lng').value);

                            if (titulo.trim() === '' || texto.trim() === '') {
                                alert('Los campos de título y texto no pueden estar vacíos.');
                                return false;
                            }

                            if (isNaN(latitud) || isNaN(longitud)) {
                                alert('Debe seleccionar una ubicación en el mapa.');
                                return false;
                            }

                            return true;
                        }
                        </script>

                        <form action="guardarMarcador.php" method="post" onsubmit="return validarFormulario();">
                            <input type="hidden" name="lat" id="lat">
                            <input type="hidden" name="lng" id="lng">
                            Titulo: <input type="text" name="titulo" id="titulo">
                            Texto: <input type="text" name="texto" id="texto">

                            <button type="submit" name="guardarMarcador">Guardar</button>
                        </form>
                    </div>
                </div>
                <?php
            }
            ?>
            

            <?php
            if (isset($_POST['usuarioInicioTienda'])) {
            ?> 
                <h1>TIENDA</h1>
            <?php
            } 
            ?>

            <?php
            if (isset($_POST['usuarioVigiaMisiones'])) {
            ?> 
                <h1>MISIONES</h1>
            <?php
            } 
            ?>
            
            <?php
            if (isset($_POST['usuarioVigiaPuntos'])) {
            ?> 
                <h1>PUNTOS</h1>
            <?php
            } 
            ?>

        </div>

    </body>
</html>