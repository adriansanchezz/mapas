<?php

//Función de mapa al que se le pasa un valor y dependiendo de este se realiza una u otra función.
function mapa($valor)
{
    // Si el valor es ver, entonces la función irá enfocada a ver el mapa y los puntos creados por el usuario.
    // Esta función está enfocada al menú de empresa.
    if ($valor == "ver") {
        ?>
        <script>
            // Creación del mapa.
            var map = L.map('map').setView([43.3828500, -3.2204300], 13);

            // Selección de cuanto zoom tendrá y más atributos necesarios.
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors',
                maxZoom: 18,
            }).addTo(map);
        </script>
        <?php
        // Establecer la conexión con la base de datos.
        // Consultar los marcadores existentes en el mapa.
        $sql = "SELECT * FROM publicidades WHERE (revision IS NULL OR revision = 1) AND estado = 1 AND ocupado = 0 AND comprador IS NULL AND id_usuario <> " . $_SESSION['usuario']['id_usuario'];

        $result = sqlSELECT($sql);

        // Si da resultados entonces entra en el if.
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Se crean variables con los datos de la consulta que interesa sacar en pantalla u operar con ellos.
                $latitud = $row['latitud'];
                $longitud = $row['longitud'];
                $descripcion = $row['descripcion'];
                $ubicacion = $row['ubicacion'];
                $precio = $row['precio'];
                $sql3 = "SELECT * FROM fotos WHERE id_publicidad =" . $row['id_publicidad'];
                $result3 = sqlSELECT($sql3);
                $mostrarImagen = '';
                if ($result3->num_rows > 0) {
                    // Recuperar la información de la imagen
                    $row3 = $result3->fetch_assoc();
                    $imagen = $row3["foto"];
                    // Mostrar la imagen en la página
                    $mostrarImagen = "<img src='data:image/jpeg;base64," . base64_encode($imagen) . "' alt='Imagen del producto'>";
                }
                // Se obtiene la id del tipo de propiedad.
                $tipo = $row['id_tipo_publicidad'];

                // Y mediante una consulta a la tabla tipospropiedades se obtiene el nombre del tipo de propiedad que es.
                $sql2 = "SELECT nombre FROM tipospublicidades WHERE id_tipo_publicidad = $tipo";
                $result2 = sqlSELECT($sql2);

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
                <script>
                    // Crear un marcador para cada registro de la base de datos.
                    var marker = L.marker([<?php echo $latitud; ?>, <?php echo $longitud; ?>]).addTo(map);
                    // Se añade un popUp para que salga una ventana al clickar un marcador existente en el mapa.
                    var marker = L.marker([<?php echo $latitud; ?>, <?php echo $longitud; ?>]).addTo(map);
                    // Se añade un popUp para que salga una ventana al clickar un marcador existente en el mapa.
                    marker.bindPopup(`
                        <div class='popup-content'>
                        <h3><?php echo $nombre_tipo . " " . $ubicacion . " " . $precio . "€"; ?></h3>
                        <p><?php echo $descripcion; ?></p>
                        <img src='<?php echo $imageUrl; ?>' alt='Imagen de la ubicación'>
                        </div>
                        <form action='empresa.php' method='POST'>
                        <input type='hidden' name='product_id' value='<?php echo $row['id_publicidad'] ?>'>
                        <input type='hidden' name='lat' value='<?php echo $latitud; ?>'>
                        <input type='hidden' name='lng' value='<?php echo $longitud; ?>'>
                        <input type='hidden' name='ubicacion' value='<?php echo $ubicacion; ?>'>
                        <input type='hidden' name='descripcion' value='<?php echo $descripcion; ?>'>
                        Imagen Google: 
                        Imagen usuario <?php echo $mostrarImagen; ?>
                        <input type='hidden' name='precio' value='<?php echo $precio; ?>'>
                        <?php
                        $sql = "SELECT * FROM pedidos as p, lineas_pedidos as lp WHERE p.id_pedido = lp.id_pedido AND p.fecha_fin IS NULL AND p.id_usuario = " . $_SESSION['usuario']['id_usuario'] . " AND lp.id_publicidad = " . $row['id_publicidad'] . " AND lp.cantidad > 0;";
                        if (sqlSELECT($sql)->num_rows > 0) {
                            echo "<p style='color: red;'>YA SELECCIONADA</p>";
                        }
                        ?>
                        <button type='submit' name='add_to_cart' value='1'>Seleccionar</button>
                        </form>
                        `);
                </script>
                <?php
            }
        }
        ?>


        <?php
    }
    // Apartado para guardar un marcador de una ubicación. Esto está centralizado hacia el menú de usuario.
    if ($valor == "guardar") {
        ?>
        <script>
            // Se crea el mapa.
            var map = L.map('map').setView([43.3828500, -3.2204300], 7);

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

        </script>
        <?php
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
                        $mostrarImagen = "<img src='data:image/jpeg;base64," . base64_encode($imagen) . "' alt='Imagen del producto'>";
                    }

                    // La api key de google. Para poder usar el google static map.
                    $apiKey = 'AIzaSyADr5gpzLPePzkWwz8C94wBQ21DzQ4GGVU'; // Reemplaza con tu propia API Key de Google Maps Static

                    // Se obtiene una imagen de la localización mediante coordenadas.
                    $imageUrl = 'https://maps.googleapis.com/maps/api/streetview?size=400x300&location=' . $latitud . ',' . $longitud . '&key=' . $apiKey;
                    ?>
                    <script>
                        // Crear un marcador para cada registro de la base de datos.
                        var marker = L.marker([<?php echo $latitud; ?>, <?php echo $longitud; ?>]).addTo(map);
                        // Se añade un popUp para que salga una ventana al clickar un marcador existente en el mapa.
                        marker.bindPopup(`<style>img{height: 200px;}</style><div class='popup-content'>
                                                                <h3 class='popup-title'><?php echo $nombre_tipo; ?></h3>
                                                                <h4 class='popup-location'><?php echo $ubicacion; ?></h4>
                                                                <h4 class='popup-price'><?php echo $precio . '€'; ?></h4>
                                                                <p class='popup-description'><?php echo $descripcion; ?></p>
                                                                Imagen Google: <img class='popup-image' src='<?php echo $imageUrl; ?>' alt='Imagen de la ubicación'>
                                                                Imagen usuario <?php echo $mostrarImagen; ?></div>
                                                                <form action='usuario.php' method='POST'>
                                                                <input type='hidden' name='id_publicidad' value='<?php echo $row['id_publicidad']; ?>'>
                                                                <?php
                                                                $conn = conectar();
                                                                $sql = "SELECT * FROM publicidades as p, empresas as em WHERE p.ocupado = 1 AND p.estado = 1 AND p.comprador IS NOT NULL AND p.comprador = em.id_empresa AND p.id_publicidad = " . $row['id_publicidad'];

                                                                $resultado = $conn->query($sql);

                                                                if ($resultado->num_rows > 0) {
                                                                    // Si se obtienen resultados, se recorren las filas
                                                                    $row4 = $resultado->fetch_assoc();

                                                                    // Mostrar el mensaje de publicidad vendida
                                                                    echo "<p style='color: red;'>YA VENDIDA A " . $row4['nombre'] . "</p>";
                                                                }
                                                                ?>

                                                                <button class='popup-delete-button' type='submit' name='borrarMarcador'>Borrar</button>
                                                            </form>`);
                    </script>
                    <?php
                }
            }
        }
        // Se cierra la conexión de la BD.
        mysqli_close($conn);
        ?>
        <script>
            // Se indica que los límites máximos son los establecidos en spainBounds.
            map.setMaxBounds(spainBounds); // Establecer límites máximos

            // Permitir arrastrar el mapa fuera de los límites para navegar
            map.on('drag', function () {
                map.panInsideBounds(spainBounds, { animate: false });
            });

            // Permitir hacer zoom fuera de los límites para navegar
            map.on('zoomend', function () {
                if (!spainBounds.contains(map.getCenter())) {
                    map.setView([43.3828500, -3.2204300], 13);
                }
            });


            // Variable marcador.
            var marker2;

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
                    if (marker2) {
                        map.removeLayer(marker2);
                    }

                    // Se crea un nuevo marcador y se añade al mapa.
                    marker2 = L.marker(e.latlng).addTo(map);
                    var apiKey = 'AIzaSyADr5gpzLPePzkWwz8C94wBQ21DzQ4GGVU'; // Reemplaza con tu propia API Key de Google Maps Static

                    var img = new Image();
                    img.src = 'https://maps.googleapis.com/maps/api/streetview?size=400x300&location=' + e.latlng.lat + ',' + e.latlng.lng + '&key=' + apiKey;


                    marker2.bindPopup("<img src='" + img.src + "' width='400' height='300' id='imagenPopUp'><br>Ubicación seleccionada").openPopup();

                    // Actualizar campos ocultos en el formulario con las coordenadas.
                    document.getElementById('lat').value = e.latlng.lat;
                    document.getElementById('lng').value = e.latlng.lng;
                    var apiKey = 'AIzaSyADr5gpzLPePzkWwz8C94wBQ21DzQ4GGVU'; // Reemplaza con tu propia API Key de Google Maps Static


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



            function validarFormulario() {
                var descripcion = document.getElementById('descripcion').value;
                var precio = document.getElementById('precio').value;
                var provincia = document.getElementById('provincia').value;
                var ciudad = document.getElementById('ciudad').value;
                var ubicacion = document.getElementById('ubicacion').value;
                var codigoPostal = document.getElementById('codigo_postal').value;

                if (descripcion.trim() === '' || precio.trim() === '' || provincia.trim() === '' || ciudad.trim() === '' || ubicacion.trim() === '') {
                    alert('Los campos de título, texto, provincia, ciudad y ubicación no pueden estar vacíos.');
                    return false;
                }

                if (isNaN(parseFloat(precio))) {
                    alert('El precio debe ser un número.');
                    return false;
                }

                if (isNaN(parseInt(codigoPostal))) {
                    alert('El código postal debe ser un número.');
                    return false;
                }

                var textoRegex = /^[A-Za-zÁáÉéÍíÓóÚúÑñ\s]+$/;

                if (!textoRegex.test(provincia) || !textoRegex.test(ciudad) || !textoRegex.test(ubicacion)) {
                    alert('Los campos de descripción, provincia, ciudad y ubicación deben contener solo letras.');
                    return false;
                }

                var latitud = parseFloat(document.getElementById('lat').value);
                var longitud = parseFloat(document.getElementById('lng').value);

                if (isNaN(latitud) || isNaN(longitud)) {
                    alert('Debe seleccionar una ubicación en el mapa.');
                    return false;
                }

                return true;
            }

        </script>


        <div class="col-md-3">
            <form action="usuario.php" method="POST" enctype="multipart/form-data"
                onsubmit="return validarFormulario(); guardarMarcador();">
                <input type="hidden" name="lat" id="lat">
                <input type="hidden" name="lng" id="lng">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="descripcion">Descripción:</label>
                            <textarea class="form-control" name="descripcion" id="descripcion" placeholder="Ej: N13, 3ºIZQ"
                                required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="provincia">Provincia:</label>
                            <input type="text" class="form-control" id="provincia" name="provincia" placeholder="Provincia"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="ciudad">Ciudad:</label>
                            <input type="text" class="form-control" id="ciudad" name="ciudad" placeholder="Ciudad" required>
                        </div>
                        <div class="form-group">
                            <label for="ubicacion">Ubicación:</label>
                            <input type="text" class="form-control" id="ubicacion" name="ubicacion" placeholder="Ubicación"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="codigo_postal">Código Postal:</label>
                            <input type="text" class="form-control" id="codigo_postal" name="codigo_postal"
                                placeholder="Código Postal" required>
                        </div>
                        <div class="form-group">
                            <label for="tipoPublicidad">Tipo de Publicidad:</label>
                            <select class="form-control" name="tipoPublicidad" id="tipoPublicidad" required>
                                <?php
                                $sql = "SELECT id_tipo_publicidad, nombre FROM tipospublicidades";
                                $resultado = sqlSELECT($sql);
                                if (mysqli_num_rows($resultado) > 0) {
                                    while ($fila = mysqli_fetch_assoc($resultado)) {
                                        $id = $fila['id_tipo_publicidad'];
                                        $nombre = $fila['nombre'];
                                        echo "<option value='$id'>$nombre</option>";
                                    }
                                } else {
                                    echo "<option value=''>No hay tipos de publicidades disponibles</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="precio">Establece un precio:</label>
                            <input type="text" class="form-control" id="precio" name="precio" placeholder="Precio" required>
                        </div>
                        <div class="form-group">
                            <label for="imagen" id="imagensubir">Sube una foto:</label>
                            <span id="mensajePubli" style="display: block; color: red;">(*) Recuerda subir la foto del lugar en
                                el que publicitarás.</span>
                            <span id="mensajePiso" style="display: none; color: red;">(*) Recuerda subir un papel certificado de
                                la comunidad de vecinos y la foto del lugar en el que publicitarás.</span>
                            <input type="file" name="imagen[]" multiple>
                        </div>

                        <button type="submit" class="btn btn-primary" name="guardarMarcador">Guardar</button>
                    </div>
                </div>
            </form>
        </div>
        <script>
            document.getElementById("tipoPublicidad").addEventListener("change", function () {
                var seleccionado = this.options[this.selectedIndex].text;
                var mensajePiso = document.getElementById("mensajePiso");
                var mensajePubli = document.getElementById("mensajePubli");
                if (seleccionado === "Piso") {
                    mensajePiso.style.display = "block";
                    mensajePubli.style.display = "none";
                } else {
                    mensajePiso.style.display = "none";
                    mensajePubli.style.display = "block";
                }
            });
        </script>

        <?php
    }
    if ($valor == "vigilar") {
        ?>

        <h1>MISIONES</h1>
        <div id="map"></div><br>
        <style>
            #solicitarMision {
                margin-left: 10vh;
                width: 150vh;
            }
        </style>
        <input type="submit" class="btn btn-primary" id="solicitarMision" value="Solicitar misión">
        <div class="container mt-4">
            <div class="table-responsive mb-4">
                Misiones en proceso:
                <table id="tabla-puntos" class="table">
                    <thead>
                        <tr>
                            <th>Ubicación</th>
                            <th>Código Postal</th>
                            <th>Descripción</th>
                            <th>Misión</th>
                            <th>Prueba</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Filas de puntos se agregarán aquí dinámicamente -->
                        <?php
                        $id_usuario = $_SESSION['usuario']['id_usuario'];
                        $sql = "SELECT m.descripcion AS mision_descripcion, p.descripcion AS publicidad_descripcion, p.codigo_postal, p.ubicacion, m.id_mision
                                        FROM misiones AS m, publicidades AS p
                                        WHERE m.id_usuario='$id_usuario' AND m.estado=0 AND m.aceptacion=0 AND m.id_publicidad = p.id_publicidad";
                        $result = sqlSELECT($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo '<tr>';
                                echo '<td>' . $row['ubicacion'] . '</td>'; // Columna de descripción de misiones
                                echo '<td>' . $row['codigo_postal'] . '</td>'; // Columna de código postal de misiones
                                echo '<td>' . $row['publicidad_descripcion'] . '</td>'; // Columna de descripción de publicidades
                                echo '<td>' . $row['mision_descripcion'] . '</td>'; // Columna de descripción de publicidades
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
                            <th>Ubicación</th>
                            <th>Código Postal</th>
                            <th>Descripción</th>
                            <th>Misión</th>
                            <th>¿Aprobada?</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Filas de puntos se agregarán aquí dinámicamente -->
                        <?php
                        $id_usuario = $_SESSION['usuario']['id_usuario'];
                        $sql = "SELECT m.descripcion AS mision_descripcion, p.descripcion AS publicidad_descripcion, p.ubicacion, m.aceptacion, p.codigo_postal
                                        FROM misiones AS m, publicidades AS p
                                        WHERE m.id_usuario='$id_usuario' AND m.estado=1 AND m.id_publicidad = p.id_publicidad";
                        $result = sqlSELECT($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo '<tr>';
                                echo '<td>' . $row['ubicacion'] . '</td>'; // Columna de descripción de misiones
                                echo '<td>' . $row['codigo_postal'] . '</td>'; // Columna de código postal de misiones
                                echo '<td>' . $row['publicidad_descripcion'] . '</td>'; // Columna de descripción de publicidades
                                echo '<td>' . $row['mision_descripcion'] . '</td>'; // Columna de descripción de publicidades
                                if ($row['aceptacion'] == 0) {
                                    echo '<td>EN ESPERA...</td>'; // Columna de descripción de publicidades
                                }
                                if ($row['aceptacion'] == 1) {
                                    echo '<td>APROBADA</td>'; // Columna de descripción de publicidades
                                }
                                if ($row['aceptacion'] == 2) {
                                    echo '<td>RECHAZADA</td>'; // Columna de descripción de publicidades
                                }
                                echo '</tr>';
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>


        <?php
        echo "<script>
                        var map = L.map('map').setView([43.3828500, -3.2204300], 13);
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: 'Map data &copy; <a href=\'https://www.openstreetmap.org/\'>OpenStreetMap</a> contributors',
                            maxZoom: 18,
                        }).addTo(map);
                    </script>";

        // Consulta para obtener los datos de publicidades y misiones
        $sql2 = "SELECT * FROM publicidades AS p, misiones AS m WHERE p.id_publicidad = m.id_publicidad AND p.estado = 1 AND p.ocupado = 1 AND p.caducidad_compra IS NOT NULL AND m.aceptacion = 0 AND m.id_usuario =" . $_SESSION['usuario']['id_usuario'] . ";";
        $result2 = sqlSELECT($sql2);
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
                            });
                            console.log("MARCADORE: " + markers.length);
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

                    var data = "descripcion=" + encodeURIComponent("Ve al lugar y saca una foto");
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
        <?php
    }
}
?>