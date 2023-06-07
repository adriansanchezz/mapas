<!DOCTYPE html>
<?php
//Importar y abrir session que esta dentro de funciones.php
require_once 'lib/funciones.php';
require_once 'lib/modulos.php';
if (isset($_POST['cerrarSesion'])) {
    session_destroy(); // Destruye todas las variables de sesión
}

// Comprueba si hoy es el primer día del mes
if (date('j') == 1) {
    // Si es así, ejecuta la función
    procesarPagos();
}

sumarVisitaTotal();
?>
<html>

<head>

    <!-- Meter informacion general de head -->
    <?php head_index(); ?>
    <link href="css/principal.css" rel="stylesheet" type="text/css">
    <link href="css/index.css" rel="stylesheet" type="text/css">

</head>

<body>
    <!-- Imprimir menu del index, de forma modular sin introducir los codigos -->
    <?php menu_index(); ?>
    <br>

    <div class="container">
        <div class="row">
            <div class="col-12 col-md-6">
                <!-- Contenedor de la izquierda -->
                <h2>¿Qué es DisplayADS?</h2><br>
                <p>&emsp;&emsp;DisplayADS es una aplicación web desde la que empresas podrán acceder a un mapa de
                    lugares con
                    disponibilidad de puntos geográficos concretos,
                    estos lugares son pisos de personas que han permitido que se cuelguen carteles de publicidad en sus
                    fachadas, balcones o ventanas. De tal forma
                    que al pagar por anunciarse en un lugar concreto, parte de ese dinero lo recibirá la persona que
                    cuelga el cartel en su propiedad.</p>
                <p>&emsp;&emsp;Nuestro enfoque geográfico de inicio es una aplicación nacional, pero hay que mencionar
                    que nuestra
                    aplicación no tiene límites; puede ser tan grande
                    como una aplicación mundial, o tan pequeña como una aplicación local.</p>
                <p>&emsp;&emsp;Apoyamos y promovemos el crecimiento de las empresas locales o mundiales, para que puedan
                    darse a
                    conocer hacia las personas interesadas tanto del
                    mismo sitio o de diferentes sitios: pueblos, ciudades o países.</p>
                <p>&emsp;&emsp;Investigamos sobre las competencias directas o indirectas, al respecto tenemos
                    competencia de
                    publicidad local y exterior como los anuncios de las
                    carreteras, anuncios de las casas, y en general anuncios físicos.</p>
                <p>&emsp;&emsp;También tenemos de competencia anuncios en línea como anuncios de videos, páginas, y en
                    general
                    anuncios digitales. Nuestra idea es promocionar en
                    todos los balcones o ventanas posibles, ayudando así a promocionar a las empresas, y también
                    ayudando a los usuarios que nos ofrecen este servicio
                    a ganar algo de dinero sin hacer casi nada.</p>
            </div>
            <div class="col-12 col-md-6">
                <div class="row">
                    <div class="col-12">
                        <!-- Contenedor de la derecha -->
                        <h2 class="titulo_mapa">¿Quieres ver nuestro mapa?</h2>
                        <div id="map"></div>
                        <!-- Se le da estilos al mapa para que quede más estético. -->
                        <style>
                            #map {
                                z-index: 0;
                                height: 300px;
                                width: 375px;
                                border: 8px solid #2c3e50;
                                /* Color del borde */
                                box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
                                /* Sombra */
                            }
                        </style>
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
                        // Consultar los marcadores existentes en el mapa.
                        $sql = "SELECT * FROM publicidades";
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
                                    marker.bindPopup("<div class='popup-content'><h3><?php echo $nombre_tipo . " " . $ubicacion . " " . $precio . "€"; ?></h3><p><?php echo $descripcion; ?></p><img src='<?php echo $imageUrl; ?>' alt='Imagen de la ubicación'></div><form action='empresa.php' method='POST'><input type='hidden' name='product_id' value='<?php echo $row['id_publicidad'] ?>'><input type='hidden' name='lat' value='<?php echo $latitud; ?>'><input type='hidden' name='lng' value='<?php echo $longitud; ?>'><input type='hidden' name='ubicacion' value='<?php echo $ubicacion; ?>'><input type='hidden' name='descripcion' value='<?php echo $descripcion; ?>'></form>");
                                </script>
                                <?php
                            }
                        }
                        ?>

                    </div>
                    <br>
                    <div class="col-12">
                        <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <img src="https://www.ama-assn.org/sites/ama-assn.org/files/styles/related_article_stub_image_1200x800_3_2/public/2023-04/a23-imgs-section-meeting-rev.png?itok=oK99w0um"
                                        class="d-block w-100" alt="...">
                                </div>
                                <div class="carousel-item">
                                    <img src="https://media.licdn.com/dms/image/C4D0BAQGDceq5L-hdZg/company-logo_200_200/0/1655377951808?e=2147483647&v=beta&t=Mgd2eGPJSPpttMOpZ5Ptp8vECvMXUjrmigUxH9lPrh0"
                                        class="d-block w-100" alt="...">
                                </div>
                                <div class="carousel-item">
                                    <img src="https://www.ama-assn.org/sites/ama-assn.org/files/styles/related_article_stub_image_1200x800_3_2/public/2023-04/a23-imgs-section-meeting-rev.png?itok=oK99w0um"
                                        class="d-block w-100" alt="...">
                                </div>
                            </div>
                            <button class="carousel-control-prev" type="button"
                                data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button"
                                data-bs-target="#carouselExampleControls" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>