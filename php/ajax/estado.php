<?php
session_start();
include '../connection.php';
if (!isset($_SESSION["user"])) {
    header('Location: ./cerrar.php');
    exit();
}
try {
    $sqlEstados = "SELECT DISTINCT estado_nombre FROM tbl_estado";
    $stmtEstados = $conn->prepare($sqlEstados);
    $stmtEstados->execute();
    $resultEstados = $stmtEstados->fetchAll(PDO::FETCH_ASSOC);
    // Iterar sobre los resultados y generar las opciones del dropdown
    $options = '<option selected disabled>Seleccione el estado</option>';
    foreach ($resultEstados as $rowEstado) {
    $nombreEstado = $rowEstado['estado_nombre'];
    $options .='<option value="' . $nombreEstado . '">' . $nombreEstado . '</option>';
    }
    echo json_encode($options);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "<br>";
}