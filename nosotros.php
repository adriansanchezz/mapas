<?php
//Importar y abrir session que esta dentro de funciones.php
require_once 'lib/funciones.php';
require_once 'lib/modulos.php';
?>
<html>

<head>
    <!-- Meter informacion general de head -->
    <?php head_index(); ?>
    <title>DisplayAds</title>
</head>

<body>
    <!-- Imprimir menu del index, de forma modular sin introducir los codigos -->
    <?php menu_index(); ?>
    <br>
    <br>
    <div class="container">
        <?php
        if (isset($_REQUEST['informacion'])) {
            ?>
            <section class="bg-white">
                <div class="container">
                    <div class="row mb-3">
                        <div class="offset-lg-3 col-lg-6 text-center">
                            <h2 class="border-bottom borde-primary border-5 pb-4 pt-5">Mision y Vision</h2>
                        </div>
                    </div>
                    <div class="row align-items-center">
                        <div class="offset-lg-2 col-lg-8">
                            <div class="accordion" id="accordionExample">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingOne">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            Misión
                                        </button>
                                    </h2>
                                    <div id="collapseOne" class="accordion-collapse collapse show"
                                        aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            Somos una empresa que ofrece un servicio de aplicación web que se encarga de
                                            promocionar a grandes o
                                            pequeñas empresas desde cualquier balcón, ventana o casa de las persona que esté
                                            interesado en ofrecer
                                            su servicio a cambio de una recompensa. </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingTwo">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                            Visión
                                        </button>
                                    </h2>
                                    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                                        data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <strong>Queremos que nuestro servicio llegue a influir en cada ciudad y cada
                                                calle del mundo.</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingThree">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseThree" aria-expanded="false"
                                            aria-controls="collapseThree">
                                            Valores
                                        </button>
                                    </h2>
                                    <div id="collapseThree" class="accordion-collapse collapse"
                                        aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <strong>Honestidad:</strong> nos aseguraremos de que los usuarios que publicitan
                                            lo hagan, pagando
                                            también a ciertos usuarios de la misma zona por observar puntos concretos en los
                                            que debería haber
                                            carteles publicitarios posicionados, si no hay publicidad posicionada, lo
                                            notificarán a nuestra
                                            empresa y nos encargaremos de que se cumpla.

                                            <br><br><strong>Buen trato:</strong> ser amables con las empresas que nos
                                            contratan, atender a sus
                                            necesidades y comunicar las posibilidades y gastos del servicio que desean
                                            contratar a través de
                                            nosotros.

                                            <br><br><strong>Pasión:</strong> valoraremos la pasión dedicada hacia nuestra
                                            empresa y recompensas
                                            ofrecidas a los clientes publicitantes, recompensándolos por ciertas acciones
                                            (como la explicada en el
                                            apartado honestidad).

                                            <br><br><strong>Impacto social:</strong> valoraremos que la publicidad a
                                            posicionar en los puntos
                                            deseados por la empresa cause impacto social, y si no es así, comunicárselo a la
                                            misma, funcionando
                                            como asesores.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <?php
        }







        if (isset($_REQUEST['politicasPrivacidad'])) {
            ?>

            <div class="container mt-5">
                <h1>Política de Privacidad</h1>
                <p>Última actualización: 5 de junio de 2023</p>

                <div class="my-4">
                    <h4>1. Información que recogemos</h4>
                    <p>Para ofrecer y mejorar nuestro servicio, recogemos diferentes tipos de información, incluyendo:
                        información de ubicación, información de contacto y detalles de uso.</p>
                </div>

                <div class="my-4">
                    <h4>2. Uso de la información</h4>
                    <p>Utilizamos la información que recogemos para proporcionar y mejorar nuestro servicio, para
                        identificar y comunicarnos con usted, para responder a sus solicitudes/investigaciones y para
                        personalizar nuestro servicio.</p>
                </div>

                <div class="my-4">
                    <h4>3. Compartiendo información</h4>
                    <p>No vendemos ni compartimos su información personal con terceros para sus propósitos de marketing
                        directo a menos que usted nos dé permiso. Podemos compartir su información con terceros para el
                        propósito de proporcionar o mejorar nuestro servicio para usted.</p>
                </div>

                <div class="my-4">
                    <h4>4. Seguridad</h4>
                    <p>La seguridad de su información es importante para nosotros. Implementamos una serie de medidas de
                        seguridad para ayudar a mantener segura su información.</p>
                </div>

                <div class="my-4">
                    <h4>5. Cambios a esta política</h4>
                    <p>Podemos actualizar nuestra política de privacidad de vez en cuando. Le notificaremos de cualquier
                        cambio publicando la nueva política de privacidad en esta página.</p>
                </div>

                <div class="my-4">
                    <h4>6. Contacto</h4>
                    <p>Si tiene alguna pregunta sobre nuestra política de privacidad, o si desea hacer una queja sobre cómo
                        manejamos su información personal, puede ponerse en contacto con nosotros en: [email de la empresa]
                    </p>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</body>

</html>