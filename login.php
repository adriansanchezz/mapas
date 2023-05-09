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

        <h1>Login</h1>
        <div class="form">                
            <form action="login.php" method="post">
                <label>Correo electronico</label><br>
                <input type="email" name="correo"/><br/>
                
                <label>Contraseña</label><br>
                <input type="password" name="password"/><br/>
                
                <input type="submit" name="login" value="Login"/>
            </form>
            <form action="registro.php" method="post" class="links">
                <input type="submit" name="registro" value="Registrarse"/>
            </form>
        </div>            

        <?php
            if(isset($_POST['login'])){
                autenticarUser($_POST['correo'],$_POST['password']);
            }
        ?>

        <form action="./pag/principal.php"><button type="submit">Acceso anticipado</button></form>
    </body>
</html>


