<?php
// config/database.php

class Database {
    /**
     * @var SQLite3
     */
    private $db;

    /**
     * Constructor que inicializa la conexión
     */
    public function __construct() {
        $this->connect();
    }

    /**
     * Establece la conexión con la base de datos y realiza la inicialización
     */
    private function connect() {
        // Definir la ruta al archivo de la base de datos.
        // Se ubica en el directorio "database" que es hermano de "config"
        $db_path = __DIR__ . "/../database/salmon.db";
        
        // Abrir la base de datos SQLite.
        $this->db = new SQLite3($db_path);
        
        // Inicializar la base de datos creando las tablas si no existen.
        $this->initializeDatabase();
    }

    /**
     * Ejecuta las sentencias SQL para crear las tablas necesarias.
     */
    private function initializeDatabase() {
        // Tabla de usuarios
        $createUsers = "CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT UNIQUE NOT NULL,
            password TEXT NOT NULL
        )";

        // Tabla de publicaciones
        $createPosts = "CREATE TABLE IF NOT EXISTS posts (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            content TEXT NOT NULL,
            image_path TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY(user_id) REFERENCES users(id)
        )";

        // Tabla de conexiones (amistades)
        $createConnections = "CREATE TABLE IF NOT EXISTS connections (
            user_id INTEGER NOT NULL,
            friend_id INTEGER NOT NULL,
            PRIMARY KEY(user_id, friend_id),
            FOREIGN KEY(user_id) REFERENCES users(id),
            FOREIGN KEY(friend_id) REFERENCES users(id)
        )";

        // Tabla de mensajes
        $createMessages = "CREATE TABLE IF NOT EXISTS messages (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            sender_id INTEGER NOT NULL,
            recipient_id INTEGER NOT NULL,
            content TEXT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY(sender_id) REFERENCES users(id),
            FOREIGN KEY(recipient_id) REFERENCES users(id)
        )";

        // Tabla de comentarios
        $createComments = "CREATE TABLE IF NOT EXISTS comments (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            post_id INTEGER NOT NULL,
            user_id INTEGER NOT NULL,
            content TEXT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY(post_id) REFERENCES posts(id),
            FOREIGN KEY(user_id) REFERENCES users(id)
        )";

        // Tabla de "me gusta" (likes)
        $createLikes = "CREATE TABLE IF NOT EXISTS likes (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            post_id INTEGER NOT NULL,
            user_id INTEGER NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            UNIQUE(post_id, user_id),
            FOREIGN KEY(post_id) REFERENCES posts(id),
            FOREIGN KEY(user_id) REFERENCES users(id)
        )";

        // Ejecutar las sentencias SQL
        $this->db->exec($createUsers);
        $this->db->exec($createPosts);
        $this->db->exec($createConnections);
        $this->db->exec($createMessages);
        $this->db->exec($createComments);
        $this->db->exec($createLikes);
    }

    /**
     * Devuelve la conexión activa a la base de datos.
     *
     * @return SQLite3
     */
    public function getConnection() {
        return $this->db;
    }
}
?>