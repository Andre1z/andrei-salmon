<?php
// src/Models/Message.php

class Message {
    private $db;
    
    // Propiedades que representan las columnas de la tabla messages.
    public $id;
    public $sender_id;
    public $recipient_id;
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
     * Crea un nuevo mensaje en la base de datos.
     *
     * Inserta un mensaje enviado de un usuario a otro utilizando una consulta preparada.
     *
     * @param int $senderId ID del usuario que envía el mensaje.
     * @param int $recipientId ID del destinatario del mensaje.
     * @param string $content Contenido del mensaje.
     * @return int|false Retorna el ID del mensaje recién creado o false en caso de fallo.
     */
    public function create($senderId, $recipientId, $content) {
        $stmt = $this->db->prepare('INSERT INTO messages (sender_id, recipient_id, content) VALUES (?, ?, ?)');
        $stmt->bindValue(1, $senderId, SQLITE3_INTEGER);
        $stmt->bindValue(2, $recipientId, SQLITE3_INTEGER);
        $stmt->bindValue(3, $content, SQLITE3_TEXT);
        $result = $stmt->execute();
        
        if ($result) {
            return $this->db->lastInsertRowID();
        }
        return false;
    }
    
    /**
     * Obtiene la bandeja de entrada de un usuario.
     *
     * Recupera todos los mensajes recibidos por un usuario determinado, incluyendo
     * el nombre del remitente, y los ordena de forma descendente por la fecha de creación.
     *
     * @param int $userId El ID del usuario destinatario cuyos mensajes se desean obtener.
     * @return array Un arreglo asociativo de los mensajes.
     */
    public function getInbox($userId) {
        $stmt = $this->db->prepare('
            SELECT m.*, u.username AS sender_username
            FROM messages m
            JOIN users u ON m.sender_id = u.id
            WHERE m.recipient_id = ?
            ORDER BY m.created_at DESC
        ');
        $stmt->bindValue(1, $userId, SQLITE3_INTEGER);
        $result = $stmt->execute();
        
        $messages = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $messages[] = $row;
        }
        return $messages;
    }
    
    /**
     * (Opcional) Obtiene los mensajes enviados por un usuario.
     *
     * Recupera todos los mensajes enviados por un usuario, incluyendo el nombre del destinatario,
     * y los ordena de forma descendente por la fecha de creación.
     *
     * @param int $senderId El ID del usuario remitente cuyos mensajes enviados se desean obtener.
     * @return array Un arreglo asociativo de los mensajes enviados.
     */
    public function getSentMessages($senderId) {
        $stmt = $this->db->prepare('
            SELECT m.*, u.username AS recipient_username
            FROM messages m
            JOIN users u ON m.recipient_id = u.id
            WHERE m.sender_id = ?
            ORDER BY m.created_at DESC
        ');
        $stmt->bindValue(1, $senderId, SQLITE3_INTEGER);
        $result = $stmt->execute();
        
        $messages = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $messages[] = $row;
        }
        return $messages;
    }
}
?>