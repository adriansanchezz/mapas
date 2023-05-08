<?php

function menu_index() {
    $html = '
    <div class="NAVBAR">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="#">LOGO DE EMPRESA</a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="Inicio.php">INICIO <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="Nosotros.php">Nosotros</a>
                    </li>
                </ul>

                <form class="form-inline my-2 my-lg-0" action="Login.php">
                    <button class="btn btn-outline-success my-2 my-sm-0" name="login" type="submit">Iniciar Sesión</button>
                </form>

                <form class="form-inline my-2 my-lg-0" action="Registro.php">
                    <button class="btn btn-outline-success my-2 my-sm-0" name="registro" type="submit">Registrarse</button>
                </form>
            </div>
        </nav>
    </div>
    ';
    echo $html;
}

function menu_general() {
    $html = '
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
                            <form action="ticket.php">
                                <button class="btn nav-link" name="ticket" type="submit">Ticket</button>
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
    ';
    echo $html;
}

function head_info() {
    $html = '
        <meta charset="UTF-8">
        <meta name="description" content="Aplicación web que facilita a las empresas publicitarse a un precio asequible y a las personas ganar dinero por hacer de publicitadores">
        <meta name="keywords" content="anuncios, empresas, pequeñas empresas, recompensas, publicidad, publicitadores, crecer, carteles, ubicaciones">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    ';
    echo $html;
}



















?>

