<!DOCTYPE html>
<?php
//Importar y abrir session que esta dentro de funciones.php
require_once '../lib/funciones.php';
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

</head>

<body>
    <?php
    if (isset($_SESSION['usuario'])) {
        // Menu general
        menu_general();
        ?>

        <div class="container">
            <div class="row">

                <!-- Banner -->
                <section class="banner">
                    <div class="container pt-5">
                        <div class="row align-items-center justify-content-between">
                            <div class="col-lg-6 col-md-12 text-center mb-5">
                                <div class="banner-content">
                                    <h1 class="fw-boolder margin-bottom:1rem">Display Ads</h1>
                                    <?php
                                    if (validarEmpresa($_SESSION['usuario']['id_usuario'])) {
                                        ?>
                                        <h4 class="border-bottom borde-primary border-5 pb-4">¿Quiero consultar algo?</h4>
                                        <a href="mailto:displayads@protonmail.com" target="_black"
                                            class="btn btn-primary text-uppercese ft-3 fw-bolder">Contactar</a>
                                        <?php
                                    } else {
                                        ?>
                                        <h4 class="border-bottom borde-primary border-5 pb-4">¿Eres una entidad empresarial y
                                            quieres formar parte de nuestra aplicación?</h4>
                                        <a href="soporte.php?solicitarEmpresa" target="_black"
                                            class="btn btn-primary text-uppercese ft-3 fw-bolder">Solicitar</a>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="col">
                                <div class="col-12 d-flex justify-content-center">
                                    <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                                        <div class="carousel-inner">
                                            <div class="carousel-item active">
                                                <img src="../img/imagen/mapaempresa.png"
                                                    class="d-block w-100" alt="...">
                                            </div>
                                            <div class="carousel-item">
                                                <img src="../img/imagen/mapausuario.png"
                                                    class="d-block w-100" alt="...">
                                            </div>
                                            <div class="carousel-item">
                                                <img src="../img/imagen/mapavigilante.png"
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
        </div>

        <?php

    } else {
        echo ('Acceso denegado');
        print '<a href ="../index.php"><button>Volver</button></a>';
        session_destroy();
    }
    ?>
</body>

</html>