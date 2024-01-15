<?php
// Inicia la sesión
session_start();
// Destruir todas las variables de sesion
session_unset();
// Cierra la sesión.
session_destroy();
// Redirige al usuario a la página de inicio de sesión u otra página deseada.
header("Location: ./borraralert.php");
exit;