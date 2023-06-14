<?php

//Función de mapa al que se le pasa un valor y dependiendo de este se realiza una u otra función.
function mapa($valor)
{
    // Si el valor es ver, entonces la función irá enfocada a ver el mapa y los puntos creados por el usuario.
    // Esta función está enfocada al menú de empresa.
    if ($valor == "ver") {
        ?>
        <!-- Se crea toda la maquetación del menú de empresa. -->
        <div class="flex-grow-1">
            <div class="p-3" style="display: block;">
                <div class="p-3" style="display: block;">
                    <form class="form-inline my-2 my-lg-0" action="empresa.php" method="post">
                        <button class="btn btn-outline-success my-2 my-sm-0" name="empresaCarrito"
                            type="submit">Carrito</button>
                    </form>
                    <h1>Bienvenido a nuestro mapa</h1>
                    <h4>Selecciona alguna ubicación para ver información:</h4>
                    <p>¿Quieres buscar una ubicación?</p>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="direccion" placeholder="Buscar dirección">
                        <div class="input-group-append">
                            <button class="btn btn-primary" id="buscarDireccion">Buscar</button>
                        </div>
                    </div>
                    <div id="map"></div>
                </div>
            </div>
        </div>
        <script>
            // Se hace llamamiento de la función mapaEmpresa, que creará el mapa en cuestión y todo lo que tenga que ver con funciones del mismo.
            mapaEmpresa();
        </script>
        <?php
    }

    // Apartado para guardar un marcador de una ubicación. Esto está centralizado hacia el menú de usuario.
    if ($valor == "guardar") {
        ?>
        <div class="container">
            <div class="row">
                <div class="col-md-9">
                    <div class="p-3">
                        <h1 class="text-primary">MAPA</h1>
                        <p>¿Quieres buscar una ubicación?</p>
                        <div>
                            <span id='errorUsuario' style='color: red;'></span>
                        </div>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" id="direccion" placeholder="Buscar dirección">
                            <div class="input-group-append">
                                <button class="btn btn-primary" id="buscarDireccion">Buscar</button>
                            </div>
                        </div>
                        <div id="map"></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <form action="usuario.php" method="POST" enctype="multipart/form-data"
                        onsubmit="return validarFormulario();">
                        <input type="hidden" name="lat" id="lat">
                        <input type="hidden" name="lng" id="lng">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="descripcion">Descripción:</label>
                                    <textarea class="form-control" name="descripcion" id="descripcion"
                                        placeholder="Ej: N13, 3ºIZQ" required></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="provincia">Provincia:</label>
                                    <input type="text" class="form-control" id="provincia" name="provincia"
                                        placeholder="Provincia" required>
                                </div>
                                <div class="form-group">
                                    <label for="ciudad">Ciudad:</label>
                                    <input type="text" class="form-control" id="ciudad" name="ciudad" placeholder="Ciudad"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label for="ubicacion">Ubicación:</label>
                                    <input type="text" class="form-control" id="ubicacion" name="ubicacion"
                                        placeholder="Ubicación" required>
                                </div>
                                <div class="form-group">
                                    <label for="codigo_postal">Código Postal:</label>
                                    <input type="text" class="form-control" id="codigo_postal" name="codigo_postal"
                                        placeholder="Código Postal" required>
                                </div>
                                <div class="form-group">
                                    <label for="tipoPublicidad">Tipo de Publicidad:</label>
                                    <select class="form-control" name="tipoPublicidad" id="tipoPublicidad" required>
                                        <?php
                                        $sql = "SELECT id_tipo_publicidad, nombre FROM tipospublicidades";
                                        $resultado = sqlSELECT($sql);
                                        if (mysqli_num_rows($resultado) > 0) {
                                            while ($fila = mysqli_fetch_assoc($resultado)) {
                                                $id = $fila['id_tipo_publicidad'];
                                                $nombre = $fila['nombre'];
                                                echo "<option value='$id'>$nombre</option>";
                                            }
                                        } else {
                                            echo "<option value=''>No hay tipos de publicidades disponibles</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="precio">Establece un precio:</label>
                                    <input type="text" class="form-control" id="precio" name="precio" placeholder="Precio"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label for="imagen" id="imagensubir">Sube una foto:</label>
                                    <span id="mensajePubli" style="display: block; color: red;">(*) Recuerda subir la foto del
                                        lugar en
                                        el que publicitarás.</span>
                                    <span id="mensajePiso" style="display: none; color: red;">(*) Recuerda subir un papel
                                        certificado de
                                        la comunidad de vecinos y la foto del lugar en el que publicitarás.</span>
                                    <input type="file" name="imagen[]" multiple>
                                </div>

                                <button type="submit" class="btn btn-primary" name="guardarMarcador">Guardar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
        <script>
            mapaUsuario();
            tipoPublicidad(); 
        </script>
        <?php
    }
    if ($valor == "vigilar") {
        ?>

        <h1>MISIONES</h1>
        <span id='errorUsuario' style='color: red;'></span>
        <div id="map"></div><br>
        <input type="submit" class="btn btn-primary" id="solicitarMision" value="Solicitar misión">
        <div class="container mt-4">
            <div class="table-responsive mb-4">
                Misiones en proceso(<small><i>*Cada mision cuenta 10 puntos</i></small>):
                <table id="tabla-puntos" class="table">
                    <thead>
                        <tr>
                            <th>Ubicación</th>
                            <th>Código Postal</th>
                            <th>Descripción</th>
                            <th>Misión</th>
                            <th>Prueba</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Filas de puntos se agregarán aquí dinámicamente -->
                        <?php
                        $id_usuario = $_SESSION['usuario']['id_usuario'];
                        $sql = "SELECT m.descripcion AS mision_descripcion, p.descripcion AS publicidad_descripcion, p.codigo_postal, p.ubicacion, m.id_mision
                                        FROM misiones AS m, publicidades AS p
                                        WHERE m.id_usuario='$id_usuario' AND m.estado=0 AND m.aceptacion=0 AND m.id_publicidad = p.id_publicidad";
                        $result = sqlSELECT($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo '<tr>';
                                echo '<td>' . $row['ubicacion'] . '</td>'; // Columna de descripción de misiones
                                echo '<td>' . $row['codigo_postal'] . '</td>'; // Columna de código postal de misiones
                                echo '<td>' . $row['publicidad_descripcion'] . '</td>'; // Columna de descripción de publicidades
                                echo '<td>' . $row['mision_descripcion'] . '</td>'; // Columna de descripción de publicidades
                                echo "<td><form action='vigilante.php' method='POST' enctype='multipart/form-data'>";
                                echo "<input type='hidden' name='id_mision' value='" . $row['id_mision'] . "'>";
                                echo "<input type='file' name='imagen' accept='image/*' required>";
                                echo "<input type='submit' name='imagenMision'></form></td>";
                                echo '</tr>';
                            }
                        }
                        ?>
                    </tbody>

                </table>
                Misiones completadas:
                <table id="tabla-puntos" class="table">
                    <thead>
                        <tr>
                            <th>Ubicación</th>
                            <th>Código Postal</th>
                            <th>Descripción</th>
                            <th>Misión</th>
                            <th>¿Aprobada?</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Filas de puntos se agregarán aquí dinámicamente -->
                        <?php
                        $id_usuario = $_SESSION['usuario']['id_usuario'];
                        $sql = "SELECT m.descripcion AS mision_descripcion, p.descripcion AS publicidad_descripcion, p.ubicacion, m.aceptacion, p.codigo_postal
                                        FROM misiones AS m, publicidades AS p
                                        WHERE m.id_usuario='$id_usuario' AND m.estado=1 AND m.id_publicidad = p.id_publicidad";
                        $result = sqlSELECT($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo '<tr>';
                                echo '<td>' . $row['ubicacion'] . '</td>'; // Columna de descripción de misiones
                                echo '<td>' . $row['codigo_postal'] . '</td>'; // Columna de código postal de misiones
                                echo '<td>' . $row['publicidad_descripcion'] . '</td>'; // Columna de descripción de publicidades
                                echo '<td>' . $row['mision_descripcion'] . '</td>'; // Columna de descripción de publicidades
                                if ($row['aceptacion'] == 0) {
                                    echo '<td>EN ESPERA...</td>'; // Columna de descripción de publicidades
                                }
                                if ($row['aceptacion'] == 1) {
                                    echo '<td>APROBADA</td>'; // Columna de descripción de publicidades
                                }
                                if ($row['aceptacion'] == 2) {
                                    echo '<td>RECHAZADA</td>'; // Columna de descripción de publicidades
                                }
                                echo '</tr>';
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <script>
            vigilante();
        </script>
        <?php
    }
}
?>