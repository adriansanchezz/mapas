<!DOCTYPE html>
<?php
//Importar y abrir session que esta dentro de functiones.php
require_once '../lib/functiones.php';
require_once '../lib/modulos.php';
?>
<html>

<head>
    <?php head_info(); ?>
    <title>DisplayAds</title>
</head>

<body>
    <!-- Menu general -->
    <?php menu_general(); ?>
    <!-- Menu horizontal -->
    <div class="d-flex vh-100">
        <div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 240px;">
            <br><br>

            <ul class="nav nav-pills flex-column mb-auto">
                <li>
                    <form action="cuenta.php" method="post">
                        <button type="submit" name="pricipal" class="btn btn-link nav-link text-white">Principal
                        </button>
                    </form>
                </li>

                <li>
                    <form action="cuenta.php" method="post">
                        <button type="submit" name="cambiar_nombre" class="btn btn-link nav-link text-white">Cambiar
                            nombre</button>
                    </form>
                </li>
                <li>
                    <form action="cuenta.php" method="post">
                        <button type="submit" name="cambiar_correo" class="btn btn-link nav-link text-white">Cambiar
                            correo</button>
                    </form>
                </li>
                <li>
                    <form action="cuenta.php" method="post">
                        <button type="submit" name="cambiar_contra" class="btn btn-link nav-link text-white">Cambiar
                            constraseña</button>
                    </form>
                </li>
            </ul>
        </div>

        <div class="flex-grow-1">
            <h2>Informacion personal</h2><br>

            <h3>Correo</h3>
            fasdfasdf <br><br>

            <h3>Nombre</h3>
            fasdfasdf <br><br>
            <br>

            <h3>Propieracdes</h3>

        </div>



        <?php
        if (isset($_POST['cambiar_nombre'])) {
            ?>
            <div class="flex-grow-1">
                <form action="cuenta.php" method="post">
                    <h3>Modificar el Nombre</h3><br />
                    <input type="text" name="nuevoNombre" placeholder="Nuevo nombre"><br /><br />

                    <input type="submit" name="guardarNombre" value="Guardar" />
                </form>
            </div>
            <?php
        } else if (isset($_POST['guardarNombre'])) {
            guardarNombre($_POST['nuevoNombre'], $_SESSION['usuario']['id_usuario']);
        }
        ?>

        <?php
        if (isset($_POST['cambiar_correo'])) {
            ?>
            <div class="flex-grow-1">
                <form action="cuenta.php" method="post">
                    <h3>Modificar el Correo</h3><br />
                    <input type="email" name="nuevoCorreo" placeholder="Nuevo correo"><br />
                    <input type="text" name="nuevoCorreo2" placeholder="Confirmar nombre"><br /><br />

                    <input type="submit" name="confirmarCorreo" value="Confirmar" />
                </form>
            </div>
            <?php
        } else if (isset($_POST['confirmarCorreo'])) {
            guardarCorreo($_POST['nuevoCorreo'], $_POST['nuevoCorreo2'], $_SESSION['usuario']['id_usuario']);
        }
        ?>

        <?php
        if (isset($_POST['cambiar_contra'])) {
            ?>
            <div class="flex-grow-1">
                <form action="cuenta.php" method="post">
                    <h3>Modificar la Constraseña</h3><br />
                    <input type="email" name="nuevoPass" placeholder="Nuevo correo"><br />
                    <input type="text" name="nuevoPass2" placeholder="Confirmar nombre"><br /><br />

                    <input type="submit" name="cambioContra" value="Confirmar" />
                </form>
            </div>
            <?php
        } else if (isset($_POST['cambioContra'])) {
            guardarPassword($_POST['nuevoPass'], $_POST['nuevoPass2'], $_SESSION['usuario']['id_usuario']);
        }
        ?>


    </div>
</body>

</html>