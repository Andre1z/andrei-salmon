<?php
// views/profile.php

include_once __DIR__ . '/header.php';

// Verificar que el usuario esté autenticado.
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?action=login");
    exit;
}

// Cargar la conexión a la base de datos.
require_once __DIR__ . '/../config/database.php';
$db = (new Database())->getConnection();

// Incluir el modelo de Usuario.
require_once __DIR__ . '/../src/Models/User.php';
$userModel = new User($db);

// Determinar qué perfil mostrar: el del usuario autenticado por defecto o el pasado por la URL.
$profileUserId = isset($_GET['user_id']) ? (int) $_GET['user_id'] : $_SESSION['user_id'];
$profileUser = $userModel->findById($profileUserId);

if (!$profileUser) {
    echo "<p>Usuario no encontrado.</p>";
    include_once __DIR__ . '/footer.php';
    exit;
}

// Incluir el modelo de publicaciones y obtener las publicaciones de este usuario.
require_once __DIR__ . '/../src/Models/Post.php';
$postModel = new Post($db);
$posts = $postModel->getPostsByUser($profileUserId);
?>

<div class="profile-container">
    <?php if ($profileUserId === $_SESSION['user_id']): ?>
        <h2>Mi Perfil</h2>
    <?php else: ?>
        <h2>Perfil de <?= escape($profileUser['username']); ?></h2>
    <?php endif; ?>

    <a href="index.php" class="btn">Volver al inicio</a>

    <div class="profile-posts">
        <?php if (empty($posts)): ?>
            <p>No hay publicaciones para mostrar.</p>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
                <div class="post">
                    <p><?= escape($post['content']); ?></p>
                    <?php if (!empty($post['image_path'])): ?>
                        <img src="<?= escape($post['image_path']); ?>" alt="Imagen de la publicación">
                    <?php endif; ?>
                    <small><?= escape($post['created_at']); ?></small>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php
include_once __DIR__ . '/footer.php';
?>