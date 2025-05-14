<?php
// src/Models/Comment.php

class Comment {
    private $db;
    
    // Propiedades que representan las columnas de la tabla de comentarios
    public $id;
    public $post_id;
    public $user_id;
    public $content;
    public $created_at;
    
    /**
     * Constructor que recibe la conexión activa a la base de datos.
     *
     * @param SQLite3 $db Conexión activa a la base de datos.
     */
    public function __construct($db) {
        $this->db = $db;
    }
    
    /**
     * Crea un nuevo comentario en la base de datos.
     *
     * @param int $postId El ID de la publicación a la que se asocia el comentario.
     * @param int $userId El ID del usuario que realiza el comentario.
     * @param string $content El contenido del comentario.
     * @return int|false Retorna el ID del comentario recién creado o false en caso de fallo.
     */
    public function create($postId, $userId, $content) {
        $stmt = $this->db->prepare('INSERT INTO comments (post_id, user_id, content) VALUES (?, ?, ?)');
        $stmt->bindValue(1, $postId, SQLITE3_INTEGER);
        $stmt->bindValue(2, $userId, SQLITE3_INTEGER);
        $stmt->bindValue(3, $content, SQLITE3_TEXT);
        $result = $stmt->execute();
        
        if ($result) {
            // Retornamos el ID del nuevo comentario.
            return $this->db->lastInsertRowID();
        }
        return false;
    }
    
    /**
     * Obtiene todos los comentarios de una publicación ordenados por fecha ascendente.
     *
     * @param int $postId El ID de la publicación.
     * @return array Un arreglo asociativo con los comentarios y el nombre de usuario asociado.
     */
    public function getCommentsByPostId($postId) {
        $stmt = $this->db->prepare('
            SELECT c.*, u.username 
            FROM comments c 
            JOIN users u ON c.user_id = u.id 
            WHERE c.post_id = ? 
            ORDER BY c.created_at ASC
        ');
        $stmt->bindValue(1, $postId, SQLITE3_INTEGER);
        $result = $stmt->execute();
        
        $comments = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $comments[] = $row;
        }
        return $comments;
    }
}
?>