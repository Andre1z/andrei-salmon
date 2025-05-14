<?php
// views/login.php

include_once __DIR__ . '/header.php';
?>

<div class="login-container">
    <h2>Bienvenido a andrei | salmon</h2>
    
    <?php if (isset($error) && !empty($error)): ?>
        <p class="error"><?php echo escape($error); ?></p>
    <?php endif; ?>
    
    <!-- Únicamente se muestra el formulario de inicio de sesión -->
    <div class="login-form">
        <h3>Inicia sesión</h3>
        <form method="post">
            <input type="hidden" name="action" value="login">
            <div class="form-group">
                <label for="login-username">Username:</label>
                <input id="login-username" type="text" name="username" placeholder="Nombre de usuario" required>
            </div>
            <div class="form-group">
                <label for="login-password">Contraseña:</label>
                <input id="login-password" type="password" name="password" placeholder="Contraseña" required>
            </div>
            <button type="submit" class="btn">Iniciar sesión</button>
        </form>
    </div>
</div>

<?php
include_once __DIR__ . '/footer.php';
?>