<?php
// Menu index
function menu_index()
{
    ?>
    <div class="NAVBAR">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="#">LOGO DE EMPRESA</a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
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
//Menu luego de iniciar sesión
function menu_general()
{
    ?>
    <div class="NAVBAR">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand">DisplayADS</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item active">
                            <form action="principal.php">
                                <button class="btn nav-link" name="inicio" type="submit">Inicio </button>
                            </form>
                        </li>
                        <?php
                        if (validarUsuario($_SESSION['usuario']['id_usuario'])) {
                            ?>
                            <li class="nav-item">
                                <form action="usuario.php">
                                    <button class="btn nav-link" name="usuario" type="submit">Usuario</button>
                                </form>
                            </li>
                            <?php
                        }
                        ?>

                        <?php
                        if (validarEmpresa($_SESSION['usuario']['id_usuario'])) {
                            ?>
                            <li class="nav-item">
                                <form action="empresa.php">
                                    <button class="btn nav-link" name="empresa" type="submit">Empresa</button>
                                </form>
                            </li>
                            <?php
                        }
                        ?>

                        <?php
                        if (validarAdmin($_SESSION['usuario']['id_usuario'])) {
                            ?>
                            <li class="nav-item">
                                <form action="administrador.php">
                                    <button class="btn nav-link" name="administrador" type="submit">Administrador</button>
                                </form>
                            </li>
                            <?php
                        }
                        ?>

                        <li class="nav-item">
                            <form action="ticket.php">
                                <button class="btn nav-link" name="ticket" type="submit">Ticket</button>
                            </form>
                        </li>

                    </ul>

                    <form class="form-inline my-2 my-lg-0" action="cuenta.php" method="post">
                        <input class="btn btn-outline-success my-2 my-sm-0" type="submit" name="cuenta" value="Cuenta" />
                    </form>

                    <form class="form-inline my-2 my-lg-0" action="../index.php" method="post">
                        <input class="btn btn-outline-success my-2 my-sm-0" type="submit" name="cerrarSesion"
                            value="Cerrar sesión" />
                    </form>
                </div>
            </div>
        </nav>
    </div>
    <?php
}
?>

<?php
// Link de head
function head_info()
{
    ?>
    <meta charset="UTF-8">
    <meta name="description"
        content="Aplicación web que facilita a las empresas publicitarse a un precio asequible y a las personas ganar dinero por hacer de publicitadores">
    <meta name="keywords"
        content="anuncios, empresas, pequeñas empresas, recompensas, publicidad, publicitadores, crecer, carteles, ubicaciones">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <?php
}
?>

<?php
//Mapa PHP
function mapa($valor)
{

    if ($valor == "ver") {
        ?>
        <div class="flex-grow-1">
            <div id="seccion1" class="p-3" style="display: block;">

                <div class="p-3" style="display: block;">
                    <h1>Aquí estará el mapa</h1>
                    ¿Quieres buscar una ubicación?<input type="text" id="direccion" placeholder="Buscar ubicación...">
                    <button type="button" onclick="buscarDireccion()">Buscar</button>



                    <div id="map"></div>
                    <style>
                        #map {
                            height: 70vh;
                            border: 8px solid #2c3e50;
                            /* Color del borde */
                            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
                            /* Sombra */
                        }
                    </style>
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
                        $sql = "SELECT * FROM propiedades";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $latitud = $row['latitud'];
                                $longitud = $row['longitud'];
                                $descripcion = $row['descripcion'];
                                $ubicacion = $row['ubicacion'];
                                $apiKey = 'AIzaSyADr5gpzLPePzkWwz8C94wBQ21DzQ4GGVU'; // Reemplaza con tu propia API Key de Google Maps Static
                
                                $imageUrl = 'https://maps.googleapis.com/maps/api/streetview?size=400x300&location=' . $latitud . ',' . $longitud . '&key=' . $apiKey;
                                ?>

                                // Crear un marcador para cada registro de la base de datos
                                var marker = L.marker([<?php echo $latitud; ?>, <?php echo $longitud; ?>]).addTo(map);
                                marker.bindPopup("<h3><?php echo $ubicacion; ?></h3><p><?php echo $descripcion; ?></p><img src='<?php echo $imageUrl; ?>' alt='Imagen de la ubicación'><br><form action='empresa.php' method='POST'><input type='hidden' name='product_id' value='<?php echo $row['id_propiedad'] ?>'><input type='hidden' name='lat' value='<?php echo $latitud; ?>'><input type='hidden' name='lng' value='<?php echo $longitud; ?>'><input type='hidden' name='ubicacion' value='<?php echo $ubicacion; ?>'><input type='hidden' name='descripcion' value='<?php echo $descripcion; ?>'><button type='submit' name='add_to_cart' value='1'>Seleccionar</button></form>");

                                function seleccionarUbicacion(latitud, longitud, descripcion, ubicacion) {
                                    // Enviar una solicitud POST al archivo "usuario.php" con los datos de la ubicación seleccionada
                                    var xhttp = new XMLHttpRequest();
                                    xhttp.onreadystatechange = function () {
                                        if (this.readyState === 4 && this.status === 200) {
                                            // Redireccionar a la página del carrito después de agregar la ubicación al carrito
                                            window.location.href = "usuario.php?usuarioCarrito=1";
                                        }
                                    };
                                    xhttp.open("POST", "usuario.php", true);
                                    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                                    xhttp.send("compraUbicacion=1&lat=" + latitud + "&lng=" + longitud + "&ubicacion=" + encodeURIComponent(ubicacion) + "&descripcion=" + encodeURIComponent(descripcion));
                                }
                                <?php
                            }
                        }

                        mysqli_close($conn);
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
        </div>
        <?php
    }
    if ($valor == "guardar") {
        ?>
        <div class="flex-grow-1">
            <div id="seccion1" class="p-3" style="display: block;">
                <h1>MAPA</h1>
                ¿Quieres buscar una ubicación? <input type="text" id="direccion" placeholder="Buscar dirección">
                <button onclick="buscarDireccion()">Buscar</button>
                <div id="map"></div>
                <style>
                    #map {
                        height: 70vh;
                        border: 8px solid #2c3e50;
                        /* Color del borde */
                        box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
                        /* Sombra */
                    }
                </style>
                <script>
                    var map = L.map('map').setView([43.3828500, -3.2204300], 13);

                    var spainBounds = L.latLngBounds(
                        L.latLng(36.0000, -9.3922), // Coordenada superior izquierda (Latitud, Longitud)
                        L.latLng(43.7486, 4.3273)  // Coordenada inferior derecha (Latitud, Longitud)
                    );

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors',
                        maxZoom: 18,
                    }).addTo(map);



                    map.setMaxBounds(spainBounds); // Establecer límites máximos



                    var marker;

                    map.on('click', function (e) {
                        try {
                            if (!spainBounds.contains(e.latlng)) {
                                alert('Por favor, coloque puntos dentro de España.');
                                return;
                            }

                            if (marker) {
                                map.removeLayer(marker);
                            }

                            marker = L.marker(e.latlng).addTo(map);
                            marker.bindPopup("Ubicación seleccionada").openPopup();

                            // Actualizar campos ocultos en el formulario con las coordenadas
                            document.getElementById('lat').value = e.latlng.lat;
                            document.getElementById('lng').value = e.latlng.lng;

                            // Realizar la solicitud de geocodificación a Nominatim
                            var url = 'https://nominatim.openstreetmap.org/reverse?lat=' + e.latlng.lat + '&lon=' + e.latlng.lng + '&format=json';
                            fetch(url)
                                .then(function (response) {
                                    return response.json();
                                })
                                .then(function (data) {
                                    if (data && data.address) {
                                        document.getElementById('provincia').value = data.address.state || '';
                                        document.getElementById('ciudad').value = data.address.city || '';
                                        document.getElementById('ubicacion').value = data.address.road || '';
                                        document.getElementById('codigo_postal').value = data.address.postcode || '';
                                    }
                                })
                                .catch(function (error) {
                                    console.log('Error:', error);
                                });
                        } catch (error) {
                            console.log('Excepción capturada:', error);
                        }
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
                        var texto = document.getElementById('descripcion').value;
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
                <?php
                require_once '../lib/functiones.php';
                require_once '../lib/modulos.php';
                ?>
                <form action="usuario.php" method="post" onsubmit="return validarFormulario(); guardarMarcador();">
                    <input type="hidden" name="lat" id="lat">
                    <input type="hidden" name="lng" id="lng">

                    Descripcion: <textarea name="descripcion" id="descripcion"></textarea>
                    <input type="text" id="provincia" name="provincia" placeholder="Provincia">
                    <input type="text" id="ciudad" name="ciudad" placeholder="Ciudad">
                    <input type="text" id="ubicacion" name="ubicacion" placeholder="Ubicación">
                    <input type="text" id="codigo_postal" name="codigo_postal" placeholder="Código Postal">
                    <select name="tipoPropiedad">
                        <option value="1">Comunidad de vecinos</option>
                    </select>

                    <button type="submit" name="guardarMarcador">Guardar</button>
                </form>

            </div>
        </div>
        <?php
    }
}
?>