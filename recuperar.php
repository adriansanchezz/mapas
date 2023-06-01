<!DOCTYPE html>
<?php
//Importar y abrir session que esta dentro de funciones.php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'lib/phpmailer/Exception.php';
require_once 'lib/phpmailer/PHPMailer.php';
require_once 'lib/phpmailer/SMTP.php';
require_once 'lib/funciones.php';
require_once 'lib/modulos.php';
?>
<html>

<head>
    <!-- Meter informacion general de head -->
    <?php head_index(); ?>
    <link href="css/login_registro.css" rel="stylesheet" type="text/css">
</head>

<body>
    <!-- Imprimir menu del index, de forma modular sin introducir los codigos -->
    <?php menu_index(); ?>
    <div class="formulario">
        <h1>Recuperación de contraseña</h1>
        <form action="recuperar.php" method="post">
            <?php


            if (!isset($_POST['continuar']) && !isset($_POST['recuperar'])) {
                ?>
                <div class="datos">
                    <input type="email" name="correo" required>
                    <label>Correo electronico</label>
                </div>

                <input type="submit" name="continuar" value="Continuar">
                <?php
                if (isset($_POST['cambiar'])) {
                    recuperarCuenta($_POST['password'],$_SESSION['recuperarCorreo']);
                }
            }
            
            if (isset($_POST['continuar'])) {
                if (validarCorreo($_POST['correo'])) {
                    $_SESSION['recuperarCorreo'] = $_POST['correo'];
                    generarCode();
                    enviarCorreo($_POST['correo']);
                    ?>
                    <div class="datos">
                        <input type="text" name="code" required>
                        <label>Codigo de recuperación</label>
                    </div>
                    <input type="submit" name="recuperar" value="Recuperar">

                    <?php
                } else {
                    ?>
                    <h3>El correo no existe.</h3>

                    <input type="submit" value="Volver">
                    <?php
                }
            }

            if (isset($_POST['recuperar'])) {
                if ($_POST['code'] == $_SESSION['recuperarCode']) {
                    ?>
                    <div class="datos">
                        <input type="password" name="password" required>
                        <label>Contraseña</label>
                    </div>
                    <input type="submit" name="cambiar" value="Cambiar contraseña">
                    <?php
                } else {
                    ?>
                    <h3>El código es incorrecto.</h3>
                    <input type="submit" value="Volver">
                    <?php
                }
            }
            ?>
        </form>
        <?php

?>
    </div>
</body>

</html>

<?php
function enviarCorreo($correo)
{
    // crear un instance; pasar `true` enables exceptions
    $mail = new PHPMailer(true);
    try {
        $code = $_SESSION['recuperarCode'];
        // Server settings
        $mail->isSMTP(); // Send using SMTP
        $mail->Host = 'smtp-mail.outlook.com'; // Set the SMTP server to send through
        $mail->SMTPAuth = true; // Enable SMTP authentication
        $mail->Username = 'displayads1@outlook.com'; // SMTP username
        $mail->Password = 'D1splayads';
        $mail->Port = 587; // TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('displayads1@outlook.com', 'DisplayAds');
        $mail->addAddress($correo); //Add a recipient

        //Content
        $mail->isHTML(true); //Set email format to HTML
        $mail->Subject = 'Code de recuperacion | DisplayAds';
        $mail->Body = "Introduce el Code para recuperar tu cuenta.<br><br>
                 Code: <b>$code</b>";
        $mail->AltBody = "Introduce el Code para recuperar tu cuenta.<br><br>
                Code: <b>$code</b>";

        $mail->send();
    } catch (Exception $e) {
        return null;
    }
}
?>