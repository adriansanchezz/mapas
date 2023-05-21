<!DOCTYPE html>
<?php
//Importar y abrir session que esta dentro de functiones.php
require_once '../lib/functiones.php';
require_once '../lib/modulos.php';
?>
<html>

<head>
    <!-- Meter informacion general de head -->
    <?php head_info(); ?>
    <title>DisplayAds</title>
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
                    <li>
                        <form action="vigilante.php" method="post">
                            <button type="submit" name="vigilantePrincipal"
                                class="btn btn-link nav-link text-white">Principal
                            </button>
                        </form>
                    </li>
                    <li>
                        <form action="vigilante.php" method="post">
                            <button type="submit" name="misiones" class="btn btn-link nav-link text-white">Misiones
                            </button>
                        </form>
                    </li>
                    <li>
                        <form action="vigilante.php" method="post">
                            <button type="submit" name="recompesas" class="btn btn-link nav-link text-white">Recompensas
                            </button>
                        </form>
                    </li>
                </ul>
            </div>



            <?php
            if (isset($_REQUEST['vigilantePrincipal'])) {
                ?>
                <div class="flex-grow-1">
                    <div id="seccion1" class="p-3" style="display: block;">
                        <h2>Noticias para vigilante</h2><br>
                    </div>
                </div>
                <?php
            }
            ?>

            <?php
            if (isset($_REQUEST['misiones'])) {
                ?>


                <?php mapa("vigilar"); ?>
                <?php

                echo '<input type="submit" class="btn btn-primary" id="solicitarMision" value="Solicitar mision">';
                ?>

                <?php
            }
            if (isset($_REQUEST['solicitarMision'])) {
                function randomMission()
                {

                }
            }
            ?>

            <?php
            if (isset($_REQUEST['recompesas'])) {
                ?>
                < div id="seccion1" class="p-3" style="display: block;">
                    <h1>Recompensas</h1>

            </div>
            <?php
            }
            ?>
        <?php

        if (isset($_GET['subirMision'])) {
            // Verifica si el usuario ha iniciado sesión y obtén su ID de usuario
            debug_to_console("Test");
            if (isset($_SESSION['usuario']['id_usuario'])) {
                $id_usuario = $_SESSION['usuario']['id_usuario'];

                // Obtén los datos enviados por AJAX
                $descripcion = $_POST['descripcion'];
                $id_tipo = 1;

                $conn = conectar();
                
                $sql = "SELECT * FROM misiones WHERE descripcion='$descripcion'";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    
                }
                else
                {
                    if ($conn->connect_error) {
                        die("Error de conexión: " . $conn->connect_error);
                    }
    
                    $stmt2 = $conn->prepare("INSERT INTO misiones (descripcion, id_tipo_mision, id_usuario) VALUES (?, ?, ?)");
                    $stmt2->bind_param("sii", $descripcion, $id_tipo, $id_usuario);
                    if ($stmt2->execute()) {
                        // Los datos se han insertado correctamente en la base de datos
                        echo "Los datos se han guardado en la base de datos.";
                    } else {
                        // Ocurrió un error al insertar los datos en la base de datos
                        echo "Error al guardar los datos en la base de datos: " . $stmt2->error;
                    }
                    $stmt->close();
                    $stmt2->close();
                    $conn->close();
                }
                
                
            } else {
                echo "El usuario no ha iniciado sesión.";
            }
        }

        ?>
        </div>
        <?php
    } else {
        echo ('Acceso denegado');
        print '<a href ="../index.php"><button>Volver</button></a>';
        session_destroy();
    }
    ?>

</body>

</html>