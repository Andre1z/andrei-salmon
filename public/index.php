<?php
// public/index.php

session_start();

// Cargar la configuración de la base de datos y los helpers
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/Helpers/Utils.php';

// Se crea la conexión a la base de datos.
$dbConnection = (new Database())->getConnection();

// Detectamos la acción a realizar (vía GET o POST)
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
        
    case 'profile':
        // Verificamos que el usuario esté autenticado
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit;
        }
        // Cargamos la información del usuario para la vista de perfil
        require_once __DIR__ . '/../src/Models/User.php';
        $userModel = new User($dbConnection);
        $user = $userModel->findById($_SESSION['user_id']);
        require_once __DIR__ . '/../views/profile.php';
        break;
        
    default:
        // Si no se especifica ninguna acción:
        if (isset($_SESSION['user_id'])) {
            // Obtenemos la información del usuario para el header
            require_once __DIR__ . '/../src/Models/User.php';
            $userModel = new User($dbConnection);
            $user = $userModel->findById($_SESSION['user_id']);
            // Cargamos la vista principal (feed de publicaciones)
            require_once __DIR__ . '/../views/home.php';
        } else {
            // El usuario no está autenticado: redirigimos a index.php?action=login
            header("Location: index.php?action=login");
            exit;
        }
        break;
}
?>