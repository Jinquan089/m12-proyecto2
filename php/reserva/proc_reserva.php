<?php
session_start();
include '../connection.php';
if (!isset($_SESSION["user"])) {
    header('Location: ./cerrar.php');
    exit();
}

try {
    $nomCli = $_POST['nomCli'];
    $silla = $_POST['silla'];
    $hora = $_POST['hora'].":00:00";
    $mesa = $_POST['mesa'];
    $fecha = $_POST['fecha'];
    $stmt = $conn->prepare("INSERT INTO tbl_reserva (nombre_persona, num_personas, hora, id_mesa_reservada, fecha_reserva)
    VALUES (:nombreCliente, :silla, :hora, :mesa, :fecha)");
    $stmt->bindParam(':nombreCliente', $nomCli);
    $stmt->bindParam(':silla', $silla);
    $stmt->bindParam(':hora', $hora);
    $stmt->bindParam(':mesa', $mesa);
    $stmt->bindParam(':fecha', $fecha);
    $stmt->execute();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "<br>";
}