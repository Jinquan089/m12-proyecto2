<?php
session_start();
include '../connection.php';
if (!isset($_SESSION["user"])) {
    header('Location: ./cerrar.php');
    exit();
}

// Asegúrate de que el parámetro 'id' está definido
if (isset($_GET['id_mesa'])) {
    $idMesa = $_GET['id_mesa'];
    $sala = $_GET['sala'];
    $num_sala = $_GET['num_sala'];
    $mesa = $_GET['mesa'];
    $estadoget = $_GET['estado'];

    try {
        // Iniciamos la transacción
        $conn->beginTransaction();

        // Consulta para obtener el estado actual de la mesa
        $sqlSelect = "SELECT me.id_sala_mesa, me.id_camarero, es.estado_nombre as estado_mesa 
                      FROM tbl_mesas me 
                      INNER JOIN tbl_estado es ON me.id_estado_mesa = es.id_estado 
                      WHERE me.id_mesa = :idMesa";
        $stmtSelect = $conn->prepare($sqlSelect);
        $stmtSelect->bindParam(':idMesa', $idMesa, PDO::PARAM_INT);
        $stmtSelect->execute();
        $selectRow = $stmtSelect->fetch(PDO::FETCH_ASSOC);

        // Insertar en la tabla de historial
        if ($selectRow['estado_mesa'] == "Ocupado") {
            $estado = "Libre";
        } else {
            $estado = "Ocupado";
        }

        $sqlInsertHistorial = "INSERT INTO tbl_historial (id_usuario, id_mesa, id_sala, estado) VALUES (:idUsuario, :idMesa, :idSala, :estado)";
        $stmtInsertHistorial = $conn->prepare($sqlInsertHistorial);
        $stmtInsertHistorial->bindParam(':idUsuario', $_SESSION['id_user'], PDO::PARAM_INT);
        $stmtInsertHistorial->bindParam(':idMesa', $idMesa, PDO::PARAM_INT);
        $stmtInsertHistorial->bindParam(':idSala', $selectRow['id_sala_mesa'], PDO::PARAM_INT);
        $stmtInsertHistorial->bindParam(':estado', $estado, PDO::PARAM_STR);
        $stmtInsertHistorial->execute();

        // Verifica el estado actual de la mesa
        if ($selectRow['estado_mesa'] == "Ocupado") {
            // Actualizar el estado y la fecha de salida en la base de datos
            $sqlLibre = "UPDATE tbl_mesas SET id_estado_mesa = (SELECT id_estado FROM tbl_estado WHERE estado_nombre = 'Libre'), id_camarero = NULL WHERE id_mesa = :idMesa";
            $stmtLibre = $conn->prepare($sqlLibre);
            $stmtLibre->bindParam(':idMesa', $idMesa, PDO::PARAM_INT);
            $stmtLibre->execute();
        } else {
            // Actualizar el estado, la fecha de entrada y asignar el id_camarero en la base de datos
            $sqlOcupa = "UPDATE tbl_mesas SET id_estado_mesa = (SELECT id_estado FROM tbl_estado WHERE estado_nombre = 'Ocupado'), id_camarero = :idCamarero WHERE id_mesa = :idMesa";
            $stmtOcupa = $conn->prepare($sqlOcupa);
            $stmtOcupa->bindParam(':idCamarero', $_SESSION['id_user'], PDO::PARAM_INT);
            $stmtOcupa->bindParam(':idMesa', $idMesa, PDO::PARAM_INT);
            $stmtOcupa->execute();
        }

        // Commit de la transacción
        $conn->commit();
        header("Location: ../home.php?sala=$sala&num_sala=$num_sala&mesa=$mesa&estado=$estadoget");
    } catch (Exception $e) {
        // En caso de error, realizar un rollback
        echo "Error: " . $e->getMessage() . "<br>";
        $conn->rollBack();
    }
} else {
    // Manejar el caso en el que 'id' no está definido o no es un número entero
    echo "Error: El parámetro 'id' no está definido o no es un número entero.";
}

// Cerrar la conexión a la base de datos
$conn = null;
?>
