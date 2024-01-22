<?php
session_start();
include '../connection.php';
if (!isset($_SESSION["user"])) {
    header('Location: ./cerrar.php');
    exit();
}

try {
    if (isset($_POST['id_user'])) {
        $id_user = $_POST['id_user'];
        $stmt = $conn->prepare("SELECT us.id_user AS id_user, us.user AS user, us.nombre AS nombre, us.apellido1 AS ape1, us.apellido2 AS ape2, 
        us.correo AS correo, us.telefono AS telf, ro.nombre_rol AS rol FROM tbl_users us 
        INNER JOIN tbl_roles ro ON us.rol = ro.id_roles WHERE us.id_user = :id_user;");
        $stmt->bindParam(":id_user", $id_user);
    } else {
        $stmt = $conn->prepare("SELECT us.id_user AS id_user, us.user AS user, us.nombre AS nombre, us.apellido1 AS ape1, us.apellido2 AS ape2, 
        us.correo AS correo, us.telefono AS telf, ro.nombre_rol AS rol FROM tbl_users us 
        INNER JOIN tbl_roles ro ON us.rol = ro.id_roles;");
    }

    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($result);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "<br>";
}