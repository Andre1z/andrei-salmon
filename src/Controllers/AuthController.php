<?php
// src/Controllers/AuthController.php

class AuthController {
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
     * Gestiona el inicio de sesión.
     * Si la petición es POST, valida las credenciales y establece la sesión.
     * En caso de fallo, carga la vista de login mostrando un mensaje de error.
     */
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            // Prepara la consulta para obtener el usuario.
            $stmt = $this->db->prepare('SELECT id, password FROM users WHERE username = ?');
            $stmt->bindValue(1, $username, SQLITE3_TEXT);
            $result = $stmt->execute();
            $user = $result->fetchArray(SQLITE3_ASSOC);

            // Verificar si el usuario existe y la contraseña es correcta.
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                header('Location: index.php');
                exit;
            } else {
                $error = 'Login failed. Incorrect username or password.';
                // Se carga la vista de login y se muestra el error.
                require_once __DIR__ . '/../../views/login.php';
            }
        } else {
            // Si no es una petición POST, muestra el formulario de login.
            require_once __DIR__ . '/../../views/login.php';
        }
    }

    /**
     * Gestiona el registro de un nuevo usuario.
     * Recibe los datos vía POST y almacena un registro en la base de datos.
     * Si el registro es exitoso, inicia la sesión y redirige al usuario.
     * En caso contrario, muestra la vista de registro con un mensaje de error.
     */
    public function signup() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $passwordInput = $_POST['password'] ?? '';
            $hashedPassword = password_hash($passwordInput, PASSWORD_DEFAULT);

            // Inserta el nuevo usuario en la base de datos.
            $stmt = $this->db->prepare('INSERT INTO users (username, password) VALUES (?, ?)');
            $stmt->bindValue(1, $username, SQLITE3_TEXT);
            $stmt->bindValue(2, $hashedPassword, SQLITE3_TEXT);
            $result = $stmt->execute();

            if ($result) {
                $_SESSION['user_id'] = $this->db->lastInsertRowID();
                header('Location: index.php');
                exit;
            } else {
                $error = 'Signup failed. Username may already exist.';
                // Se carga la vista de registro mostrando el error.
                require_once __DIR__ . '/../../views/signup.php';
            }
        } else {
            // Si la petición no es POST, se muestra el formulario de registro.
            require_once __DIR__ . '/../../views/signup.php';
        }
    }

    /**
     * Gestiona la finalización de la sesión del usuario.
     * Destruye la sesión y redirige al front controller.
     */
    public function logout() {
        session_destroy();
        header('Location: index.php');
        exit;
    }
}
?>