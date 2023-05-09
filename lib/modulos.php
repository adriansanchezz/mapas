<?php
function menu_index() {
?>
    <div class="NAVBAR">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="#">LOGO DE EMPRESA</a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="Inicio.php">INICIO <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="Nosotros.php">Nosotros</a>
                    </li>
                </ul>

                <form class="form-inline my-2 my-lg-0" action="Login.php">
                    <button class="btn btn-outline-success my-2 my-sm-0" name="login" type="submit">Iniciar Sesión</button>
                </form>

                <form class="form-inline my-2 my-lg-0" action="Registro.php">
                    <button class="btn btn-outline-success my-2 my-sm-0" name="registro" type="submit">Registrarse</button>
                </form>
            </div>
        </nav>
    </div>
<?php
}
?>

<?php
function menu_general() {
?>
    <div class="NAVBAR">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" >DisplayADS</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item active">
                            <form action="principal.php">
                                <button class="btn nav-link" name="inicio" type="submit">Inicio </button>
                            </form>
                        </li>

                        <li class="nav-item">
                            <form action="usuario.php">
                                <button class="btn nav-link" name="usuario" type="submit">Usuario</button>
                            </form>
                        </li>

                        <li class="nav-item">
                            <form action="empresa.php">
                                <button class="btn nav-link" name="empresa" type="submit">Empresa</button>
                            </form>
                        </li>

                        <li class="nav-item">
                            <form action="ticket.php">
                                <button class="btn nav-link" name="ticket" type="submit">Ticket</button>
                            </form>
                        </li>

                        <li class="nav-item">
                            <form action="administrador.php">
                                <button class="btn nav-link" name="administrador" type="submit">Administrador</button>
                            </form>
                        </li>
                    </ul>

                    <form class="form-inline my-2 my-lg-0" action="cuenta.php" method="post">
                        <input class="btn btn-outline-success my-2 my-sm-0" type="submit" name="cuenta" value="Cuenta"/>
                    </form>

                    <form class="form-inline my-2 my-lg-0" action="principal.php" method="post">
                        <input class="btn btn-outline-success my-2 my-sm-0" type="submit" name="cerrarSesion" value="Cerrar sesión"/>
                    </form>
                </div>
            </div>
        </nav>
    </div>
<?php
}
?>

<?php
function head_info() {
?>
    <meta charset="UTF-8">
    <meta name="description" content="Aplicación web que facilita a las empresas publicitarse a un precio asequible y a las personas ganar dinero por hacer de publicitadores">
    <meta name="keywords" content="anuncios, empresas, pequeñas empresas, recompensas, publicidad, publicitadores, crecer, carteles, ubicaciones">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<?php
}
?>

<?php
function mapa($valor) {
    if($valor=="ver"){
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
    }
    if($valor=="guardar"){
?>
            <div class="flex-grow-1">
                <h1>MAPA</h1>
                ¿Quieres buscar una ubicación? <input type="text" id="direccion" placeholder="Buscar dirección">
                <button onclick="buscarDireccion()">Buscar</button>
                <div id="map" style="height: 100vh;"></div>
                <script>
                    var map = L.map('map').setView([43.3828500, -3.2204300], 13);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors',
                        maxZoom: 18,
                    }).addTo(map);

                    var marker;

                    map.on('click', function (e) {
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

                <form action="../guardarMarcador.php" method="post" onsubmit="return validarFormulario();">
                    <input type="hidden" name="lat" id="lat">
                    <input type="hidden" name="lng" id="lng">
                    Titulo: <input type="text" name="titulo" id="titulo">
                    Texto: <input type="text" name="texto" id="texto">


                    <button type="submit" name="guardarMarcador">Guardar</button>
                </form>
            </div>
<?php
    }
}
?>
