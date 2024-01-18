<?php
session_start();
include '../connection.php';
if (!isset($_SESSION["user"])) {
    header('Location: ./cerrar.php');
    exit();
}

try {
    $stmt = $conn->prepare("SELECT me.nombre_mesa AS nombre_mesa, me.sillas AS sillas, sa.nombre_sala AS sala, tisa.nombre_tipos AS tipo_sala, es.estado_nombre AS estado 
    FROM tbl_mesas me
    INNER JOIN tbl_salas sa ON me.id_sala_mesa = sa.id_sala
    INNER JOIN tbl_tipos_salas tisa ON sa.id_tipos_sala = tisa.id_tipos
    INNER JOIN tbl_estado es ON es.id_estado = me.id_estado_mesa;");
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $options = '';
    foreach ($result as $row) {
        $options .= "<tr>";
        $options .= "<td>" . $row['tipo_sala'] . "</td>";
        $options .= "<td>" . $row['sala'] . "</td>";
        $options .= "<td>" . $row['nombre_mesa'] . "</td>";
        $options .= "<td>" . $row['sillas'] . "</td>";
        $options .= "<td>" . $row['estado'] . "</td>";
        $options .= "</tr>";
    }
echo json_encode($options);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "<br>";
}