<?php
session_start();
if (!isset($_SESSION["user"])) {
    header('Location: ./cerrar.php');
    exit();
}
include '../connection.php';
try {
    $sql = "SELECT hi.id_historial, hi.fecha_hora_ocupado AS fechaocu, hi.fecha_hora_libre AS fechalib, hi.estado AS estado, me.nombre_mesa AS mesa, us.nombre AS camarero
    FROM tbl_historial hi
    INNER JOIN tbl_mesas me ON me.id_mesa = hi.id_mesa
    INNER JOIN tbl_users us ON us.id_user = hi.id_user";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($stmt->rowCount() > 0) {
            $options = "";
            foreach ($result as $row) {
                $options .= "<tr>";
                $options .= "<td>" . $row['camarero'] . "</td>";
                $options .= "<td>" . $row['mesa'] . "</td>";
                $options .= "<td>" . $row['fechaocu'] . "</td>";
                $options .= "<td>" . $row['fechalib'] . "</td>";
                $options .= "<td>" . $row['estado'] . "</td>";
                $options .= "</tr>";
            }
    } else {
        $options .= "<tr><td colspan='6'>No hay historico</td></tr>";
    }
    echo json_encode ($options);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "<br>";
}