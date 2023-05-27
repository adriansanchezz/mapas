<!DOCTYPE html>
<?php
//Importar y abrir session que esta dentro de functiones.php
require_once '../lib/functiones.php';
require_once '../lib/modulos.php';
?>
<html>

<head>
    <!-- Meter informacion general de head -->
    <?php head_info(); ?>
    <title>DisplayAds</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        /* Mantener la relación de aspecto de las imágenes */
        .carousel-inner img {
            object-fit: contain;
            height: 250px;
        }

        .carousel {
            width: 40vh;
        }
    </style>
</head>

<body>
    <?php
    if (isset($_SESSION['usuario'])) {
        // Menu general
        menu_general();

        if (validarUsuario($_SESSION['usuario']['id_usuario'])) {
            echo "Usuario SI";
        } else {
            echo "Usuario NO";
        }
        echo "<br>";

        if (validarAdmin($_SESSION['usuario']['id_usuario'])) {
            echo "Admin SI";
        } else {
            echo "Admin NO";
        }
        echo "<br>";

        if (validarEmpresa($_SESSION['usuario']['id_usuario'])) {
            echo "Empresa SI";
        } else {
            echo "Empresa NO";
        }
        echo "<br>";

        if (validarVIP($_SESSION['usuario']['id_usuario'])) {
            echo "VIP SI";
        } else {
            echo "VIP NO";
        }
        echo "<br>";

        if (validarVigilante($_SESSION['usuario']['id_usuario'])) {
            echo "Vigilante SI";
        } else {
            echo "Vigilante NO";
        }

    } else {
        echo ('Acceso denegado');
        print '<a href ="../index.php"><button>Volver</button></a>';
        session_destroy();
    }
    ?>
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <!-- Contenedor de la izquierda -->
                <h2>¿Qué es DisplayADS?</h2>
                <p>DisplayADS es una aplicación web desde la que empresas podrán acceder a un mapa de lugares con
                    disponibilidad de puntos geográficos concretos,
                    estos lugares son pisos de personas que han permitido que se cuelguen carteles de publicidad en sus
                    fachadas, balcones o ventanas. De tal forma
                    que al pagar por anunciarse en un lugar concreto, parte de ese dinero lo recibirá la persona que
                    cuelga el cartel en su propiedad.</p>
                <br>
                <p>Nuestro enfoque geográfico de inicio es una aplicación nacional, pero hay que mencionar que nuestra
                    aplicación no tiene límites; puede ser tan grande
                    como una aplicación mundial, o tan pequeña como una aplicación local.</p>
                <br>
                <p>Apoyamos y promovemos el crecimiento de las empresas locales o mundiales, para que puedan darse a
                    conocer hacia las personas interesadas tanto del
                    mismo sitio o de diferentes sitios: pueblos, ciudades o países.</p>
                <br>
                <p>Investigamos sobre las competencias directas o indirectas, al respecto tenemos competencia de
                    publicidad local y exterior como los anuncios de las
                    carreteras, anuncios de las casas, y en general anuncios físicos.</p>
                <br>
                <p>También tenemos de competencia anuncios en línea como anuncios de videos, páginas, y en general
                    anuncios digitales. Nuestra idea es promocionar en
                    todos los balcones o ventanas posibles, ayudando así a promocionar a las empresas, y también
                    ayudando a los usuarios que nos ofrecen este servicio
                    a ganar algo de dinero sin hacer casi nada.</p>
            </div>

            <div class="col-md-3">
                <!-- Contenedor de la derecha -->
                <h2>CARROUSEL DE IMAGENES SOBRE NUESTRA APLICACION</h2>
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
                <br><br><br>
                <!-- Contenedor de la derecha -->
                <h2>¿Eres una entidad empresarial y quieres formar parte de nuestra aplicación?</h2>
                <div>
                    <form>
                        <input type="button" value="CLICK AQUÍ"
                            style="font-size: 20px; background-color: blue; border: none; color: white; padding: 10px 20px; border-radius: 5px;">
                    </form>
                </div>



            </div>
        </div>
</body>

</html>