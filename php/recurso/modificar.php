<?php
session_start();
include '../connection.php';
if (!isset($_SESSION["user"])) {
    header('Location: ./cerrar.php');
    exit();
}

try {
    $id_mesa = $_POST['id_mesa'];
    $sillas = $_POST['sillas'];
    $stmt = $conn->prepare("UPDATE `tbl_mesas` SET `sillas` = :sillas WHERE `id_mesa` = :id_mesa;");
    $stmt->bindParam(":id_mesa", $id_mesa);
    $stmt->bindParam(":sillas", $sillas);
    $stmt->execute();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "<br>";
}