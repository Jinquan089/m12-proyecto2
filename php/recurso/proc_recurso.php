<?php
session_start();
include '../connection.php';
if (!isset($_SESSION["user"])) {
    header('Location: ./cerrar.php');
    exit();
}

try {
    $stmt = $conn->prepare("SELECT me.id_mesa AS id_mesa, me.nombre_mesa AS nombre_mesa, me.sillas AS sillas, sa.nombre_sala AS sala, tisa.nombre_tipos AS tipo_sala, es.estado_nombre AS estado 
    FROM tbl_mesas me
    INNER JOIN tbl_salas sa ON me.id_sala_mesa = sa.id_sala
    INNER JOIN tbl_tipos_salas tisa ON sa.id_tipos_sala = tisa.id_tipos
    INNER JOIN tbl_estado es ON es.id_estado = me.id_estado_mesa;");
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $options = '';
    foreach ($result as $row) {
        $id_mesa = $row['id_mesa'];
        $options .= "<tr>";
        $options .= "<input type='hidden' name='id_mesa' id='$id_mesa' class='id-recurso'>";
        $options .= "<td>" . $row['tipo_sala'] . "</td>";
        $options .= "<td>" . $row['sala'] . "</td>";
        $options .= "<td>" . $row['nombre_mesa'] . "</td>";
        $options .= "<td class='sillas-cell id-recurso'>" . $row['sillas'] . "</td>";
        $options .= "<td>" . $row['estado'] . "</td>";
        $options .= "<td class='actions-cell'><button type='button' class='btn btn-warning edit-button'>Modificar</button></td>";
        $options .= "</tr>";
    }
echo json_encode($options);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "<br>";
}