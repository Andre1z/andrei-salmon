<?php
// src/Controllers/ConnectionController.php

class ConnectionController {
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
     * Agrega una conexión (amistad) entre el usuario autenticado y otro usuario seleccionado.
     *
     * Este método valida que el usuario esté autenticado, verifica el ID del amigo recibido
     * por POST y, de ser correcto, inserta la nueva conexión en la tabla "connections".
     * Finalmente, redirige al front controller para actualizar la interfaz.
     */
    public function addConnection() {
        // Verificar que el usuario tenga sesión iniciada.
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id'];
            $friendId = isset($_POST['friend_id']) ? (int) $_POST['friend_id'] : 0;

            // Validar que se haya seleccionado un amigo válido
            if ($friendId <= 0) {
                header('Location: index.php');
                exit;
            }

            // Prepara la consulta para insertar la conexión. Se usa INSERT OR IGNORE para evitar duplicados.
            $stmt = $this->db->prepare('INSERT OR IGNORE INTO connections (user_id, friend_id) VALUES (?, ?)');
            $stmt->bindValue(1, $userId, SQLITE3_INTEGER);
            $stmt->bindValue(2, $friendId, SQLITE3_INTEGER);
            $stmt->execute();

            // Redirige nuevamente al front controller tras registrar la conexión.
            header('Location: index.php');
            exit;
        } else {
            // Si la petición no es POST, redirigir al front controller.
            header('Location: index.php');
            exit;
        }
    }
}
?>