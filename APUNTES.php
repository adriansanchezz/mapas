<!DOCTYPE html>
<?php
// Comodines

//consulta, otro quitar $result
try {
    //conexion
    $conn = conectar();

    //consulta
    $sql = "";

    //Ejecutar
    $result = mysqli_query($conn, $sql);

    while ($row = mysqli_fetch_assoc($result)) {
    }

} catch (Exception $e) {
    echo "Hay un fallo " . $e;
} finally {
    // Cerrar la conexión y liberar recursos
    mysqli_close($conn);
}
?>

<!-- Para modulos -->
<?php
function nombre()
{
?>

<?php
}
?>

<?php


// Notas

// KanBin
echo ('Acceso denegado');
print '<br>';
print '<a href ="login.php"><button class="back">Volver</button></a>';
session_destroy();


//Adrian
?>

<html>

<head>
</head>

<body>



</body>

</html>