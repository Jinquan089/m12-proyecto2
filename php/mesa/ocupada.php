<?php
session_start();
include '../connection.php';
if (!isset($_SESSION["user"])) {
    header('Location: ./cerrar.php');
    exit();
}

// Asegúrate de que el parámetro 'id' está definido
if (isset($_POST['id_mesa'])) {
    try {
        $idMesa = $_POST['id_mesa'];
        // Iniciamos la transacción
        $conn->beginTransaction();

        // Consulta para obtener el estado actual de la mesa
        $sqlSelect = "SELECT me.id_sala_mesa, me.id_camarero, es.estado_nombre as estado_mesa 
                      FROM tbl_mesas me 
                      INNER JOIN tbl_estado es ON me.id_estado_mesa = es.id_estado 
                      WHERE me.id_mesa = :idMesa";
        $stmtSelect = $conn->prepare($sqlSelect);
        $stmtSelect->bindParam(':idMesa', $idMesa);
        $stmtSelect->execute();
        $selectRow = $stmtSelect->fetch(PDO::FETCH_ASSOC);

        // Verifica el estado actual de la mesa
        if ($selectRow['estado_mesa'] == "Ocupado") {
            // Actualizar el estado y la fecha de salida en la base de datos
            $sqlLibre = "UPDATE tbl_mesas SET id_estado_mesa = (SELECT id_estado FROM tbl_estado WHERE estado_nombre = 'Libre'), id_camarero = NULL WHERE id_mesa = :idMesa";
            $stmtLibre = $conn->prepare($sqlLibre);
            $stmtLibre->bindParam(':idMesa', $idMesa);
            $stmtLibre->execute();
            $estado = "Libre";
            $sqlInsertHistorial = "UPDATE tbl_historial SET estado = :estado, fecha_hora_libre = NOW() WHERE id_mesa = :id_mesa;";
            $stmtInsertHistorial = $conn->prepare($sqlInsertHistorial);
            $stmtInsertHistorial->bindParam(':estado', $estado);
            $stmtInsertHistorial->bindParam(':id_mesa', $idMesa);
            $stmtInsertHistorial->execute();
        } else {
            // Actualizar el estado, la fecha de entrada y asignar el id_camarero en la base de datos
            $sqlOcupa = "UPDATE tbl_mesas SET id_estado_mesa = (SELECT id_estado FROM tbl_estado WHERE estado_nombre = 'Ocupado'), id_camarero = :idCamarero WHERE id_mesa = :idMesa";
            $stmtOcupa = $conn->prepare($sqlOcupa);
            $stmtOcupa->bindParam(':idCamarero', $_SESSION['id_user']);
            $stmtOcupa->bindParam(':idMesa', $idMesa);
            $stmtOcupa->execute();
            $estado = "Ocupado";
            $sqlInsertHistorial = "INSERT INTO tbl_historial (fecha_hora_libre, estado, id_mesa) VALUES (NULL, :estado, :id_mesa)";
            $stmtInsertHistorial = $conn->prepare($sqlInsertHistorial);
            $stmtInsertHistorial->bindParam(':id_mesa', $idMesa);
            $stmtInsertHistorial->bindParam(':estado', $estado);
            $stmtInsertHistorial->execute();
        }
        // Commit de la transacción
        $conn->commit();
    } catch (Exception $e) {
        // En caso de error, realizar un rollback
        echo "Error: " . $e->getMessage() . "<br>";
        $conn->rollBack();
    }
}
