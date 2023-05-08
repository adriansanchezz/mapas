<!DOCTYPE html>
<?php
if(isset($_POST['cerrarSesion'])){
    session_destroy(); // Destruye todas las variables de sesión
    header("Location:../login.php"); // Redirige al usuario a la página de inicio de sesión
    exit; // Detiene la ejecución del script después de la redirección
}
require_once '../lib/functiones.php';
require_once '../lib/modulos.php';
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
                <a class="navbar-brand" >DisplayADS</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item active">
                            <form action="principal.php">
                                <button class="btn nav-link" name="inicio" type="submit">Inicio </button>
                            </form>
                        </li>

                        <li class="nav-item">
                            <form action="usuario.php">
                                <button class="btn nav-link" name="usuario" type="submit">Usuario</button>
                            </form>
                        </li>

                        <li class="nav-item">
                            <form action="empresa.php">
                                <button class="btn nav-link" name="empresa" type="submit">Empresa</button>
                            </form>
                        </li>

                        <li class="nav-item">
                            <form action="administrador.php">
                                <button class="btn nav-link" name="administrador" type="submit">Administrador</button>
                            </form>
                        </li>
                    </ul>

                    <form class="form-inline my-2 my-lg-0" action="cuenta.php" method="post">
                        <input class="btn btn-outline-success my-2 my-sm-0" type="submit" name="cuenta" value="Cuenta"/>
                    </form>

                    <form class="form-inline my-2 my-lg-0" action="principal.php" method="post">
                        <input class="btn btn-outline-success my-2 my-sm-0" type="submit" name="cerrarSesion" value="Cerrar sesión"/>
                    </form>

                </div>
            </div>
        </nav>
    </div>



<h1>INICIO</h1>


    </body>
</html>