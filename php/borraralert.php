<?php
session_start();
if (isset($_SESSION["user"])) {
    header('Location: ./cerrar.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <script>
        // Borra 'bienvenidaMostrada' del localStorage
        localStorage.removeItem('bienvenidaMostrada');
        window.location.href = '../index.php';
    </script>
</head>
</html>