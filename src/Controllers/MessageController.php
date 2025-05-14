<?php
// src/Controllers/MessageController.php

class MessageController {
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
     * Envía un mensaje a otro usuario.
     *
     * Este método verifica que el usuario esté autenticado, extrae el identificador
     * del destinatario y el contenido del mensaje recibidos vía POST, y, si son válidos,
     * inserta el mensaje en la tabla "messages". Luego redirige al front controller (index.php)
     * para actualizar la vista, por ejemplo, la sección de mensajes.
     */
    public function sendMessage() {
        // Verificar que el usuario tenga sesión iniciada.
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $senderId = $_SESSION['user_id'];
            $recipientId = isset($_POST['recipient_id']) ? (int) $_POST['recipient_id'] : 0;
            $messageContent = trim($_POST['message_content'] ?? '');

            // Validar que el destinatario sea válido y que se haya ingresado un mensaje.
            if ($recipientId <= 0 || empty($messageContent)) {
                header('Location: index.php');
                exit;
            }

            // Preparar la consulta para insertar el mensaje en la base de datos.
            $stmt = $this->db->prepare('INSERT INTO messages (sender_id, recipient_id, content) VALUES (?, ?, ?)');
            $stmt->bindValue(1, $senderId, SQLITE3_INTEGER);
            $stmt->bindValue(2, $recipientId, SQLITE3_INTEGER);
            $stmt->bindValue(3, $messageContent, SQLITE3_TEXT);
            $stmt->execute();

            // Redirigir al front controller, por ejemplo, a la sección de "Mensajes".
            header('Location: index.php#messages');
            exit;
        } else {
            // Si la solicitud no es POST, se redirige al front controller.
            header('Location: index.php');
            exit;
        }
    }
}
?>