<?php
session_start();
include '../connection.php';
if (!isset($_SESSION["user"])) {
    header('Location: ./cerrar.php');
    exit();
}

// Asegúrate de que el parámetro 'id' está definido
if(isset($_GET['id_mesa'])) {
    $idMesa = mysqli_real_escape_string($conn,$_GET['id_mesa']);
    $sala = mysqli_real_escape_string($conn,$_GET['sala']);
    $num_sala = mysqli_real_escape_string($conn,$_GET['num_sala']);
    $mesa = mysqli_real_escape_string($conn,$_GET['mesa']);
    $estadoget = mysqli_real_escape_string($conn,$_GET['estado']);
    // Consulta para obtener el estado actual de la mesa
    $sqlSelect = "SELECT me.id_sala_mesa, me.id_camarero ,es.estado_nombre as estado_mesa FROM tbl_mesas me INNER JOIN tbl_estado es ON me.id_estado_mesa = es.id_estado WHERE me.id_mesa = ?;";
    $stmtSelect = mysqli_prepare($conn, $sqlSelect);
    mysqli_stmt_bind_param($stmtSelect, "i", $idMesa);
    mysqli_stmt_execute($stmtSelect);
    $resultSelect = mysqli_stmt_get_result($stmtSelect);
    $selectRow = mysqli_fetch_assoc($resultSelect);
    try {
    // Desactivamos la autoejecución de consultas
    mysqli_autocommit($conn,false);
    // Iniciamos la transacción
    mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);
    // Insertar en la tabla de historial
    if ($selectRow['estado_mesa'] == "Ocupado") {
        $estado = "Libre";
    } else {
        $estado = "Ocupado";
    }
    $sqlInsertHistorial = "INSERT INTO tbl_historial (id_usuario, id_mesa, id_sala, estado) VALUES (?, ?, ?, ?)";
    $stmtInsertHistorial = mysqli_prepare($conn, $sqlInsertHistorial);
    mysqli_stmt_bind_param($stmtInsertHistorial, "iiis", $_SESSION['id_user'], $idMesa, $selectRow['id_sala_mesa'],$estado);
    mysqli_stmt_execute($stmtInsertHistorial);
    // Verifica el estado actual de la mesa
    if ($selectRow['estado_mesa'] == "Ocupado") {
        // Actualizar el estado y la fecha de salida en la base de datos
        $sqlLibre = "UPDATE tbl_mesas SET id_estado_mesa = (SELECT id_estado FROM tbl_estado WHERE estado_nombre = 'Libre'), id_camarero = NULL WHERE id_mesa = ?";
        $stmtLibre = mysqli_prepare($conn, $sqlLibre);
        mysqli_stmt_bind_param($stmtLibre, "i", $idMesa);
        mysqli_stmt_execute($stmtLibre);
    } else {
        // Actualizar el estado, la fecha de entrada y asignar el id_camarero en la base de datos
        $sqlOcupa = "UPDATE tbl_mesas SET id_estado_mesa = (SELECT id_estado FROM tbl_estado WHERE estado_nombre = 'Ocupado'), id_camarero = ? WHERE id_mesa = ?";
        $stmtOcupa = mysqli_prepare($conn, $sqlOcupa);
        mysqli_stmt_bind_param($stmtOcupa, "ii", $_SESSION['id_user'], $idMesa);
        mysqli_stmt_execute($stmtOcupa);
    }
    
    // Commit de la transacción
    mysqli_commit($conn);
    header("Location: ../home.php?sala=$sala&num_sala=$num_sala&mesa=$mesa&estado=$estadoget");
    } catch (Exception $e) {
        // En caso de error, realizar un rollback
        echo "Error: ". $e->getMessage()."<br>";
        mysqli_rollback($conn);
    }
} else {
    // Manejar el caso en el que 'id' no está definido o no es un número entero
    echo "Error: El parámetro 'id' no está definido o no es un número entero.";
}


mysqli_stmt_close($stmtSelect);
mysqli_stmt_close($stmtInsertHistorial);
mysqli_stmt_close($stmtLibre);
mysqli_stmt_close($stmtOcupa);
// Cerrar la conexión a la base de datos
mysqli_close($conn);
?>