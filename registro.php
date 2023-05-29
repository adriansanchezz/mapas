<!DOCTYPE html>
<?php
//Importar y abrir session que esta dentro de functiones.php
require_once 'lib/functiones.php';
require_once 'lib/modulos.php';
?>
<html>

<head>
    <!-- Meter informacion general de head -->
    <?php head_info(); ?>
    <link href="css/login_registro.css" rel="stylesheet" type="text/css">
    <title>DisplayAds</title>
</head>

<body>
    <!-- Imprimir menu del index, de forma modular sin introducir los codigos -->
    <?php menu_index(); ?>
    <div class="formulario">
        <h1>Registrate</h1>
        <form action="registro.php" method="post">
            <div class="datos">
                <input type="text" name="username" required>
                <label>Nombre de usuario</label>
            </div>

            <div class="datos">
                <input type="email" name="correo" required>
                <label>Correo electronico</label>
            </div>

            <div class="datos">
                <input type="tel" name="telefono" required>
                <label>Numero de telefono</label>
            </div>

            <div class="datos">
                <input type="password" name="password" required>
                <label>Contraseña</label>
            </div>

            <div class="datos">
                <input type="password" name="password2" required>
                <label>Repite la contraseña</label>
            </div>
            <div>Al registrarte, aceptas nuestras Condiciones de uso y Políticas de privacidad.</div><br>

            <input type="submit" name="registrar" value="Registrarse">

            <div class="redirigir">
                ¿Ya tienes una cuenta? <a href="login.php">Iniciar Sesion</a>
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