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
                        <a class="nav-link" href="index.php">INICIO <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="nosotros.php">Nosotros</a>
                    </li>
                </ul>

                <form class="form-inline my-2 my-lg-0" action="login.php">
                    <button class="btn btn-outline-success my-2 my-sm-0" name="login" type="submit">Iniciar Sesión</button>
                </form>

                <form class="form-inline my-2 my-lg-0" action="registro.php">
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
                                    <button class="btn nav-link" name="usuarioPrincipal" type="submit">Usuario</button>
                                </form>
                            </li>
                            <?php
                        }
                        ?>

                        <?php
                        if (validarVigilante($_SESSION['usuario']['id_usuario'])) {
                            ?>
                            <li class="nav-item">
                                <form action="vigilante.php">
                                    <button class="btn nav-link" name="vigilantePrincipal" type="submit">Vigilante</button>
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
                                    <button class="btn nav-link" name="empresaPrincipal" type="submit">Empresa</button>
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
                                    <button class="btn nav-link" name="administradorPanel" type="submit">Administrador</button>
                                </form>
                            </li>
                            <?php
                        }
                        ?>

                    </ul>

                    <form class="form-inline my-2 my-lg-0" action="cuenta.php" method="post">
                        <input class="btn btn-outline-success my-2 my-sm-0" type="submit" name="cuenta"
                            value="Cuenta" />&nbsp;&nbsp;
                    </form>

                    <form class="form-inline my-2 my-lg-0" action="ticket.php" method="post">
                        <input class="btn btn-outline-success my-2 my-sm-0" type="submit" name="ticket" value="Ticket" />
                    </form>

                    <form class="form-inline my-2 my-lg-0" action="../index.php" method="post">
                        <button class="btn nav-link" name="cerrarSesion" type="submit">
                            <i class="fa-solid fa-right-from-bracket" style="color: #ffffff;"></i>
                        </button>
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
    <link href="css/style.css" rel="stylesheet" type="text/css">
    <link href="css/modulos.css" rel="stylesheet" type="text/css">
    <link href="css/menu.css" rel="stylesheet" type="text/css">
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="../js/funciones.js"></script>
    
    <?php
}
?>

