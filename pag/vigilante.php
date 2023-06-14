<?php
//Importar y abrir session que esta dentro de funciones.php
require_once '../lib/funciones.php';
require_once '../lib/modulos.php';
require_once '../lib/mapa.php';
?>
<html>

<head>
    <!-- Meter informacion general de head -->
    <?php head_info(); ?>
    <script src="../js/funciones.js"></script>
    <link href="../css/vigilante.css" rel="stylesheet" type="text/css">
    <script src="../js/mapa.js"></script>
    <title>DisplayAds</title>
</head>

<body>
    <?php
    if (isset($_SESSION['usuario']) && validarVigilante($_SESSION['usuario']['id_usuario']) && validarBloqueo($_SESSION['usuario']['id_usuario'])) {
        // Menu general
        menu_general();
        $puntos = 0;
        $id_usuario = $_SESSION['usuario']['id_usuario'];
        $conn = conectar();
        $sql = "SELECT * FROM usuarios WHERE id_usuario='$id_usuario'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $puntos = $row['puntos'];
            }

        }
        ?>
        <!-- Crear submenu con sus opciones -->
        <div class="d-flex vh-100">
            <div id="sidebar">
                <div class="p-2">
                    <a href="vigilante.php?misiones" class="navbar-brand text-center text-light w-100 p-4 border-bottom">
                        Vigilante
                    </a>
                </div>
                <div id="sidebar-accordion" class="accordion">
                    <div class="list-group">
                        <a href="vigilante.php?misiones" class="list-group-item list-group-item-action text-light"
                            id="sidebar2">
                            <i class="fa fa-bullseye mr-3" aria-hidden="true"></i>Misiones
                        </a>

                        <a href="vigilante.php?recompensas" class="list-group-item list-group-item-action text-light"
                            id="sidebar2">
                            <i class="fa fa-shopping-bag mr-3" aria-hidden="true"></i> Recompensas
                        </a>

                        <a href="vigilante.php?recompensas" class="list-group-item list-group-item-action text-light"
                            id="sidebar2">
                            <?php echo "<b>Tus puntos:</b> " . $puntos; ?>
                        </a>
                    </div>
                </div>
            </div>

            <!-- <?php
            if (isset($_REQUEST['vigilantePrincipal'])) {
                ?>
                <div class="flex-grow-1">
                    <div class="p-3" style="display: block;">
                        <h2>Noticias para vigilante</h2><br>
                    </div>
                </div>
                <?php
            }
            ?> -->

            <?php
            if (isset($_REQUEST['misiones'])) {
                ?>
                <div class="flex-grow-1">
                    <?php
                    mapa("vigilar"); ?>
                </div>
                <?php
            }

            ?>
            <?php
            if (isset($_REQUEST['recompensas'])) {
                ?>
                <div class="flex-grow-1">
                    <div class="p-3" style="display: block;">

                        <section class="bg-white py-4 my-3">
                            <div class="container">
                                <div class="row">
                                    <div class="col-12">
                                        <h2 class="mb-3 text-primary">RECOMPENSAS</h2>
                                    </div>

                                    <?php
                                        recompensas();
                                    ?>
                                </div>
                            </div>
                    </div>
                </div>
            </div>


            <?php
            }
            if (isset($_POST['reclamarRecompensa'])) {
                $id_usuario = $_SESSION['usuario']['id_usuario'];
                $id_producto = $_POST['id_producto'];
                $ubicacion = $_POST['ubicacion'];
                reclamarRecompensa($id_usuario, $id_producto, $ubicacion);
            }
            if (isset($_POST['imagenMision'])) {
                $id_mision = $_POST['id_mision'];
                imagenMision($id_mision);
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