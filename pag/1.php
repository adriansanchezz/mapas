<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
include 'lib/functiones.php';
session_start();
?>
<html>
    <head>
        <?php head_info(); ?>
        <title>DisplayAds</title>
    </head>
    <body>
        <div class="NAVBAR">
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
                <div class="container-fluid">
                    <a class="navbar-brand" href="Menu.php">DisplayADS</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav mr-auto">
                            <li class="nav-item active">
                                <a class="nav-link" href="Menu.php">INICIO <span class="sr-only">(current)</span></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="NosotrosLoged.php">Nosotros</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="MenuUsuario.php">Usuario</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="MenuEmpresa.php">Empresa</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="MenuAdministrador.php">Administrador</a>
                            </li>

                        </ul>
                        <form class="form-inline my-2 my-lg-0" action="Cuenta.php">
                            <button class="btn btn-outline-success my-2 my-sm-0" name="cuenta" type="submit">Cuenta</button>
                        </form>
                        <form class="form-inline my-2 my-lg-0" action="Inicio.php">
                            <button class="btn btn-outline-success my-2 my-sm-0" name="cerrarSesion" type="submit">Cerrar sesión</button>
                        </form>
                    </div>

                </div>
            </nav>
        </div>
        <?php
        if (isset($_REQUEST['enviar'])) {
            @$usu = $_REQUEST['usu'];
            @$password = $_REQUEST['con'];

            if (empty($usu) || empty($password)) {
                echo "Has dejado algún campo sin rellenar.";
                $vacio = true;
                ?>
                <div>
                    <form action="registro.php" method="post">
                        <input type="submit" name="volverReg" value="Volver al registro"/>
                    </form>
                    <form action="login.php" method="post">
                        <input type="submit" name="volverLog" value="Volver al login"/>
                    </form>
                </div>
                <?php
            } else {
                $instruccion = "select * from usuarios where email = '$usu' AND contrasena = '$password'";

                $consulta = mysqli_query(conectar(), $instruccion)
                        or die("Fallo en la consulta");
                while ($resultado = mysqli_fetch_array($consulta)) {
                    $_SESSION['usuario'] = $resultado;
                }
                $vacio = false;
            }
        }
        if (@$vacio == false) {

            if (isset($_SESSION['usuario'])) {
                $nom = $_SESSION['usuario']['email'];
                $instruccion = "select id_rol from usuarios_roles where id_usr=(select id_usr from usuarios where email = '$nom') AND id_rol = 5;";
                $consulta = mysqli_query(conectar(), $instruccion);

                while ($fila = mysqli_fetch_assoc($consulta)) {
                    $invitado = true;
                }

                $instruccion = "select id_rol from usuarios_roles where id_usr=(select id_usr from usuarios where email = '$nom') AND id_rol = 4;";
                $consulta = mysqli_query(conectar(), $instruccion);

                while ($fila = mysqli_fetch_assoc($consulta)) {
                    $empresa = true;
                }

                $instruccion = "select id_rol from usuarios_roles where id_usr=(select id_usr from usuarios where email = '$nom') AND id_rol = 3;";
                $consulta = mysqli_query(conectar(), $instruccion);
                while ($fila = mysqli_fetch_assoc($consulta)) {
                    $vigilante = true;
                }

                $instruccion = "select id_rol from usuarios_roles where id_usr=(select id_usr from usuarios where email = '$nom') AND id_rol = 2;";
                $consulta = mysqli_query(conectar(), $instruccion);

                while ($fila = mysqli_fetch_assoc($consulta)) {
                    $usuario = true;
                }

                $instruccion = "select id_rol from usuarios_roles where id_usr=(select id_usr from usuarios where email = '$nom') AND id_rol = 1;";
                $consulta = mysqli_query(conectar(), $instruccion);

                while ($fila = mysqli_fetch_assoc($consulta)) {
                    $administrador = true;
                }
                ?>

                <?php
                $nom = $_SESSION['usuario']['nombre'];
                strtoupper($nom);
                echo("<h1>Bienvenido $nom</h1>");
                echo("<div>");
                if (@$administrador == true) {
                    ?>


                    <?php
                }

                if (@$empresa == true) {
                    ?>


                    <?php
                }
                if (@$vigilante == true) {
                    ?>


                    <?php
                }
                if (@$usuario == true) {
                    ?>




                    <?php
                }
                if (@$invitado == true) {
                    ?>        

                    <?php
                }
                ?>

                <?php
            } else {
                echo('Acceso denegado');
                print '<br>';
                print '<a href ="login.php"><button class="back">Volver</button></a>';
                session_destroy();
            }
        }
        ?>
    </body>
</html>
