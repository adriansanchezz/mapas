<!DOCTYPE html>
<?php
//Importar y abrir session que esta dentro de funciones.php
require_once '../lib/funciones.php';
require_once '../lib/modulos.php';
?>
<html>

<head>
    <!-- Meter informacion general de head -->
    <?php head_info(); ?>
    <title>DisplayAds</title>
    <style>
        img {
            height: 80vh;
        }
    </style>
</head>

<body>
    <?php
    if (isset($_SESSION['usuario'])) {
        // Menu general
        menu_general(); ?>

        <!-- Crear submenu con sus opciones -->
        <div class="d-flex vh-100">
            <div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 200px;">
                <br><br>

                <ul class="nav nav-pills flex-column mb-auto">
                    <li class="nav-item">
                        <form action="guias.php" method="post">
                            <button type="submit" name="guiaUsuario" class="btn btn-link nav-link text-white">Guía de
                                Usuario</button>
                        </form>
                    </li>
                    <li>
                        <form action="guias.php" method="post">
                            <button type="submit" name="guiaVigilante" class="btn btn-link nav-link text-white">Guía de
                                Vigilante</button>
                        </form>
                    </li>
                    <li>
                        <form action="guias.php" method="post">
                            <button type="submit" name="guiaEmpresa" class="btn btn-link nav-link text-white">Guía de
                                Empresas</button>
                        </form>
                    </li>
                </ul>
            </div>

            <?php
            if (isset($_REQUEST['guiaUsuario'])) {

                ?>
                <div class="flex-grow-1 text-center">
                    <div class="p-3" style="display: block;">
                        <h2>Guía para el usuario común</h2>
                        <br>
                        <br>
                        <h4>Rol "usuario"</h4>
                        <p>El rol de usuario común lo tiene nada más entrar en nuestra página y no necesitas hacer nada
                            especial para poder acceder al menú del mismo. Si te preguntas cómo funciona este apartado, entonces
                            sigue los siguientes pasos.</p>
                        <br>
                        <p>Al acceder al apartado de "Usuario" situado en el menú superior de la página:</p>
                        <p>Podremos ver otro menú, este lateral, en el que veremos los botones "principal", "mapa" y "tienda".
                        </p>
                        <br>
                        <br>
                        <h4>Principal</h4>
                        <p>En el apartado "principal" podrás ver las noticias de actualizaciones o informaciones del día a día
                            de las que se advierte al usuario.</p>
                        <br>
                        <br>
                        <h4>Mapa</h4>
                        <p>En el apartado "mapa" podrás ver un mapa de toda la península en el que podrás hacer clic con el
                            objetivo de situar un punto. ¿Qué punto debe situar el usuario? Básicamente la ubicación en la que
                            se encuentra su lugar publicitario.</p>
                        <p>Por ejemplo, si yo vivo en Castro Urdiales, puedo buscar mi ubicación por calle e incluso piso en la
                            barra de búsqueda, y los campos se rellenan. Como usuario solo deberá rellenar los campos:
                            descripción y precio, porque son obligatorios. En la descripción se recomienda poner la calle, el
                            portal y el piso. También se recomienda que la foto de Google enfoque a la propiedad en cuestión.
                        </p>

                        <img src="../img/guiaMapa.png">
                        <br>
                        <p>Y por último se guarda el punto y se podrá ver en el mapa. También tendrás un botón de borrar si lo
                            seleccionas. Cabe destacar que si te has equivocado puedes ir a "cuenta" y editar la descripción o
                            el precio de la ubicación.
                        </p>
                        <br>
                        <br>
                        <h4>Tienda</h4>
                        <p>En la tienda podremos ver los productos disponibles. Estos son fundas y marcos para los carteles de
                            publicidad, ayudando al usuario a poder situar la publicidad en su propiedad de forma más cómoda. En
                            "Carrito" podrá ver cuántos productos ha añadido y confirmar su compra.
                        </p>
                    </div>
                </div>

                <?php

            }
            if (isset($_REQUEST['guiaVigilante'])) {

                ?>
                <div class="flex-grow-1 text-center">
                    <div class="p-3" style="display: block;">
                        <h2>Guía para el vigilante</h2><br>
                        <br>
                        <br>
                        <h4>Rol "Vigilante"</h4>
                        <p>El rol de usuario vigilante se rotará entre los distintos usuarios que se encuentran en la aplicación
                            para evitar problemas.
                            El vigilante se encargará de visitar puntos en los que hay situada publicidad de una empresa y
                            tendrá recompensas por sacar una foto. Para conseguir puntos la misión deberá ser revisada y
                            aceptada por un administrador.
                        </p>
                        <br>
                        <p>Al acceder al apartado de "Vigilante" situado en el menú superior de la página:</p>
                        <p>Podremos ver otro menú, este lateral, en el que veremos los botones "principal", "misiones" y
                            "recompensas".
                        </p>
                        <br>
                        <br>
                        <h4>Principal</h4>
                        <p>En el apartado "principal" podrá ver las noticias de actualizaciones o informaciones del día a día
                            que afectan al vigilante.</p>
                        <br>
                        <br>
                        <h4>Misiones.</h4>
                        <p>En el apartado "misiones" podrá ver un mapa con un rango dependiendo de su ubicación actual. Debe
                            aceptar la ubicación o esto no funcionará y no verá ningún punto. Para iniciar tendrá que clickar
                            sobre "solicitar misión" y se le asignará un punto con un círculo rojo alrededor del mismo. También
                            en las tablas de misiones podrá ver la información sobre la misión actual. La misión se completa
                            cuando sube una prueba (una foto de la ubicación en la que se ve el cartel publicitario) y le da a
                            completar. Entonces estará a la espera de que un administrador la de por válida y obtenga su
                            recompensa..</p>
                        <br>
                        <br>
                        <h4>Recompensas.</h4>
                        <p>En el apartado "recompensas" simplemente podrá canjear sus puntos a cambio de una recompensa que
                            elija.</p>
                        <br>
                        <br>
                    </div>
                </div>
                <?php
            }

            if (isset($_REQUEST['guiaEmpresa'])) {

                ?>
                <div class="flex-grow-1 text-center">
                    <div class="p-3" style="display: block;">
                        <h2>Guía para la empresa</h2><br>
                        <br>
                        <br>
                        <h4>Rol "Empresa"</h4>
                        <p>El rol de usuario empresa se otorgará una vez la empresa haya enviado un formulario con todos los
                            datos que le pedimos con la seguridad de comprobar que es verídica. Tras ser revisado el formulario
                            por un administrador, si lo acepta entonces le otorgará dicho rol y permisos.


                            El objetivo de este tipo de usuario es ofrecerle poder publicitarse en cualquier punto disponible en
                            el mapa, situado por un usuario propietario del lugar.
                        </p>
                        <br>
                        <p>Al acceder al apartado de "Empresa" situado en el menú superior de la página:</p>
                        <p>Podremos ver otro menú, este lateral, en el que veremos los botones "principal", "publicitarse" e
                            "información".
                        </p>
                        <br>
                        <br>
                        <h4>Principal</h4>
                        <p>En el apartado "principal" podrá ver las noticias de actualizaciones o informaciones del día a día
                            de las que se advierte al usuario.</p>
                        <br>
                        <br>
                        <h4>Publicitarse.</h4>
                        <p>En el apartado "publicitarse" podrá ver un mapa con puntos de distintas ubicaciones disponibles, que
                            representan lugares en los que la empresa puede situar su publicidad pagando un precio por ello.
                            Una vez ha seleccionado la ubicación, entonces puede ir al apartado "carrito" y ver qué ubicaciones
                            ha comprado, también aumentar o disminuir los meses que desea tener su publicidad en dicho lugar.
                        </p>
                        <br>
                        <br>
                        <h4>Información.</h4>
                        <p>El apartado "información" es simplemente para ver la información sobre la propia empresa, que es la
                            misma información que se introdujo en el formulario de solicitud.</p>
                        <br>
                        <br>
                    </div>
                </div>

                <?php

            }
            ?>

            <?php
    } else {
        echo ('Acceso denegado');
        print '<a href ="../index.php"><button>Volver</button></a>';
        session_destroy();
    }
    ?>
</body>

</html>