<?php
//Función de mapa al que se le pasa un valor y dependiendo de este se realiza una u otra función.
function mapa($valor)
{
    // Si el valor es ver, entonces la función irá enfocada a ver el mapa y los puntos creados por el usuario.
    // Esta función está enfocada al menú de empresa.
    if ($valor == "ver") {
        ?>
        <!-- Se crea toda la maquetación del menú de empresa. -->
        <div class="flex-grow-1">
            <div class="p-3" style="display: block;">
                <div class="p-3" style="display: block;">
                    <form class="form-inline my-2 my-lg-0" action="empresa.php" method="post">
                        <button class="btn btn-outline-success my-2 my-sm-0" name="empresaCarrito"
                            type="submit">Carrito</button>
                    </form>
                    <h1>Bienvenido a nuestro mapa. </h1>
                    <h4>Selecciona alguna ubicación para ver información:</h4>
                    <h6>O busca la ubicación que desees.</h6>
                    ¿Quieres buscar una ubicación?<input type="text" id="direccion" placeholder="Buscar ubicación...">
                    <button type="button" onclick="buscarDireccion()">Buscar</button>

                    <div id="map"></div>
                    <!-- Se le da estilos al mapa para que quede más estético. -->
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
                        // Creación del mapa.
                        var map = L.map('map').setView([43.3828500, -3.2204300], 13);

                        // Selección de cuanto zoom tendrá y más atributos necesarios.
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors',
                            maxZoom: 18,
                        }).addTo(map);

                        <?php
                        // Establecer la conexión con la base de datos.
                        $conn = conectar();

                        // Consultar los marcadores existentes en el mapa.
                        $sql = "SELECT * FROM publicidades";
                        $result = $conn->query($sql);

                        // Si da resultados entonces entra en el if.
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                // Se crean variables con los datos de la consulta que interesa sacar en pantalla u operar con ellos.
                                $latitud = $row['latitud'];
                                $longitud = $row['longitud'];
                                $descripcion = $row['descripcion'];
                                $ubicacion = $row['ubicacion'];
                                $precio = $row['precio'];

                                // Se obtiene la id del tipo de propiedad.
                                $tipo = $row['id_tipo_publicidad'];

                                // Y mediante una consulta a la tabla tipospropiedades se obtiene el nombre del tipo de propiedad que es.
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
                                // La api key de google. Para poder usar el google static map.
                                $apiKey = 'AIzaSyADr5gpzLPePzkWwz8C94wBQ21DzQ4GGVU'; // Reemplaza con tu propia API Key de Google Maps Static
                
                                // Se obtiene una imagen de la localización mediante coordenadas.
                                $imageUrl = 'https://maps.googleapis.com/maps/api/streetview?size=400x300&location=' . $latitud . ',' . $longitud . '&key=' . $apiKey;
                                ?>

                                // Crear un marcador para cada registro de la base de datos.
                                var marker = L.marker([<?php echo $latitud; ?>, <?php echo $longitud; ?>]).addTo(map);
                                // Se añade un popUp para que salga una ventana al clickar un marcador existente en el mapa.
                                marker.bindPopup("<div class='popup-content'><h3><?php echo $nombre_tipo . " " . $ubicacion . " " . $precio . "€"; ?></h3><p><?php echo $descripcion; ?></p><img src='<?php echo $imageUrl; ?>' alt='Imagen de la ubicación'></div><form action='empresa.php' method='POST'><input type='hidden' name='product_id' value='<?php echo $row['id_publicidad'] ?>'><input type='hidden' name='lat' value='<?php echo $latitud; ?>'><input type='hidden' name='lng' value='<?php echo $longitud; ?>'><input type='hidden' name='ubicacion' value='<?php echo $ubicacion; ?>'><input type='hidden' name='descripcion' value='<?php echo $descripcion; ?>'><button type='submit' name='add_to_cart' value='1'>Seleccionar</button></form>");


                                <?php
                            }
                        }
                        // Se cierra la conexión de la BD.
                        mysqli_close($conn);
                        ?>

                        // Función para buscar una dirección mediante una barra de búsqueda.
                        function buscarDireccion() {
                            var direccion = document.getElementById('direccion').value;

                            // Realizar la petición de geocodificación mediante un fetch.then.then.catch para asegurarse de que funciona.
                            fetch('https://nominatim.openstreetmap.org/search?format=json&q=' + direccion)
                                .then(function (response) {
                                    return response.json();
                                })
                                .then(function (data) {
                                    if (data.length > 0) {
                                        var latitud = parseFloat(data[0].lat);
                                        var longitud = parseFloat(data[0].lon);

                                        // Centrar el mapa en la ubicación encontrada.
                                        map.setView([latitud, longitud], 13);

                                        if (marker) {
                                            map.removeLayer(marker);
                                        }

                                        marker = L.marker([latitud, longitud]).addTo(map);
                                        marker.bindPopup("Ubicación encontrada").openPopup();

                                        // Actualizar campos ocultos en el formulario con las coordenadas.
                                        document.getElementById('lat').value = latitud;
                                        document.getElementById('lng').value = longitud;
                                    } else {
                                        alert("No se encontró la dirección especificada.");
                                    }
                                })
                                .catch(function (error) {
                                    // Por consola se señala el error.
                                    console.log('Error:', error);
                                });
                        }
                    </script>
                </div>
            </div>
        </div>
        <?php
    }
    // Apartado para guardar un marcador de una ubicación. Esto está centralizado hacia el menú de usuario.
    if ($valor == "guardar") {
        ?>
        <div class="flex-grow-1">
            <div class="p-3" style="display: block;">
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
                    // Se crea el mapa.
                    var map = L.map('map').setView([43.3828500, -3.2204300], 13);

                    // Se definen las coordenadas límites de España (más o menos).
                    var spainBounds = L.latLngBounds(
                        L.latLng(36.0000, -9.3922), // Coordenada superior izquierda (Latitud, Longitud)
                        L.latLng(43.7486, 4.3273)  // Coordenada inferior derecha (Latitud, Longitud)
                    );

                    // Se añade al mapa con un zoom de 18.
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors',
                        maxZoom: 18,
                    }).addTo(map);



                    // Se indica que los límites máximos son los establecidos en spainBounds.
                    map.setMaxBounds(spainBounds); // Establecer límites máximos


                    // Variable marcador.
                    var marker;

                    // Event listener de click.
                    map.on('click', function (e) {
                        try {
                            // Control para saber si el click ha sido dentro de los límites establecidos.
                            if (!spainBounds.contains(e.latlng)) {
                                alert('Por favor, coloque puntos dentro de España.');
                                // Si no lo está, entonces se le manda una alerta.
                                return;
                            }

                            // Si existe un marcador se borra.
                            if (marker) {
                                map.removeLayer(marker);
                            }

                            // Se crea un nuevo marcador y se añade al mapa.
                            marker = L.marker(e.latlng).addTo(map);
                            // Se indica con un popup de "ubicacion seleccionada".
                            marker.bindPopup("Ubicación seleccionada").openPopup();

                            // Actualizar campos ocultos en el formulario con las coordenadas.
                            document.getElementById('lat').value = e.latlng.lat;
                            document.getElementById('lng').value = e.latlng.lng;

                            // Realizar la solicitud de geocodificación a Nominatim.
                            var url = 'https://nominatim.openstreetmap.org/reverse?lat=' + e.latlng.lat + '&lon=' + e.latlng.lng + '&format=json';
                            // Y se realiza un fetch a esa url mediante then then catch para controlar todos los errores posibles o lentitudes en la solicitud.
                            fetch(url)
                                .then(function (response) {
                                    return response.json();
                                })
                                .then(function (data) {
                                    if (data && data.address) {
                                        // Se obtienen los elementos mediante id y se rellenan con los obtenido.
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

                    // Función para buscar una dirección. Idéntica que en ver.
                    function buscarDireccion() {
                        var direccion = document.getElementById('direccion').value;

                        // Realizar la petición de geocodificación.
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

                    // Comprobar que ningún campo del formulario quede vacío, los que no pueden ser vacíos.
                    function validarFormulario() {
                        var descripcion = document.getElementById('descripcion').value;
                        var precio = document.getElementById('precio').value;
                        var latitud = parseFloat(document.getElementById('lat').value);
                        var longitud = parseFloat(document.getElementById('lng').value);

                        if (descripcion.trim() === '' || precio.trim() === '') {
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

                <form action="usuario.php" method="post" onsubmit="return validarFormulario(); guardarMarcador();">
                    <input type="hidden" name="lat" id="lat">
                    <input type="hidden" name="lng" id="lng">

                    Descripcion: <textarea name="descripcion" id="descripcion"></textarea>
                    <input type="text" id="provincia" name="provincia" placeholder="Provincia">
                    <input type="text" id="ciudad" name="ciudad" placeholder="Ciudad">
                    <input type="text" id="ubicacion" name="ubicacion" placeholder="Ubicación">
                    <input type="text" id="codigo_postal" name="codigo_postal" placeholder="Código Postal">
                    <select name="tipoPublicidad">
                        <?php
                        $conn = conectar();
                        $sql = "SELECT id_tipo_publicidad, nombre FROM tipospublicidades";
                        $resultado = mysqli_query($conn, $sql);

                        // Verificar si se obtuvieron resultados
                        if (mysqli_num_rows($resultado) > 0) {
                            // Recorrer los resultados y crear las opciones del select
                            while ($fila = mysqli_fetch_assoc($resultado)) {
                                $id = $fila['id_tipo_publicidad'];
                                $nombre = $fila['nombre'];
                                echo "<option value='$id'>$nombre</option>";
                            }
                            mysqli_close($conn);
                        } else {
                            echo "<option value=''>No hay tipos de publicidades disponibles</option>";
                        }
                        ?>
                    </select>
                    <label>Establece un precio: </label>
                    <input type="text" id="precio" name="precio" placeholder="Precio" required>


                    <button type="submit" name="guardarMarcador">Guardar</button>
                </form>

            </div>
        </div>
        <?php
    }
    if ($valor == "vigilar") {
        ?>
        <div class="flex-grow-1">
            <div class="p-3" style="display: block;">
                <h1>MISIONES</h1>
                <div id="map"></div>
                <div class="container mt-4">
                    <div class="table-responsive mb-4">
                        Misiones en proceso:
                        <table id="tabla-puntos" class="table">
                            <thead>
                                <tr>
                                    <th>Descripcion</th>
                                    <th>Latitud</th>
                                    <th>Longitud</th>
                                    <th>Prueba</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Filas de puntos se agregarán aquí dinámicamente -->
                                <?php
                                $id_usuario = $_SESSION['usuario']['id_usuario'];
                                $conn = conectar();
                                $sql = "SELECT * FROM misiones WHERE id_usuario='$id_usuario' AND estado=0";
                                $result = $conn->query($sql);




                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo '<tr>';
                                        echo '<td>' . $row['descripcion'] . '</td>'; // Columna de descripción
                                        echo '<td>' . $row['fecha_fin'] . '</td>'; // Columna de fecha_fin
                                        echo '<td>' . $row['descripcion'] . '</td>'; // Columna de descripción
                                        echo "<td><form action='vigilante.php' method='POST' enctype='multipart/form-data'>";
                                        echo "<input type='hidden' name='id_mision' value='" . $row['id_mision'] . "'>";
                                        echo "<input type='file' name='imagen' accept='image/*' required>";
                                        echo "<input type='submit' name='imagenMision'></form></td>";
                                        echo '</tr>';
                                    }
                                }


                                ?>
                            </tbody>

                        </table>
                        Misiones completadas:
                        <table id="tabla-puntos" class="table">
                            <thead>
                                <tr>
                                    <th>Descripcion</th>
                                    <th>Latitud</th>
                                    <th>Longitud</th>
                                    <th>Prueba</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Filas de puntos se agregarán aquí dinámicamente -->
                                <?php
                                $id_usuario = $_SESSION['usuario']['id_usuario'];
                                $conn = conectar();
                                $sql = "SELECT * FROM misiones WHERE id_usuario='$id_usuario' AND estado=1";
                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo '<tr>';
                                        echo '<td>' . $row['descripcion'] . '</td>'; // Columna de descripción
                                        echo '<td>' . $row['fecha_fin'] . '</td>'; // Columna de fecha_fin
                                        echo '<td>' . $row['descripcion'] . '</td>'; // Columna de descripción
                                        
                                        echo '</tr>';
                                    }
                                }


                                ?>
                            </tbody>

                        </table>
                    </div>
                </div>
                
                <style>
                    #map {
                        height: 70vh;
                        border: 8px solid #2c3e50;
                        /* Color del borde */
                        box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
                        /* Sombra */
                    }
                </style>
                <?php
                echo "<script>
                        var map = L.map('map').setView([43.3828500, -3.2204300], 13);
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: 'Map data &copy; <a href=\'https://www.openstreetmap.org/\'>OpenStreetMap</a> contributors',
                            maxZoom: 18,
                        }).addTo(map);
                    </script>";

                // Consulta para obtener los datos de publicidades y misiones
                $sql2 = "SELECT * FROM publicidades AS p, misiones AS m WHERE p.id_publicidad = m.id_publicidad";
                $result2 = $conn->query($sql2);
                if ($result2->num_rows > 0) {
                    while ($row2 = $result2->fetch_assoc()) {
                        echo "<script>
                        var marcadorLeaflet = L.marker([" . $row2['latitud'] . "," . $row2['longitud'] . "]);
                        resaltarMarcadorEnMapa(marcadorLeaflet);

                        function resaltarMarcadorEnMapa(marcador) {
                            console.log(marcador);
                            if (marcador) {
                                // Código para resaltar el marcador
                                marcador.setIcon(L.icon({
                                    iconUrl: 'https://upload.wikimedia.org/wikipedia/commons/thumb/3/39/Red_circle_frame_transparent.svg/1200px-Red_circle_frame_transparent.svg.png',
                                    iconSize: [25, 25],
                                    iconAnchor: [12, 12]
                                }));
                                
                                // Agregar el marcador resaltado al mapa
                                marcador.addTo(map);
                            }
                        }
                        </script>";
                    }
                }
                ?>
                <script>


                    // Obtener la ubicación actual del usuario
                    if ("geolocation" in navigator) {
                        navigator.geolocation.getCurrentPosition(function (position) {
                            var userLat = position.coords.latitude;
                            var userLng = position.coords.longitude;

                            // Centrar el mapa en la ubicación del usuario
                            map.setView([userLat, userLng], 13);

                            // Crear un círculo alrededor de la ubicación del usuario
                            var userCircle = L.circle([userLat, userLng], {
                                color: 'blue',
                                fillColor: 'blue',
                                fillOpacity: 0.2,
                                radius: 500 // Radio del círculo en metros
                            }).addTo(map);

                            // Obtener los límites del círculo
                            var circleBounds = userCircle.getBounds();
                            var circleBoundsNE = circleBounds.getNorthEast();
                            var circleBoundsSW = circleBounds.getSouthWest();
                            var circleBoundsLatMin = circleBoundsSW.lat;
                            var circleBoundsLngMin = circleBoundsSW.lng;
                            var circleBoundsLatMax = circleBoundsNE.lat;
                            var circleBoundsLngMax = circleBoundsNE.lng;

                            // Consultar los marcadores dentro del límite
                            // Reemplaza esta sección con el código PHP correspondiente
                            fetch('../lib/obtenerMarcadores.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({
                                    latMin: circleBoundsLatMin,
                                    lngMin: circleBoundsLngMin,
                                    latMax: circleBoundsLatMax,
                                    lngMax: circleBoundsLngMax
                                })
                            })
                                .then(function (response) {
                                    return response.json();
                                })
                                .then(function (markers) {
                                    markers.forEach(function (marker) {
                                        var lat = marker.latitud;
                                        var lng = marker.longitud;
                                        var nombreTipo = marker.nombre_tipo;
                                        var ubicacion = marker.ubicacion;
                                        var precio = marker.precio;
                                        var descripcion = marker.descripcion;
                                        var imageUrl = marker.imagen_url;

                                        // Crear un marcador para cada registro de la base de datos dentro del límite
                                        var marker = L.marker([lat, lng]).addTo(map);
                                    })
                                    console.log("MARCADORE: " + markers.length)
                                    obtenerMision(markers);
                                })
                                .catch(function (error) {
                                    console.log("Error al obtener los marcadores: " + error.message);
                                });


                        }, function (error) {
                            console.log("Error al obtener la ubicación del usuario: " + error.message);
                        });
                    } else {
                        console.log("Geolocalización no es compatible en este navegador.");
                    }


                    function obtenerMision(markers) {
                        console.log(markers);
                        var botonSolicitarMision = document.getElementById('solicitarMision');
                        botonSolicitarMision.addEventListener('click', seleccionarPunto);

                        // Función para seleccionar un punto aleatorio y resaltarlo en el mapa
                        function seleccionarPunto() {

                            // Obtener todos los marcadores en el mapa
                            var marcadores = L.layerGroup(markers); // Suponiendo que 'markers' es un array de objetos con atributos de marcadores

                            // Generar un índice aleatorio
                            var indiceAleatorio = Math.floor(Math.random() * marcadores.getLayers().length);

                            // Obtener el objeto JSON correspondiente al índice aleatorio
                            var marcadorJSON = markers[indiceAleatorio];
                            console.log("MARCADOR:" + marcadorJSON.id_publicidad);

                            // Obtener la ubicación (latitud y longitud) del marcador
                            var latitud = marcadorJSON.latitud;
                            var longitud = marcadorJSON.longitud;



                            var data = "descripcion=" + encodeURIComponent(marcadorJSON.ubicacion);
                            data += "&id_publicidad=" + encodeURIComponent(marcadorJSON.id_publicidad);

                            var url = '../lib/ejecutarMision.php?subirMision';
                            var xhr = new XMLHttpRequest();
                            xhr.open('POST', url, true);
                            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                            xhr.onreadystatechange = function () {
                                if (xhr.readyState === 4) {
                                    if (xhr.status === 200) {
                                        console.log("correcto");
                                        var resultado = xhr.responseText.trim();
                                        console.log(resultado);


                                        if (resultado === "true") {
                                            console.log("La condición es verdadera. Realizando acción A.");
                                            // Realiza la acción A
                                        }
                                        if (resultado === "false") {
                                            console.log("La condición es falsa. Realizando acción B.");
                                            // Crear una tabla con las características de la misión:
                                            // Obtener la tabla existente
                                            var tabla = document.getElementById('tabla-puntos');

                                            // Crear una nueva fila de tabla
                                            var fila = document.createElement('tr');

                                            // Crear las celdas de la fila con los datos del punto
                                            var celdaId = document.createElement('td');
                                            celdaId.textContent = marcadorJSON.ubicacion;
                                            fila.appendChild(celdaId);

                                            var celdaLatitud = document.createElement('td');
                                            celdaLatitud.textContent = latitud;
                                            fila.appendChild(celdaLatitud);

                                            var celdaLongitud = document.createElement('td');
                                            celdaLongitud.textContent = longitud;
                                            fila.appendChild(celdaLongitud);

                                            var celdaInput = document.createElement('td');
                                            
                                            fila.appendChild(celdaInput);

                                            // Agregar la fila a la tabla
                                            tabla.appendChild(fila);
                                            window.location.href = 'vigilante.php?misiones=';
                                            exit();
                                        }
                                    } else {
                                        console.log("Error en la solicitud AJAX. Estado de la respuesta: " + xhr.status);
                                    }
                                }
                            };
                            xhr.onerror = function () {
                                console.log("Error en la solicitud AJAX");
                            };
                            xhr.send(data);


                            // Crear un marcador de Leaflet utilizando la ubicación
                            var marcadorLeaflet = L.marker([latitud, longitud]);

                            // Hacer algo con el marcador seleccionado (por ejemplo, resaltarlo en el mapa)
                            resaltarMarcadorEnMapa(marcadorLeaflet);
                        }

                        // Función para resaltar un marcador en el mapa
                        function resaltarMarcadorEnMapa(marcador) {
                            console.log(marcador);
                            if (marcador) {
                                // Código para resaltar el marcador
                                marcador.setIcon(L.icon({
                                    iconUrl: 'https://upload.wikimedia.org/wikipedia/commons/thumb/3/39/Red_circle_frame_transparent.svg/1200px-Red_circle_frame_transparent.svg.png',
                                    iconSize: [25, 25],
                                    iconAnchor: [12, 12]
                                }));

                                // Agregar el marcador resaltado al mapa
                                marcador.addTo(map);
                            }
                        }
                    }


                </script>

            </div>
        </div>

        <?php

    }

}
?>