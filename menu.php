<!DOCTYPE html>
<?php
// require_once 'lib/functiones.php';
// session_start();
?>
<?php
require_once 'lib/functiones.php';
require_once 'lib/modulos.php';
?>
<html>
    <head>
        <title>DisplayADS - Menú</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
        
    </head>
    <body>
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
                                <form action="menu.php">
                                    <button class="btn nav-link" name="inicio" type="submit">Inicio <span class="sr-only">(current)</span></button>
                                </form>
                            </li>
                            <li class="nav-item">
                                <form action="menu.php">
                                    <button class="btn nav-link" name="nosotros" type="submit">Nosotros</button>
                                </form>
                            </li>
                            <li class="nav-item">
                                <form action="menu.php">
                                    <button class="btn nav-link" name="usuario" type="submit">Usuario</button>
                                </form>
                            </li>
                            <li class="nav-item">
                                <form action="menu.php">
                                    <button class="btn nav-link" name="empresa" type="submit">Empresa</button>
                                </form>
                            </li>
                            <li class="nav-item">
                                <form action="menu.php">
                                    <button class="btn nav-link" name="administrador" type="submit">Administrador</button>
                                </form>
                            </li>
                        </ul>
                        <form class="form-inline my-2 my-lg-0" action="Cuenta.php">
                            <button class="btn btn-outline-success my-2 my-sm-0" name="login" type="submit">Cuenta</button>
                        </form>
                        <form class="form-inline my-2 my-lg-0" action="Index.php">
                            <button class="btn btn-outline-success my-2 my-sm-0" name="registro" type="submit">Cerrar sesión</button>
                        </form>
                    </div>
                </div>
            </nav>
        </div>

        <?php
        if (isset($_REQUEST['inicio'])) {
            ?>
            <h1>INICIO</h1>

            <?php
        } 
        
        else if (isset($_REQUEST['nosotros'])) {
            ?>
            <h1>NOSOTROS</h1>
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <!-- Contenedor de la izquierda -->
                        <h2>TEXTO EXPLICATIVO</h2>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed in nibh eu nisi lacinia pretium.</p>
                    </div>
                </div>
                <?php
            } 
            
            
            else if (isset($_REQUEST['usuario'])) {
                ?>
                <div class="d-flex vh-100">
                    <div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 280px;">
                        <hr>
                        <ul class="nav nav-pills flex-column mb-auto">
                            <li class="nav-item">
                                <form action="menu.php" method="get">
                                    <button type="submit" name="usuarioInicio" class="btn btn-link nav-link text-white">
                                        Inicio
                                    </button>
                                </form>
                            </li>
                            <li>
                                <form action="menu.php" method="get">
                                    <button type="submit" name="usuarioVigia" class="btn btn-link nav-link text-white">
                                        Vigía
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    <div class="flex-grow-1">

                    </div>
                </div>
                <?php
            } 
            
            
            
            
            else if (isset($_REQUEST['empresa'])) {
                ?>   
                <div class="d-flex vh-100">
                    <div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 280px;">
                        <hr>
                        <ul class="nav nav-pills flex-column mb-auto">
                            <li class="nav-item">
                                <form action="menu.php" method="get">
                                    <button type="submit" name="empresaMapa" class="btn btn-link nav-link text-white">
                                        Mapa
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>

                </div>
                <?php
            } 
            
            
            
            else if (isset($_REQUEST['empresaMapa'])) {
                ?>
                <div class="d-flex vh-100">
                    <div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 280px;">
                        <hr>
                        <ul class="nav nav-pills flex-column mb-auto">
                            <li class="nav-item">
                                <form action="menu.php" method="get">
                                    <button type="submit" name="empresaMapa" class="btn btn-link nav-link text-white">
                                        Mapa
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>


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
                <?php
            } 
            
            
            
            else if (isset($_REQUEST['administrador'])) {
                ?>
                <div class="d-flex vh-100">
                    <div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 280px;">
                        <hr>
                        <ul class="nav nav-pills flex-column mb-auto">
                            <li class="nav-item">
                                <form action="menu.php" method="get">
                                    <button type="submit" name="administradorAdministradores" class="btn btn-link nav-link text-white">
                                        Administradores
                                    </button>
                                </form>
                            </li>
                            <li class="nav-item">
                                <form action="menu.php" method="get">
                                    <button type="submit" name="administradorEmpresas" class="btn btn-link nav-link text-white">
                                        Empresas
                                    </button>
                                </form>
                            </li>
                            <li class="nav-item">
                                <form action="menu.php" method="get">
                                    <button type="submit" name="administradorVigilantes" class="btn btn-link nav-link text-white">
                                        Vigilantes
                                    </button>
                                </form>
                            </li>
                            <li class="nav-item">
                                <form action="menu.php" method="get">
                                    <button type="submit" name="administradorUsuarios" class="btn btn-link nav-link text-white">
                                        Usuarios
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    <div class="flex-grow-1">
                        <div id="seccion1" class="p-3" style="display: block;">
                            <h1></h1>
                        </div>

                    </div>
                </div>
                <?php
            } 
            
            
            
            else if (isset($_REQUEST['administradorAdministradores'])) {
                ?>
                <div class="d-flex vh-100">
                    <div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 280px;">
                        <hr>
                        <ul class="nav nav-pills flex-column mb-auto">
                            <li class="nav-item">
                                <form action="menu.php" method="get">
                                    <button type="submit" name="administradorAdministradores" class="btn btn-link nav-link text-white">
                                        Administradores
                                    </button>
                                </form>
                            </li>
                            <li class="nav-item">
                                <form action="menu.php" method="get">
                                    <button type="submit" name="administradorEmpresas" class="btn btn-link nav-link text-white">
                                        Empresas
                                    </button>
                                </form>
                            </li>
                            <li class="nav-item">
                                <form action="menu.php" method="get">
                                    <button type="submit" name="administradorVigilantes" class="btn btn-link nav-link text-white">
                                        Vigilantes
                                    </button>
                                </form>
                            </li>
                            <li class="nav-item">
                                <form action="menu.php" method="get">
                                    <button type="submit" name="administradorUsuarios" class="btn btn-link nav-link text-white">
                                        Usuarios
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    <div class="flex-grow-1">
                        <div id="seccion1" class="p-3" style="display: block;">
                            <h1></h1>
                        </div>

                    </div>
                </div>
                <?php
            } 
            
            
            
            else if (isset($_REQUEST['administradorEmpresas'])) {
                ?>
                <div class="d-flex vh-100">
                    <div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 280px;">
                        <hr>
                        <ul class="nav nav-pills flex-column mb-auto">
                            <li class="nav-item">
                                <form action="menu.php" method="get">
                                    <button type="submit" name="administradorAdministradores" class="btn btn-link nav-link text-white">
                                        Administradores
                                    </button>
                                </form>
                            </li>
                            <li class="nav-item">
                                <form action="menu.php" method="get">
                                    <button type="submit" name="administradorEmpresas" class="btn btn-link nav-link text-white">
                                        Empresas
                                    </button>
                                </form>
                            </li>
                            <li class="nav-item">
                                <form action="menu.php" method="get">
                                    <button type="submit" name="administradorVigilantes" class="btn btn-link nav-link text-white">
                                        Vigilantes
                                    </button>
                                </form>
                            </li>
                            <li class="nav-item">
                                <form action="menu.php" method="get">
                                    <button type="submit" name="administradorUsuarios" class="btn btn-link nav-link text-white">
                                        Usuarios
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    <div class="flex-grow-1">
                        <div id="seccion1" class="p-3" style="display: block;">
                            <h1></h1>
                        </div>

                    </div>
                </div>
                <?php
            } 
            
            
            else if (isset($_REQUEST['administradorVigilantes'])) {
                ?>
                <div class="d-flex vh-100">
                    <div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 280px;">
                        <hr>
                        <ul class="nav nav-pills flex-column mb-auto">
                            <li class="nav-item">
                                <form action="menu.php" method="get">
                                    <button type="submit" name="administradorAdministradores" class="btn btn-link nav-link text-white">
                                        Administradores
                                    </button>
                                </form>
                            </li>
                            <li class="nav-item">
                                <form action="menu.php" method="get">
                                    <button type="submit" name="administradorEmpresas" class="btn btn-link nav-link text-white">
                                        Empresas
                                    </button>
                                </form>
                            </li>
                            <li class="nav-item">
                                <form action="menu.php" method="get">
                                    <button type="submit" name="administradorVigilantes" class="btn btn-link nav-link text-white">
                                        Vigilantes
                                    </button>
                                </form>
                            </li>
                            <li class="nav-item">
                                <form action="menu.php" method="get">
                                    <button type="submit" name="administradorUsuarios" class="btn btn-link nav-link text-white">
                                        Usuarios
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    <div class="flex-grow-1">
                        <div id="seccion1" class="p-3" style="display: block;">
                            <h1></h1>
                        </div>

                    </div>
                </div>
                <?php
            } 
            
            
            
            else if (isset($_REQUEST['administradorUsuarios'])) {
                ?>
                <div class="d-flex vh-100">
                    <div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 280px;">
                        <hr>
                        <ul class="nav nav-pills flex-column mb-auto">
                            <li class="nav-item">
                                <form action="menu.php" method="get">
                                    <button type="submit" name="administradorAdministradores" class="btn btn-link nav-link text-white">
                                        Administradores
                                    </button>
                                </form>
                            </li>
                            <li class="nav-item">
                                <form action="menu.php" method="get">
                                    <button type="submit" name="administradorEmpresas" class="btn btn-link nav-link text-white">
                                        Empresas
                                    </button>
                                </form>
                            </li>
                            <li class="nav-item">
                                <form action="menu.php" method="get">
                                    <button type="submit" name="administradorVigilantes" class="btn btn-link nav-link text-white">
                                        Vigilantes
                                    </button>
                                </form>
                            </li>
                            <li class="nav-item">
                                <form action="menu.php" method="get">
                                    <button type="submit" name="administradorUsuarios" class="btn btn-link nav-link text-white">
                                        Usuarios
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    <div class="flex-grow-1">
                        <div id="seccion1" class="p-3" style="display: block;">
                            <h1></h1>
                        </div>

                    </div>
                </div>
                <?php
            } 
            
            
            
            else if (isset($_REQUEST['usuarioInicio'])) {
                ?>
                <div class="d-flex vh-100">
                    <div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 280px;">
                        <hr>
                        <ul class="nav nav-pills flex-column mb-auto">
                            <li class="nav-item">
                                <form action="menu.php" method="get">
                                    <button type="submit" name="usuarioInicio" class="btn btn-link nav-link text-white">
                                        Inicio
                                    </button>
                                </form>
                            </li>
                            <li>
                                <form action="menu.php" method="get">
                                    <button type="submit" name="usuarioVigia" class="btn btn-link nav-link text-white">
                                        Vigía
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    <div class="flex-grow-1">
                        <form class="form-inline my-2 my-lg-0" action="menu.php">
                            <button class="btn btn-outline-success my-2 my-sm-0" name="usuarioInicioMapa" type="submit">Mapa</button>
                        </form>
                        <form class="form-inline my-2 my-lg-0" action="menu.php">
                            <button class="btn btn-outline-success my-2 my-sm-0" name="usuarioInicioTienda" type="submit">Tienda</button>
                        </form>
                    </div>
                </div>
                <?php
            } 
            
            
            
            else if (isset($_REQUEST['usuarioVigia'])) {
                ?>
                <div class="d-flex vh-100">
                    <div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 280px;">
                        <hr>
                        <ul class="nav nav-pills flex-column mb-auto">
                            <li class="nav-item">
                                <form action="menu.php" method="get">
                                    <button type="submit" name="usuarioInicio" class="btn btn-link nav-link text-white">
                                        Inicio
                                    </button>
                                </form>
                            </li>
                            <li>
                                <form action="menu.php" method="get">
                                    <button type="submit" name="usuarioVigia" class="btn btn-link nav-link text-white">
                                        Vigía
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    <div class="flex-grow-1">
                        <form class="form-inline my-2 my-lg-0" action="menu.php">
                            <button class="btn btn-outline-success my-2 my-sm-0" name="usuarioVigiaMisiones" type="submit">Misiones</button>
                        </form>
                        <form class="form-inline my-2 my-lg-0" action="menu.php">
                            <button class="btn btn-outline-success my-2 my-sm-0" name="usuarioVigiaPuntos" type="submit">Puntos</button>
                        </form>


                    </div>
                </div>
                <?php
            } 
            
            
            
            else if (isset($_REQUEST['usuarioInicioMapa'])) {
                ?>
                <div class="d-flex vh-100">
                    <div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 280px;">
                        <hr>
                        <ul class="nav nav-pills flex-column mb-auto">
                            <li class="nav-item">
                                <form action="menu.php" method="get">
                                    <button type="submit" name="usuarioInicio" class="btn btn-link nav-link text-white">
                                        Inicio
                                    </button>
                                </form>
                            </li>
                            <li>
                                <form action="menu.php" method="get">
                                    <button type="submit" name="usuarioVigia" class="btn btn-link nav-link text-white">
                                        Vigía
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
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
            
            
            
            
            else if (isset($_REQUEST['usuarioInicioTienda'])) {
                ?> 
                <div class="d-flex vh-100">
                    <div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 280px;">
                        <hr>
                        <ul class="nav nav-pills flex-column mb-auto">
                            <li class="nav-item">
                                <form action="menu.php" method="get">
                                    <button type="submit" name="usuarioInicio" class="btn btn-link nav-link text-white">
                                        Inicio
                                    </button>
                                </form>
                            </li>
                            <li>
                                <form action="menu.php" method="get">
                                    <button type="submit" name="usuarioVigia" class="btn btn-link nav-link text-white">
                                        Vigía
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    <div class="flex-grow-1">
                        <form class="form-inline my-2 my-lg-0" action="menu.php">
                            <button class="btn btn-outline-success my-2 my-sm-0" name="usuarioInicioMapa" type="submit">Mapa</button>
                        </form>
                        <form class="form-inline my-2 my-lg-0" action="menu.php">
                            <button class="btn btn-outline-success my-2 my-sm-0" name="usuarioInicioTienda" type="submit">Tienda</button>
                        </form>
                        <h1>TIENDA</h1>
                    </div>
                </div>
                <?php
            } 
            
            
            
            
            else if (isset($_REQUEST['usuarioVigiaMisiones'])) {
                ?> 
                <div class="d-flex vh-100">
                    <div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 280px;">
                        <hr>
                        <ul class="nav nav-pills flex-column mb-auto">
                            <li class="nav-item">
                                <form action="menu.php" method="get">
                                    <button type="submit" name="usuarioInicio" class="btn btn-link nav-link text-white">
                                        Inicio
                                    </button>
                                </form>
                            </li>
                            <li>
                                <form action="menu.php" method="get">
                                    <button type="submit" name="usuarioVigia" class="btn btn-link nav-link text-white">
                                        Vigía
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    <div class="flex-grow-1">
                        <form class="form-inline my-2 my-lg-0" action="menu.php">
                            <button class="btn btn-outline-success my-2 my-sm-0" name="usuarioVigiaMisiones" type="submit">Misiones</button>
                        </form>
                        <form class="form-inline my-2 my-lg-0" action="menu.php">
                            <button class="btn btn-outline-success my-2 my-sm-0" name="usuarioVigiaPuntos" type="submit">Puntos</button>
                        </form>
                        <h1>MISIONES</h1>

                    </div>
                </div>
                <?php
            } 
            
            
            
            
            else if (isset($_REQUEST['usuarioVigiaPuntos'])) {
                ?>
                <div class="d-flex vh-100">
                    <div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 280px;">
                        <hr>
                        <ul class="nav nav-pills flex-column mb-auto">
                            <li class="nav-item">
                                <form action="menu.php" method="get">
                                    <button type="submit" name="usuarioInicio" class="btn btn-link nav-link text-white">
                                        Inicio
                                    </button>
                                </form>
                            </li>
                            <li>
                                <form action="menu.php" method="get">
                                    <button type="submit" name="usuarioVigia" class="btn btn-link nav-link text-white">
                                        Vigía
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    <div class="flex-grow-1">
                        <form class="form-inline my-2 my-lg-0" action="menu.php">
                            <button class="btn btn-outline-success my-2 my-sm-0" name="usuarioVigiaMisiones" type="submit">Misiones</button>
                        </form>
                        <form class="form-inline my-2 my-lg-0" action="menu.php">
                            <button class="btn btn-outline-success my-2 my-sm-0" name="usuarioVigiaPuntos" type="submit">Puntos</button>
                        </form>
                        <h1>PUNTOS</h1>

                    </div>
                </div>
                <?php
            }
            ?>
    </body>
</html>
