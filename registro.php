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
                </form>

                <form action="index.php" method="post">
                    <input type="submit" name="volverReg" value="Volver"/>
                </form>        
            </div>
        
      <?php
        if(isset($_POST['registrar'])){
            validarUser($_POST['username'],$_POST['correo'],$_POST['telefono'],$_POST['password'],$_POST['password2']);
        }
      ?>
    </body>
</html>

