<?php
// views/header.php

// Asegúrate de haber iniciado la sesión y cargado los helpers necesarios (por ejemplo, la función escape())
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
    <!-- Enlace al stylesheet ubicado en public/assets/css/styles.css -->
    <link rel="stylesheet" href="assets/css/styles.css">
    <!-- Enlace al favicon (asegúrate de que la ruta corresponda al archivo correcto) -->
    <link rel="icon" type="image/svg+xml" href="assets/images/logo.svg">
</head>
<body>
<header>
    <div class="header-container">
        <h1>Andrei | salmon</h1>
        <?php if (isset($_SESSION['user_id'])): ?>
            <nav>
                <ul>
                    <li><a href="index.php">Inicio</a></li>
                    <li><a href="?action=profile">Mi perfil</a></li>        
                    <li><a href="?action=logout">Cerrar sesión</a></li>
                </ul>
            </nav>
            <div class="user-info">
                <span>¡Hola, <?= escape($user['username'] ?? 'Usuario') ?>!</span>
            </div>
        <?php else: ?>
            <nav>
                <ul>
                    <li><a href="index.php?action=login">Iniciar sesión</a></li>
                    <li><a href="index.php?action=signup">Regístrate</a></li>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</header>