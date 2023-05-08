<!DOCTYPE html>
<?php
require_once 'lib/functiones.php';
require_once 'lib/modulos.php';
?>
<html>
    <head>
        <?php head_info(); ?>
        <title>DisplayAds</title>
    </head>
    <body>
        <!-- Imprimir menu del index, de forma modular sin introducir los codigos -->
        <?php menu_index(); ?>
        <br>
        <br>
        <div class="container">
            <div class="row">
                <div class="col-md-9">
                    <!-- Contenedor de la izquierda -->
                    <h2>¿Qué es DisplayADS?</h2>
                    <p>DisplayADS es una aplicación web desde la que empresas podrán acceder a un mapa de lugares con disponibilidad de puntos geográficos concretos, 
                        estos lugares son pisos de personas que han permitido que se cuelguen carteles de publicidad en sus fachadas, balcones o ventanas. De tal forma 
                        que al pagar por anunciarse en un lugar concreto, parte de ese dinero lo recibirá la persona que cuelga el cartel en su propiedad.</p>
                    <br>
                    <p>Nuestro enfoque geográfico de inicio es una aplicación nacional, pero hay que mencionar que nuestra aplicación no tiene límites; puede ser tan grande 
                        como una aplicación mundial, o tan pequeña como una aplicación local.</p>
                    <br>
                    <p>Apoyamos y promovemos el crecimiento de las empresas locales o mundiales, para que puedan darse a  conocer hacia las personas interesadas tanto del 
                        mismo sitio o de diferentes sitios: pueblos, ciudades o países.</p>
                    <br>
                    <p>Investigamos sobre las competencias directas o indirectas, al respecto tenemos competencia de publicidad local y exterior como los anuncios de las 
                        carreteras, anuncios de las casas, y en general anuncios físicos.</p>
                    <br>
                    <p>También tenemos de competencia anuncios en línea como anuncios de videos, páginas, y en general anuncios digitales. Nuestra idea es promocionar en 
                        todos los balcones o ventanas posibles, ayudando así a promocionar a las empresas, y también ayudando a los usuarios que nos ofrecen este servicio 
                        a ganar algo de dinero sin hacer casi nada.</p>
                </div>
                <div class="col-md-3">
                    <!-- Contenedor de la derecha -->
                    <h2>CARROUSEL DE IMAGENES SOBRE NUESTRA APLICACION</h2>
                    <div id="myCarousel" class="carousel slide" data-bs-ride="carousel">
                        <!-- Indicadores de las diapositivas -->
                        <ol class="carousel-indicators">
                            <li data-bs-target="#myCarousel" data-bs-slide-to="0" class="active"></li>
                            <li data-bs-target="#myCarousel" data-bs-slide-to="1"></li>
                            <li data-bs-target="#myCarousel" data-bs-slide-to="2"></li>
                        </ol>

                        <!-- Diapositivas -->
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="img1.jpg" class="d-block w-100" alt="Imagen 1">
                            </div>
                            <div class="carousel-item">
                                <img src="img2.jpg" class="d-block w-100" alt="Imagen 2">
                            </div>
                            <div class="carousel-item">
                                <img src="img3.jpg" class="d-block w-100" alt="Imagen 3">
                            </div>
                        </div>

                        <!-- Controles del carrusel -->
                        <a class="carousel-control-prev" href="#myCarousel" role="button" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Anterior</span>
                        </a>
                        <a class="carousel-control-next" href="#myCarousel" role="button" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Siguiente</span>
                        </a>
                    </div>
                </div>
            </div>
    </body>
</html>
