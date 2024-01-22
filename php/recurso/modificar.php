<?php
session_start();
include '../connection.php';
if (!isset($_SESSION["user"])) {
    header('Location: ./cerrar.php');
    exit();
}

try {
    $id_user = $_POST['id_user'];
    $user = $_POST['user'];
    $nombre = $_POST['nombre'];
    $ape1 = $_POST['ape1'];
    $ape2 = $_POST['ape2'];
    $correo = $_POST['correo'];
    $telf = $_POST['telf'];
    $stmt = $conn->prepare("UPDATE tbl_users SET 
    user = :user, nombre = :nombre, apellido1 = :ape1 ,apellido2 = :ape2,correo = :correo ,telefono = :telf 
    WHERE id_user = :id_user");
    $stmt->bindParam(":id_user", $id_user);
    $stmt->bindParam(":user", $user);
    $stmt->bindParam(":nombre", $nombre);
    $stmt->bindParam(":ape1", $ape1);
    $stmt->bindParam(":ape2", $ape2);
    $stmt->bindParam(":correo", $correo);
    $stmt->bindParam(":telf", $telf);
    $stmt->execute();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "<br>";
}