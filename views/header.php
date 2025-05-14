<?php
// views/header.php

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
    <link rel="stylesheet" href="assets/css/styles.css">
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
            // Si la variable $user ya está definida, se muestra el nombre; de lo contrario se consulta via sesión
            if (isset($user) && isset($user['username'])) {
                echo "¡Hola, " . escape($user['username']) . "!";
            } else {
                // Alternativamente, si se guardara el username en la sesión, se podría hacer:
                echo "¡Hola, Usuario!";
            }
            ?>
        </div>
    </div>
</header>