<?php
// src/Models/Like.php

class Like {
    private $db;
    
    // Propiedades que representan las columnas de la tabla de likes
    public $id;
    public $post_id;
    public $user_id;
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
     * Crea un "me gusta" para una publicación.
     *
     * @param int $postId El ID de la publicación.
     * @param int $userId El ID del usuario que da "me gusta".
     * @return int|false Retorna el ID del like recién creado o false en caso de fallo.
     */
    public function create($postId, $userId) {
        $stmt = $this->db->prepare('INSERT INTO likes (post_id, user_id) VALUES (?, ?)');
        $stmt->bindValue(1, $postId, SQLITE3_INTEGER);
        $stmt->bindValue(2, $userId, SQLITE3_INTEGER);
        $result = $stmt->execute();
        
        if ($result) {
            return $this->db->lastInsertRowID();
        }
        return false;
    }
    
    /**
     * Elimina un "me gusta" de una publicación.
     *
     * @param int $postId El ID de la publicación.
     * @param int $userId El ID del usuario que desea retirar el like.
     * @return bool Retorna true si se eliminó el like correctamente.
     */
    public function remove($postId, $userId) {
        $stmt = $this->db->prepare('DELETE FROM likes WHERE post_id = ? AND user_id = ?');
        $stmt->bindValue(1, $postId, SQLITE3_INTEGER);
        $stmt->bindValue(2, $userId, SQLITE3_INTEGER);
        return $stmt->execute() ? true : false;
    }
    
    /**
     * Verifica si un usuario ya ha dado "me gusta" a una publicación.
     *
     * @param int $postId El ID de la publicación.
     * @param int $userId El ID del usuario.
     * @return array|false Retorna un arreglo asociativo con el like si existe, o false en caso contrario.
     */
    public function exists($postId, $userId) {
        $stmt = $this->db->prepare('SELECT id FROM likes WHERE post_id = ? AND user_id = ?');
        $stmt->bindValue(1, $postId, SQLITE3_INTEGER);
        $stmt->bindValue(2, $userId, SQLITE3_INTEGER);
        $result = $stmt->execute();
        
        return $result->fetchArray(SQLITE3_ASSOC);
    }
}
?>