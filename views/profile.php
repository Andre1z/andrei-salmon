<?php
// views/profile.php

// Iniciar sesión y verificar que esté activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Incluir la configuración de la base de datos y cargar el modelo de usuario
require_once __DIR__ . '/../config/database.php';
$db = (new Database())->getConnection();

require_once __DIR__ . '/../src/Models/User.php';
$userModel = new User($db);
$user = $userModel->findById($_SESSION['user_id']);

$message = '';

// Procesamiento de formularios
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Actualización del nombre de usuario
    if (isset($_POST['action']) && $_POST['action'] === 'update_username') {
        $new_username = trim($_POST['new_username'] ?? '');
        if (!empty($new_username)) {
            $stmt = $db->prepare("UPDATE users SET username = ? WHERE id = ?");
            $stmt->bindValue(1, $new_username, SQLITE3_TEXT);
            $stmt->bindValue(2, $_SESSION['user_id'], SQLITE3_INTEGER);
            if ($stmt->execute()) {
                $message = "Nombre de usuario actualizado correctamente.";
                // Recargar la información del usuario para reflejar el cambio
                $user = $userModel->findById($_SESSION['user_id']);
            } else {
                $message = "Error al actualizar el nombre de usuario.";
            }
        } else {
            $message = "El nombre de usuario no puede estar vacío.";
        }
    }

    // Actualización de la contraseña
    if (isset($_POST['action']) && $_POST['action'] === 'update_password') {
        $old_password = $_POST['old_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        if ($new_password !== $confirm_password) {
            $message = "La nueva contraseña y su confirmación no coinciden.";
        } else {
            if (password_verify($old_password, $user['password'])) {
                $new_hash = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
                $stmt->bindValue(1, $new_hash, SQLITE3_TEXT);
                $stmt->bindValue(2, $_SESSION['user_id'], SQLITE3_INTEGER);
                if ($stmt->execute()) {
                    $message = "Contraseña actualizada correctamente.";
                } else {
                    $message = "Error al actualizar la contraseña.";
                }
            } else {
                $message = "La contraseña antigua no coincide.";
            }
        }
    }
}

// Ahora que ya se dispone de la información del usuario, se incluye el header.
include_once __DIR__ . '/header.php';
?>

<div class="container">
    <div class="profile-container">
        <h2>Mi Perfil</h2>
        <?php if (!empty($message)): ?>
            <p class="message-info"><?php echo escape($message); ?></p>
        <?php endif; ?>

        <!-- Sección para actualizar el nombre de usuario -->
        <div class="profile-section">
            <h3>Nombre de Usuario</h3>
            <form method="post" class="update-form">
                <input type="hidden" name="action" value="update_username">
                <div class="form-group">
                    <label for="new_username">Nombre de Usuario:</label>
                    <input id="new_username" type="text" name="new_username" value="<?php echo escape($user['username']); ?>" required>
                </div>
                <button type="submit" class="btn">Actualizar</button>
            </form>
        </div>

        <!-- Sección para actualizar la contraseña -->
        <div class="profile-section">
            <h3>Cambiar Contraseña</h3>
            <form method="post" class="update-form">
                <input type="hidden" name="action" value="update_password">
                <div class="form-group">
                    <label for="old_password">Contraseña Antigua:</label>
                    <input id="old_password" type="password" name="old_password" required>
                </div>
                <div class="form-group">
                    <label for="new_password">Nueva Contraseña:</label>
                    <input id="new_password" type="password" name="new_password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Repetir Nueva Contraseña:</label>
                    <input id="confirm_password" type="password" name="confirm_password" required>
                </div>
                <button type="submit" class="btn">Actualizar Contraseña</button>
            </form>
        </div>
    </div>
</div>

<?php
include_once __DIR__ . '/footer.php';
?>