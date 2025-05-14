<?php
// views/home.php

// Incluir el encabezado (header). Este archivo declare el DOCTYPE, <head> y <body>.
include_once __DIR__ . '/header.php';

// Asegurarse de que exista la conexión a la base de datos.
// Si $db ya estuviera definido en un nivel superior, no se volverá a cargar.
if (!isset($db)) {
    require_once __DIR__ . '/../config/database.php';
    $db = (new Database())->getConnection();
}

// Obtener información del usuario autenticado.
if (!isset($user)) {
    require_once __DIR__ . '/../src/Models/User.php';
    $userModel = new User($db);
    $user = $userModel->findById($_SESSION['user_id']);
}

// Instanciar el modelo de publicaciones y obtener el feed (publicaciones propias y de amigos).
require_once __DIR__ . '/../src/Models/Post.php';
$postModel = new Post($db);
$posts = $postModel->getFeedPosts($_SESSION['user_id']);

// Para cada publicación, obtener los comentarios asociados.
require_once __DIR__ . '/../src/Models/Comment.php';
$commentModel = new Comment($db);
foreach ($posts as &$post) {
    $post['comments'] = $commentModel->getCommentsByPostId($post['post_id']);
}
unset($post);

// Obtener la lista de otros usuarios (para conectar) excluyendo al usuario actual.
$others = $userModel->getAllExcept($_SESSION['user_id']);

// Obtener la bandeja de entrada de mensajes para el usuario actual.
require_once __DIR__ . '/../src/Models/Message.php';
$messageModel = new Message($db);
$messages = $messageModel->getInbox($_SESSION['user_id']);
?>

<main>
    <!-- Panel izquierdo: Herramientas y contactos -->
    <div class="left pane">
        <h3>Mis herramientas</h3>
        <ul>
            <li><a href="?action=profile">Mi perfil</a></li>
            <li><a href="#messages">Mensajes</a></li>
        </ul>
        <h3>Mis contactos</h3>
        <ul>
            <?php foreach ($others as $other): ?>
                <li>
                    <?php echo escape($other['username']); ?>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="action" value="connect">
                        <input type="hidden" name="friend_id" value="<?php echo $other['id']; ?>">
                        <button type="submit">Connect</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <!-- Panel central: Feed de publicaciones -->
    <div class="center pane">
        <h3>Publicaciones</h3>
        <!-- Formulario para crear una nueva publicación -->
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="action" value="post">
            <textarea name="content" placeholder="Escribe algo nuevo" required></textarea>
            <input type="file" name="image" accept=".jpg,.jpeg,.png">
            <button type="submit">Enviar</button>
        </form>

        <!-- Recorrido de cada publicación -->
        <?php foreach ($posts as $post): ?>
            <div class="post">
                <strong>
                    <a href="?action=profile&user_id=<?php echo $post['post_user_id']; ?>">
                        <?php echo escape($post['username']); ?>
                    </a>:
                </strong>
                <p><?php echo escape($post['content']); ?></p>
                <?php if (!empty($post['image_path'])): ?>
                    <img src="<?php echo escape($post['image_path']); ?>" alt="Post Image">
                <?php endif; ?>
                <small><?php echo escape($post['created_at']); ?></small>
                
                <!-- Botón para alternar "me gusta" -->
                <form method="post" style="display:inline;">
                    <input type="hidden" name="action" value="like">
                    <input type="hidden" name="post_id" value="<?php echo $post['post_id']; ?>">
                    <button type="submit">Me gusta (<?php echo $post['like_count']; ?>)</button>
                </form>
                
                <!-- Sección de comentarios -->
                <div class="comments" style="margin-top:10px;">
                    <strong>Comentarios:</strong>
                    <?php if (!empty($post['comments'])): ?>
                        <?php foreach ($post['comments'] as $comment): ?>
                            <div style="margin: 5px 0 0 15px;">
                                <strong><?php echo escape($comment['username']); ?>:</strong>
                                <p style="margin:0;"><?php echo escape($comment['content']); ?></p>
                                <small><?php echo escape($comment['created_at']); ?></small>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p style="margin: 5px 0 0 15px; color:#999;">No hay comentarios.</p>
                    <?php endif; ?>
                    
                    <!-- Formulario para agregar un comentario -->
                    <form method="post" style="margin-top: 5px; margin-left:15px;">
                        <input type="hidden" name="action" value="comment">
                        <input type="hidden" name="post_id" value="<?php echo $post['post_id']; ?>">
                        <textarea name="comment_content" rows="2" placeholder="Escribe un comentario" required></textarea>
                        <button type="submit">Comentar</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Panel derecho: Mensajes -->
    <div class="right pane" id="messages">
        <h3>Mensajes</h3>
        <!-- Formulario para enviar un mensaje -->
        <form method="post">
            <input type="hidden" name="action" value="send_message">
            <select name="recipient_id" required>
                <option value="" disabled selected>Selecciona un amigo</option>
                <?php foreach ($others as $other): ?>
                    <option value="<?php echo escape($other['id']); ?>"><?php echo escape($other['username']); ?></option>
                <?php endforeach; ?>
            </select>
            <textarea name="message_content" placeholder="Escribe tu mensaje" required></textarea>
            <button type="submit">Enviar</button>
        </form>
        
        <h4>Bandeja de entrada</h4>
        <?php foreach ($messages as $message): ?>
            <div class="message">
                <strong>From <?php echo escape($message['sender_username']); ?>:</strong>
                <p><?php echo escape($message['content']); ?></p>
                <small><?php echo escape($message['created_at']); ?></small>
            </div>
        <?php endforeach; ?>
    </div>
</main>

<?php
// Incluir el pie de página (footer)
include_once __DIR__ . '/footer.php';
?>