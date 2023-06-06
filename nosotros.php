<!DOCTYPE html>
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