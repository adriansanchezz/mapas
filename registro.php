<!DOCTYPE html>
<?php
require_once 'lib/functiones.php';
session_start();
?>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="description" content="Aplicación web que facilita a las empresas publicitarse a un precio asequible y a las personas ganar dinero por hacer de publicitadores">
        <meta name="keywords" content="anuncios, empresas, pequeñas empresas, recompensas, publicidad, publicitadores, crecer, carteles, ubicaciones">
        <meta name="robots" content="index, follow">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>DisplayADS - Registrarse</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">   
    </head>
    <body>
        <?php
            include 'lib/menu_index.php';
        ?>
            <div class="form"> 
                <form action="registro.php" method="post">
                    <label>Nombre de usuario</label><br>
                    <input type="text" name="username"/><br/>
                    
                    <label>Correo electronico</label><br>
                    <input type="email" name="correo"/><br/>
                    
                    <label>Numero de telefono</label><br>
                    <input type="tel" name="telefono"/><br/>
                    
                    <label>Contraseña</label><br>
                    <input type="password" name="password"/><br/>
                    
                    <label>Repite la contraseña</label><br>
                    <input type="password" name="password2"/><br/>
                    
                    <input type="submit" name="registrar" value="Registrarse"/>
                    
                    <form action="index.php" method="post">
                        <input type="submit" name="volverReg" value="Volver"/>
                    </form>            
            </div>
        
      <?php
        if(isset($_POST['registrar'])){
            $estado = validarUser($_POST['username'],$_POST['correo'],$_POST['telefono'],$_POST['password'],$_POST['password2']);
        }
      ?>
    </body>
</html>

