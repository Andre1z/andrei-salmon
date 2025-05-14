<?php
// src/Models/User.php

class User {
    private $db;
    
    // Propiedades de la entidad (opcional, para referencia)
    public $id;
    public $username;
    public $password;
    
    /**
     * Constructor que recibe la conexión activa a la base de datos.
     *
     * @param SQLite3 $db Conexión activa a la base de datos.
     */
    public function __construct($db) {
        $this->db = $db;
    }
    
    /**
     * Crea un nuevo usuario en la base de datos.
     *
     * Se espera que la contraseña ya esté hasheada.
     *
     * @param string $username El nombre de usuario.
     * @param string $hashedPassword La contraseña hasheada.
     * @return int|false Retorna el ID del usuario recién creado o false en caso de fallo.
     */
    public function create($username, $hashedPassword) {
        $stmt = $this->db->prepare('INSERT INTO users (username, password) VALUES (?, ?)');
        $stmt->bindValue(1, $username, SQLITE3_TEXT);
        $stmt->bindValue(2, $hashedPassword, SQLITE3_TEXT);
        $result = $stmt->execute();
        
        if ($result) {
            return $this->db->lastInsertRowID();
        }
        return false;
    }
    
    /**
     * Encuentra un usuario por su ID.
     *
     * @param int $id El ID del usuario.
     * @return array|false Retorna un arreglo asociativo con los datos del usuario o false si no se encuentra.
     */
    public function findById($id) {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->bindValue(1, $id, SQLITE3_INTEGER);
        $result = $stmt->execute();
        return $result->fetchArray(SQLITE3_ASSOC);
    }
    
    /**
     * Encuentra un usuario por su nombre de usuario.
     *
     * @param string $username El nombre del usuario.
     * @return array|false Retorna un arreglo asociativo con los datos del usuario o false si no se encuentra.
     */
    public function findByUsername($username) {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE username = ?');
        $stmt->bindValue(1, $username, SQLITE3_TEXT);
        $result = $stmt->execute();
        return $result->fetchArray(SQLITE3_ASSOC);
    }
    
    /**
     * Obtiene una lista de todos los usuarios, excluyendo al usuario con el ID dado.
     *
     * Esto es útil para mostrar una lista de posibles conexiones, evitando incluir al usuario actual.
     *
     * @param int $excludeId El ID del usuario que se quiere excluir.
     * @return array Un arreglo de usuarios.
     */
    public function getAllExcept($excludeId) {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE id != ?');
        $stmt->bindValue(1, $excludeId, SQLITE3_INTEGER);
        $result = $stmt->execute();
        
        $users = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $users[] = $row;
        }
        return $users;
    }
}
?>