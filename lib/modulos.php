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
?>

