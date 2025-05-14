<?php
// src/Controllers/PostController.php

class PostController {
    private $db;
    
    /**
     * Constructor que recibe la conexión activa a la base de datos.
     *
     * @param SQLite3 $db Conexión a la base de datos.
     */
    public function __construct($db) {
        $this->db = $db;
    }
    
    /**
     * Crea una nueva publicación.
     * 
     * Valida que el usuario esté autenticado, procesa el contenido del post y, si se adjunta
     * una imagen, verifica su validez y la mueve al directorio correspondiente. Luego inserta
     * la publicación en la base de datos.
     */
    public function createPost() {
        // Verificar que el usuario haya iniciado sesión
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php");
            exit;
        }

        // Solo procesamos si es una petición POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id'];
            $content = trim($_POST['content'] ?? '');
            $image_path = null;
            
            // Validar que el contenido no esté vacío (según necesidad)
            if (empty($content)) {
                header("Location: index.php?error=EmptyContent");
                exit;
            }

            // Procesar carga de imagen si existe
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $file_tmp_path = $_FILES['image']['tmp_name'];
                $file_name = basename($_FILES['image']['name']);
                $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                $allowed_exts = ['jpg', 'jpeg', 'png'];

                // Validar extensión permitida
                if (in_array($file_ext, $allowed_exts)) {
                    // Generar un nombre de archivo único
                    $new_file_name = time() . '-' . $userId . '-' . $file_name;
                    // En esta estructura, las imágenes se guardan en "public/assets/images/"
                    $destination = "assets/images/" . $new_file_name;
                    
                    // Mover el archivo al destino
                    if (move_uploaded_file($file_tmp_path, $destination)) {
                        $image_path = $destination;
                    }
                }
            }

            // Insertar la publicación en la base de datos
            $stmt = $this->db->prepare('INSERT INTO posts (user_id, content, image_path) VALUES (?, ?, ?)');
            $stmt->bindValue(1, $userId, SQLITE3_INTEGER);
            $stmt->bindValue(2, $content, SQLITE3_TEXT);
            $stmt->bindValue(3, $image_path, SQLITE3_TEXT);
            $stmt->execute();
        }

        // Redirigir de vuelta al front controller
        header("Location: index.php");
        exit;
    }
    
    /**
     * Alterna la acción de "me gusta" para una publicación.
     * 
     * Si el usuario ya ha dado "me gusta", se elimina el registro; de lo contrario, se inserta.
     * Verifica también que la petición se realice mediante POST y que el usuario esté autenticado.
     */
    public function toggleLike() {
        // Verificar que el usuario esté autenticado
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php");
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id'];
            $postId = isset($_POST['post_id']) ? (int) $_POST['post_id'] : 0;
            
            if ($postId <= 0) {
                header("Location: index.php");
                exit;
            }
            
            // Verificar si el usuario ya dio "me gusta" a esta publicación
            $stmt = $this->db->prepare('SELECT id FROM likes WHERE post_id = ? AND user_id = ?');
            $stmt->bindValue(1, $postId, SQLITE3_INTEGER);
            $stmt->bindValue(2, $userId, SQLITE3_INTEGER);
            $result = $stmt->execute();
            $existing_like = $result->fetchArray(SQLITE3_ASSOC);
            
            if ($existing_like) {
                // Si ya existe el like, procede a eliminarlo (toggle off)
                $deleteStmt = $this->db->prepare('DELETE FROM likes WHERE id = ?');
                $deleteStmt->bindValue(1, $existing_like['id'], SQLITE3_INTEGER);
                $deleteStmt->execute();
            } else {
                // Si no existe, inserta el like
                $insertStmt = $this->db->prepare('INSERT INTO likes (post_id, user_id) VALUES (?, ?)');
                $insertStmt->bindValue(1, $postId, SQLITE3_INTEGER);
                $insertStmt->bindValue(2, $userId, SQLITE3_INTEGER);
                $insertStmt->execute();
            }
            
            // Redirigir de vuelta al front controller
            header("Location: index.php");
            exit;
        } else {
            header("Location: index.php");
            exit;
        }
    }
}
?>