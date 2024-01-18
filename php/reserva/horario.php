<?php
session_start();
include '../connection.php';
if (!isset($_SESSION["user"])) {
    header('Location: ./cerrar.php');
    exit();
}

try {
    $stmt = $conn->prepare("SELECT * FROM tbl_horario;");
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $options = '<option selected disabled>Tipo de Sala</option>';
    foreach ($result as $row) {
        $id_hora = $row["id_franja_horaria"];
        $horainicio = $row['franja_horaria_inicio'];
        $horafin = $row['franja_horaria_fin'];
        $options .= "<option value='$id_hora'>$horainicio - $horafin</option>";
    }
echo json_encode($options);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "<br>";
}