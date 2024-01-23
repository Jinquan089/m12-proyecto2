<?php
session_start();
include '../connection.php';
if (!isset($_SESSION["user"])) {
    header('Location: ./cerrar.php');
    exit();
}

try {
    $id_reser = $_POST['id_reser'];
    $stmt = $conn->prepare("DELETE FROM `tbl_reserva` WHERE `id_reserva` = :id_reser;");
    $stmt->bindParam(":id_reser", $id_reser);
    $stmt->execute();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "<br>";
}