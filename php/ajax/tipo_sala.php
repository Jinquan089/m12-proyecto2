<?php
session_start();
include '../connection.php';
if (!isset($_SESSION["user"])) {
    header('Location: ./cerrar.php');
    exit();
}

try {
    $stmt_tipos_salas = $conn->prepare("SELECT id_tipos, nombre_tipos, aforo FROM tbl_tipos_salas");
    $stmt_tipos_salas->execute();
    $result_tipos_sala = $stmt_tipos_salas->fetchAll(PDO::FETCH_ASSOC);
    $options = '<option selected disabled>Tipo de Sala</option>';
    foreach ($result_tipos_sala as $row) {
        $nombre_tipos = $row['nombre_tipos'];
        $id_tipos = $row['id_tipos'];
        $options .= "<option value='$nombre_tipos'>$nombre_tipos </option>";
    }
echo json_encode($options);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "<br>";
}