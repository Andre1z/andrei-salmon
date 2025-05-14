<?php
// src/Controllers/CommentController.php

class CommentController {
    private $db;

    /**
     * Constructor que recibe la conexión a la base de datos.
     *
     * @param SQLite3 $db Conexión activa a la base de datos.
     */
    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Agrega un comentario a un post.
     *
     * Este método verifica que el usuario esté autenticado, valida los datos recibidos por POST
     * (el identificador del post y el contenido del comentario) y, si son válidos, inserta el comentario
     * en la tabla de comentarios de la base de datos. Tras la inserción, redirige al usuario al front controller.
     */
    public function addComment() {
        // Verificar que el usuario haya iniciado sesión
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id'];
            $postId = isset($_POST['post_id']) ? (int) $_POST['post_id'] : 0;
            $commentContent = trim($_POST['comment_content'] ?? '');

            // Validar que se hayan recibido un ID de post y un contenido
            if ($postId === 0 || empty($commentContent)) {
                header('Location: index.php');
                exit;
            }

            // Preparar la consulta para insertar el comentario
            $stmt = $this->db->prepare('INSERT INTO comments (post_id, user_id, content) VALUES (?, ?, ?)');
            $stmt->bindValue(1, $postId, SQLITE3_INTEGER);
            $stmt->bindValue(2, $userId, SQLITE3_INTEGER);
            $stmt->bindValue(3, $commentContent, SQLITE3_TEXT);
            $stmt->execute();

            // Redirigir de vuelta al front controller
            header('Location: index.php');
            exit;
        } else {
            // Si no se recibe una petición POST, redirigimos al usuario.
            header('Location: index.php');
            exit;
        }
    }
}
?>