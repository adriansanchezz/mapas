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

            <!-- Banner -->
            <section class="banner">
                <div class="container pt-5">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-lg-6 col-md-12 text-center mb-5">
                            <div class="banner-content">
                                <h1 class="fw-boolder margin-bottom:1rem">Display Ads</h1>
                                <h4 class="border-bottom borde-primary border-5 pb-4">¿Quiero consultar algo?</h4>
                                <a href="mailto:displayads@protonmail.com" target="_black"
                                    class="btn btn-primary text-uppercese ft-3 fw-bolder">Contactar</a>
                            </div>
                        </div>
                        <div class="col">
                            <!-- Contenedor de la derecha -->
                            <div id="map"></div>
                        </div>
                    </div>
                </div>
            </section>

            <hr class="linea">
            <!-- descripcion -->
            <section>
                <div class="container">
                    <div class="row mb-3">
                        <div class="offset-lg-3 col-lg-6 text-center">
                            <h2 class="border-bottom borde-primary border-4 pb-3 pt-5">¿Qué es DisplayADS?</h2>
                        </div>
                    </div>

                    <div class="row align-items-center">
                        <div class="col-lg-8 offset-lg-2 col-md-8">
                            <p>DisplayADS es una aplicación web desde la que empresas podrán acceder a un mapa de
                                lugares con
                                disponibilidad de puntos geográficos concretos, estos lugares son pisos de personas que
                                han
                                permitido que se cuelguen carteles de publicidad en sus
                                fachadas, balcones o ventanas. De tal forma
                                que al pagar por anunciarse en un lugar concreto, parte de ese dinero lo recibirá la
                                persona que
                                cuelga el cartel en su propiedad.</p>
                            <p>Nuestro enfoque geográfico de inicio es una aplicación nacional, pero hay que mencionar
                                que nuestra
                                aplicación no tiene límites; puede ser tan grande
                                como una aplicación mundial, o tan pequeña como una aplicación local.</p>
                            <p>Apoyamos y promovemos el crecimiento de las empresas locales o mundiales, para que puedan
                                darse a conocer hacia las personas interesadas tanto del
                                mismo sitio o de diferentes sitios: pueblos, ciudades o países.</p>
                            <p>Investigamos sobre las competencias directas o indirectas, al respecto tenemos
                                competencia de
                                publicidad local y exterior como los anuncios de las
                                carreteras, anuncios de las casas, y en general anuncios físicos.</p>
                            <p>También tenemos de competencia anuncios en línea como anuncios de videos, páginas, y en
                                general
                                anuncios digitales. Nuestra idea es promocionar en
                                todos los balcones o ventanas posibles, ayudando así a promocionar a las empresas, y
                                también
                                ayudando a los usuarios que nos ofrecen este servicio
                                a ganar algo de dinero sin hacer casi nada.</p>
                        </div>
                    </div>
                </div>
            </section>

            <hr class="linea">

            <div class="col-12 d-flex justify-content-center">
                <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="./img/imagen/mapaempresa.png" class="d-block w-100" alt="...">
                        </div>
                        <div class="carousel-item">
                            <img src="./img/imagen/mapausuario.png" class="d-block w-100" alt="...">
                        </div>
                        <div class="carousel-item">
                            <img src="./img/imagen/mapavigilante.png" class="d-block w-100" alt="...">
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-light container-fluid text-center mt-5">
        <div class="pt-2 pb-2">
            &copy;Display Ads
        </div>
    </footer>




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
            marker.bindPopup("<div class='popup-content'><h3><?php echo $nombre_tipo . " " . $ubicacion . " " . $precio . "€"; ?></h3><p><?php echo $descripcion; ?></p><form action='empresa.php' method='POST'><input type='hidden' name='product_id' value='<?php echo $row['id_publicidad'] ?>'><input type='hidden' name='lat' value='<?php echo $latitud; ?>'><input type='hidden' name='lng' value='<?php echo $longitud; ?>'><input type='hidden' name='ubicacion' value='<?php echo $ubicacion; ?>'><input type='hidden' name='descripcion' value='<?php echo $descripcion; ?>'></form>");
        </script>
        <?php
    }
}
?>


</html>