<?php
// src/Models/Post.php

class Post {
    private $db;
    
    // Propiedades de la publicación (opcional, a modo de anotación)
    public $id;
    public $user_id;
    public $content;
    public $image_path;
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
     * Crea una nueva publicación.
     *
     * Inserta un registro en la tabla "posts" con el contenido, el ID de usuario y la ruta
     * de la imagen (si la hay). Retorna el ID del post recién creado o false en caso de fallo.
     *
     * @param int $userId El ID del usuario que realiza el post.
     * @param string $content El contenido textual de la publicación.
     * @param string|null $imagePath La ruta de la imagen cargada (puede ser null si no hay imagen).
     * @return int|false
     */
    public function createPost($userId, $content, $imagePath = null) {
        $stmt = $this->db->prepare('INSERT INTO posts (user_id, content, image_path) VALUES (?, ?, ?)');
        $stmt->bindValue(1, $userId, SQLITE3_INTEGER);
        $stmt->bindValue(2, $content, SQLITE3_TEXT);
        $stmt->bindValue(3, $imagePath, SQLITE3_TEXT);
        $result = $stmt->execute();
        
        if ($result) {
            return $this->db->lastInsertRowID();
        }
        return false;
    }
    
    /**
     * Recupera todas las publicaciones de un usuario en particular.
     *
     * Realiza un JOIN con la tabla "users" para incluir el nombre del usuario y
     * cuenta la cantidad de "me gusta" que tiene cada post.
     *
     * @param int $userId El ID del usuario.
     * @return array Un arreglo asociativo con los posts del usuario.
     */
    public function getPostsByUser($userId) {
        $stmt = $this->db->prepare('
            SELECT 
                posts.id AS post_id,
                posts.user_id AS post_user_id,
                posts.content,
                posts.image_path,
                posts.created_at,
                users.username,
                (SELECT COUNT(*) FROM likes WHERE likes.post_id = posts.id) AS like_count
            FROM posts
            JOIN users ON posts.user_id = users.id
            WHERE posts.user_id = ?
            ORDER BY posts.created_at DESC
        ');
        $stmt->bindValue(1, $userId, SQLITE3_INTEGER);
        $result = $stmt->execute();
        
        $posts = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $posts[] = $row;
        }
        return $posts;
    }
    
    /**
     * Recupera el feed de publicaciones para un usuario.
     *
     * El feed incluye las publicaciones propias y las de los amigos (conexiones).
     * Se realiza un JOIN con la tabla "users" y se cuenta la cantidad de "me gusta" de cada post.
     *
     * @param int $userId El ID del usuario.
     * @return array Un arreglo asociativo con los posts para el feed.
     */
    public function getFeedPosts($userId) {
        $stmt = $this->db->prepare('
            SELECT 
                posts.id AS post_id,
                posts.user_id AS post_user_id,
                posts.content,
                posts.image_path,
                posts.created_at,
                users.username,
                (SELECT COUNT(*) FROM likes WHERE likes.post_id = posts.id) AS like_count
            FROM posts
            JOIN users ON posts.user_id = users.id
            WHERE posts.user_id = ?
               OR posts.user_id IN (SELECT friend_id FROM connections WHERE user_id = ?)
            ORDER BY posts.created_at DESC
        ');
        $stmt->bindValue(1, $userId, SQLITE3_INTEGER);
        $stmt->bindValue(2, $userId, SQLITE3_INTEGER);
        $result = $stmt->execute();
        
        $posts = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $posts[] = $row;
        }
        return $posts;
    }
}
?>