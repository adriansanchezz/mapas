<!DOCTYPE html>
<?php
// Comodines

try {
    //conexion
    $conn = conectar();

    //consulta
    $sql = "";

    //Ejecutar
    mysqli_query($conn, $sql);

} catch (Exception $e) {
    echo "Hay un fallo " . $e;
} finally {
    // Cerrar la conexión y liberar recursos
    mysqli_close($conn);
}


// Notas

// KanBin
echo ('Acceso denegado');
print '<br>';
print '<a href ="login.php"><button class="back">Volver</button></a>';
session_destroy();

/* 
Cuenta fucion falta lista de erro 8 caracter




*/

//Adrian

?>
<html>

<head>
</head>

<body>



</body>

</html>