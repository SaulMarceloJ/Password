<?php
session_start();

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'sistema_login');

try {
    $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

function registrarUsuario($username, $email, $password) {
    global $pdo;
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $pdo->prepare("INSERT INTO usuarios (username, email, password) VALUES (?, ?, ?)");
    return $stmt->execute([$username, $email, $hashedPassword]);
}

function verificarLogin($username, $password) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    return ($user && password_verify($password, $user['password'])) ? $user : false;
}

function actualizarUsuario($id, $nuevoUsername, $nuevoEmail, $nuevoPassword = null) {
    global $pdo;

    if ($nuevoPassword) {
        $hashedPassword = password_hash($nuevoPassword, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("UPDATE usuarios SET username = ?, email = ?, password = ? WHERE id = ?");
        return $stmt->execute([$nuevoUsername, $nuevoEmail, $hashedPassword, $id]);
    } else {
        $stmt = $pdo->prepare("UPDATE usuarios SET username = ?, email = ? WHERE id = ?");
        return $stmt->execute([$nuevoUsername, $nuevoEmail, $id]);
    }
}

function eliminarUsuario($id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
    return $stmt->execute([$id]);
}

?>
