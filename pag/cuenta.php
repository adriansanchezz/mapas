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
        <meta charset="UTF-8">
        <meta name="description" content="Aplicación web que facilita a las empresas publicitarse a 
                                          un precio asequible y a las personas ganar dinero por hacer de 
                                          publicitadores">
        <meta name="keywords" content="anuncios, empresas, pequeñas empresas, recompensas, publicidad, publicitadores, 
                                       crecer, carteles, ubicaciones">
        <meta name="robots" content="index, follow">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>DisplayADS - Mi Cuenta</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">   
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
                            <button class="btn btn-outline-success my-2 my-sm-0" name="login" type="submit">Cuenta</button>
                        </form>
                        <form class="form-inline my-2 my-lg-0" action="Inicio.php">
                            <button class="btn btn-outline-success my-2 my-sm-0" name="registro" type="submit">Cerrar sesión</button>
                        </form>
                    </div>

                </div>
            </nav>
        </div>

    </body>
</html>


