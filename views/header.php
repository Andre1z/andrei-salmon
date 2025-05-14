<?php
// views/header.php

// Si la función escape() no existe, incluimos el archivo de utilidades.
if (!function_exists('escape')) {
    require_once __DIR__ . '/../src/Helpers/Utils.php';
}

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>andrei | salmon</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Enlace a la hoja de estilos -->
    <link rel="stylesheet" href="assets/css/styles.css">
    <!-- Enlace al favicon -->
    <link rel="icon" type="image/svg+xml" href="assets/images/logo.svg">
</head>
<body>
<header>
    <div class="header-container">
        <h1>andrei | salmon</h1>
        <nav>
            <ul>
                <li><a href="index.php">Inicio</a></li>
                <li><a href="profile.php">Mi perfil</a></li>
                <li><a href="?action=logout">Cerrar sesión</a></li>
            </ul>
        </nav>
        <div class="user-info">
            <?php
            // Se muestra el nombre del usuario si la variable $user ya está definida.
            if (isset($user) && isset($user['username'])) {
                echo "¡Hola, " . escape($user['username']) . "!";
            } else {
                echo "¡Hola, Usuario!";
            }
            ?>
        </div>
    </div>
</header>