<?php
// public/index.php

session_start();

// Cargar la configuración de la base de datos.
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/Helpers/Utils.php';

// Se crea la conexión a la base de datos.
$dbConnection = (new Database())->getConnection();

/**
 * Detectamos la acción a realizar:
 * Puede venir vía GET o POST (por ejemplo, 'login', 'signup', 'logout', 'post', etc.)
 */
$action = $_GET['action'] ?? ($_POST['action'] ?? '');

// Enrutamiento simple: según el valor de "action" se delega la ejecución al controlador correspondiente.
switch ($action) {
    case 'login':
        require_once __DIR__ . '/../src/Controllers/AuthController.php';
        $authController = new AuthController($dbConnection);
        $authController->login();
        break;
        
    case 'signup':
        require_once __DIR__ . '/../src/Controllers/AuthController.php';
        $authController = new AuthController($dbConnection);
        $authController->signup();
        break;
        
    case 'logout':
        require_once __DIR__ . '/../src/Controllers/AuthController.php';
        $authController = new AuthController($dbConnection);
        $authController->logout();
        break;
        
    case 'post':
        require_once __DIR__ . '/../src/Controllers/PostController.php';
        $postController = new PostController($dbConnection);
        $postController->createPost();
        break;
        
    case 'comment':
        require_once __DIR__ . '/../src/Controllers/CommentController.php';
        $commentController = new CommentController($dbConnection);
        $commentController->addComment();
        break;
        
    case 'like':
        require_once __DIR__ . '/../src/Controllers/PostController.php';
        $postController = new PostController($dbConnection);
        $postController->toggleLike();
        break;
        
    case 'connect':
        require_once __DIR__ . '/../src/Controllers/ConnectionController.php';
        $connectionController = new ConnectionController($dbConnection);
        $connectionController->addConnection();
        break;
        
    case 'send_message':
        require_once __DIR__ . '/../src/Controllers/MessageController.php';
        $messageController = new MessageController($dbConnection);
        $messageController->sendMessage();
        break;
        
    default:
        // Si no se especifica ninguna acción, comprobamos si el usuario ha iniciado sesión.
        if (isset($_SESSION['user_id'])) {
            // Cargar la vista principal (feed de publicaciones).
            require_once __DIR__ . '/../views/home.php';
        } else {
            // Si el usuario no está autenticado, mostrar la vista de login (o combinar login y signup).
            require_once __DIR__ . '/../views/login.php';
        }
        break;
}
?>