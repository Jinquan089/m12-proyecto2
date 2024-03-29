<?php
session_start();
include '../connection.php';
if (!isset($_SESSION["user"])) {
    header('Location: ./cerrar.php');
    exit();
}

try {
    include("../connection.php");
    $tipo_sala = $_POST['tipo_sala'];

    $sql = "SELECT DISTINCT sillas FROM tbl_tipos_salas tsa 
            INNER JOIN tbl_salas sa ON tsa.id_tipos = sa.id_tipos_sala 
            INNER JOIN tbl_mesas me ON sa.id_sala = me.id_sala_mesa 
            INNER JOIN tbl_estado esta ON me.id_estado_mesa = esta.id_estado
            WHERE nombre_tipos = :tipo_sala ORDER BY sillas ASC";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":tipo_sala", $tipo_sala);
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $options = '<option selected disabled>Numero de persona</option>';
    foreach ($result as $row) {
        $num_sillas = $row['sillas'];
        $options .= '<option value="' . $num_sillas . '">' . $num_sillas . '</option>';
    }

    echo json_encode($options);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "<br>";
}

