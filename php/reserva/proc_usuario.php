<?php
session_start();
include '../connection.php';
if (!isset($_SESSION["user"])) {
    header('Location: ./cerrar.php');
    exit();
}

try {
    $stmt = $conn->prepare("SELECT us.user AS user, us.nombre AS nombre, us.apellido1 AS ape1, us.apellido2 AS ape2, 
    us.correo AS correo, us.telefono AS telf, ro.nombre_rol AS rol FROM tbl_users us 
    INNER JOIN tbl_roles ro ON us.rol = ro.id_roles;");
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $options = '';
    foreach ($result as $row) {
        $options .= "<tr>";
        $options .= "<td>" . $row['user'] . "</td>";
        $options .= "<td>" . $row['nombre'] . "</td>";
        $options .= "<td>" . $row['ape1'] . "</td>";
        $options .= "<td>" . $row['ape2'] . "</td>";
        $options .= "<td>" . $row['correo'] . "</td>";
        $options .= "<td>" . $row['telf'] . "</td>";
        $options .= "<td>" . $row['rol'] . "</td>";
        $options .= "</tr>";
    }
echo json_encode($options);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "<br>";
}