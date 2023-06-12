<?php
//Importar y abrir session que esta dentro de funciones.php
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
        <h1>Iniciar sesion</h1>
        <form action="login.php" method="post">
            <div class="datos">
                <input type="email" name="correo" required>
                <label>Correo electronico</label>
            </div>

            <div class="datos">
                <input type="password" name="password" required>
                <label>Contraseña</label>
            </div>

            <input type="submit" name="iniciar" value="Iniciar">
            <div class="redirigir">
                ¿Aún no tienes una cuenta? <a href="registro.php">Registrarse</a>
            </div>
            <div class="redirigir"><a href="recuperar.php">¿Has olvido su contraseña?</a></div>
            <?php
    if (isset($_POST['iniciar'])) {
        autenticarUser($_POST['correo'], $_POST['password']);
    }
    ?>
        </form>
    </div>



</body>

</html>