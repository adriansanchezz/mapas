<!DOCTYPE html>
<?php
require_once 'lib/functiones.php';
session_start();
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>DisplayAds - Iniciar Sesion</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">   
    </head>
    <body>
        <?php
            include 'lib/menu_index.php';
        ?>
        
        <h1>Login</h1>
            <?php
        if (isset($_REQUEST['cerrSes'])) {
            session_destroy();
            ?>
            <div class="box">     
                <div class="form">                
                    <form action="MenusGenerales.php" method="post">
                        <div class="inputBox">
                            <input type="text" name="usu"/>
                            <span>Correo</span>
                            <i></i>
                        </div>
                        <div class="inputBox">
                            <input type="password" name="con"/>
                            <span>Contraseña</span>
                            <i></i>
                        </div>
                        <input type="submit" name="enviar" value="Entrar"/>
                    </form>
                    <form action="registro.php" method="post" class="links">
                        <input type="submit" name="registro" value="Registrarse"/>
                    </form>
                </div>            
            </div>
            <div></div>
            <?php
        } else {
            if (isset($_REQUEST['enviarReg'])) {
                @$nombre = $_REQUEST['usu'];
                @$correo = $_REQUEST['correo'];
                @$password = $_REQUEST['password'];
                @$passwordRepeat = $_REQUEST['passwordMatchInput'];
                @$telefono = $_REQUEST['telefono'];

                if (empty($nombre) || empty($password) || empty($correo) || empty($passwordRepeat) || empty($telefono)) {

                    echo "HAS DEJADO ALGÚN CAMPO INCOMPLETO";
                    ?>
                    <div>
                        <form action="registro.php" method="post" style="scale: 1.5;display: flex;justify-content: center;">
                            <input type="submit" name="volverReg" value="Volver al registro" style="color: #45f3ff;width: 100%;border: none;cursor: pointer;background: #333;margin-bottom: 5px;">
                        </form>
                        <form action="login.php" method="post" style="scale: 1.5;display: flex;justify-content: center;">
                            <input type="submit" name="volverLog" value="Volver al login" style="color: #45f3ff;width: 100%;border: none;cursor: pointer;background: #333;margin-top: 5px;">
                        </form>
                    </div>
                    <?php
                } else {
                    if ($password !== $passwordRepeat) {
                        echo "LAS PASSWORDS NO SON IGUALES";
                        ?>
                        <div>
                            <form action="Registro.php" method="post" style="scale: 1.5;display: flex;justify-content: center;">
                                <input type="submit" name="volverReg" value="Volver al registro" style="color: #45f3ff;width: 100%;border: none;cursor: pointer;background: #333;margin-bottom: 5px;">
                            </form>
                            <form action="login.php" method="post" style="scale: 1.5;display: flex;justify-content: center;">
                                <input type="submit" name="volverLog" value="Volver al login" style="color: #45f3ff;width: 100%;border: none;cursor: pointer;background: #333;margin-top: 5px;">
                            </form>
                        </div>
                        <?php
                    } else {
                        ?>
                        <div class="box">     
                            <div class="form">                
                                <form action="MenusGenerales.php" method="post">
                                    <div class="inputBox">
                                        <input type="text" name="usu"/>
                                        <span>Correo</span>
                                        <i></i>
                                    </div>
                                    <div class="inputBox">
                                        <input type="password" name="con"/>
                                        <span>Contraseña</span>
                                        <i></i>
                                    </div>
                                    <input type="submit" name="enviar" value="Entrar"/>
                                </form>
                                <form action="registro.php" method="post" class="links">
                                    <input type="submit" name="registro" value="Registrarse"/>
                                </form>
                            </div>            
                        </div>
                        <div></div>
                        <?php
                        $instruccion = "SELECT `id_usr`, `nombre`, `contrasena`, `telefono`, `email` FROM `usuarios` WHERE email = '" . $correo . "';";
                        $consulta = mysqli_query(conectar(), $instruccion);
                        $nfilasBusqueda = mysqli_num_rows($consulta);
                        if ($nfilasBusqueda > 0) {
                            echo "<h5>LO SENTIMOS PERO EL CORREO INTRODUCIDO YA EXISTE, VUELVA A REGISTRARSE</h5>";
                        } else {
                            $instruccion = "insert into usuarios values('default', '$nombre', '$password', '$telefono', '$correo')";
                            $consulta = mysqli_query(conectar(), $instruccion);

                            $instruccion = "insert into usuarios_roles values((select id_usr from usuarios where email = '$correo'), 4)";
                            $consulta = mysqli_query(conectar(), $instruccion);
                            print ("<h2>Se ha registrado correctamente</h2>");
                        }
                    }
                }
            } else {
                ?>
                <div class="box">     
                    <div class="form">                
                        <form action="MenusGenerales.php" method="post">
                            <div class="inputBox">
                                <span>Correo</span>
                                <input type="text" name="usu"/>
                                <i></i>
                            </div>
                            <div class="inputBox">
                                <span>Contraseña</span>
                                <input type="password" name="con"/>
                                <i></i>
                            </div>
                            <input type="submit" name="enviar" value="Entrar"/>
                        </form>
                        <form action="Registro.php" method="post" class="links">
                            <input type="submit" name="registro" value="Registrarse"/>
                        </form>
                        <form action="Inicio.php" method="post" class="links">
                            <input type="submit" name="inicio" value="Inicio"/>
                        </form>
                    </div>            
                </div>
                <div></div>

                <?php
            }
        }
        ?>       
    </body>
</html>


