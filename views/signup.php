<?php
// views/signup.php

include_once __DIR__ . '/header.php';
?>

<div class="signup-container">
    <h2>Regístrate en andrei | salmon</h2>
    
    <?php if (isset($error) && !empty($error)): ?>
        <p class="error"><?php echo escape($error); ?></p>
    <?php endif; ?>
    
    <div class="signup-form">
        <form method="post">
            <input type="hidden" name="action" value="signup">
            <div class="form-group">
                <label for="signup-username">Username:</label>
                <input id="signup-username" type="text" name="username" placeholder="Nombre de usuario" required>
            </div>
            <div class="form-group">
                <label for="signup-password">Contraseña:</label>
                <input id="signup-password" type="password" name="password" placeholder="Contraseña" required>
            </div>
            <button type="submit" class="btn">Crear cuenta</button>
        </form>
        <p class="login-link">
            ¿Ya tienes una cuenta? <a href="index.php?action=login">Inicia sesión</a>
        </p>
    </div>
</div>

<?php
include_once __DIR__ . '/footer.php';
?>