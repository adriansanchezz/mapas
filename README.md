
    try {
        //conexion
        $conn = conectar();

        //consulta
        $sql = "";

        //Ejecutar
        mysqli_query($conn, $sql);

    } catch (Exception $e) {
        echo "Hay un fallo ".$e;
    } finally {
        // Cerrar la conexión y liberar recursos
        mysqli_close($conn);
    }