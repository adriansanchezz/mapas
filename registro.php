<!DOCTYPE html>
<?php
//Importar y abrir session que esta dentro de funciones.php
require_once 'lib/funciones.php';
require_once 'lib/modulos.php';
?>
<html>

<head>
    <!-- Meter informacion general de head -->
    <?php head_info(); ?>
    <link href="css/login_registro.css" rel="stylesheet" type="text/css">
    <script src="./js/funciones.js"></script>
    <style>
        .error {
            color: red;
        }
    </style>
</head>

<body>
    <!-- Imprimir menu del index, de forma modular sin introducir los codigos -->
    <?php menu_index(); ?>
    <div class="formulario">
        <h1>Registrate</h1>
        <form action="registro.php" method="post" onsubmit="return validarFormulario()" id="formularioRegistro">
            <div class="datos">
                <input type="text" name="username" id="username" required>
                <label>Nombre de usuario</label>
                <span id="errorUsername" class="error"></span>
            </div>

            <div class="datos">
                <input type="email" name="correo" id="correo" required>
                <label>Correo electrónico</label>
                <span id="errorCorreo" class="error"></span>
            </div>

            <div class="datos">
                <input type="tel" name="telefono" id="telefono" required>
                <label>Número de teléfono</label>
                <span id="errorTelefono" class="error"></span>
            </div>

            <div class="datos">
                <input type="password" name="password" id="password" required>
                <label>Contraseña</label>
                <span id="errorPassword" class="error"></span>
            </div>

            <div class="datos">
                <input type="password" name="password2" id="password2" required>
                <label>Repite la contraseña</label>
                <span id="errorPassword2" class="error"></span>
            </div>

            <div class="redirigir">Al registrarte, aceptas nuestras Condiciones de uso y <a
                    href="nosotros.php?politicasPrivacidad">Políticas de privacidad</a>.</div>

            <input type="submit" name="registrar" value="Registrarse">

            <div class="redirigir">
                ¿Ya tienes una cuenta? <a href="login.php">Iniciar Sesión</a>
            </div>

            
            <?php
            if (isset($_POST['registrar'])) {
                registrarUser($_POST['username'], $_POST['correo'], $_POST['telefono'], $_POST['password'], $_POST['password2']);
            }
            ?>
        </form>


    </div>

</body>

</html>