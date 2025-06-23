<?php
session_start();

// Ruta del archivo SQLite (se creará automáticamente si no existe)
define('DB_PATH', 'sistema_login.db');

try {
    $pdo = new PDO("sqlite:" . DB_PATH);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Crear la tabla si no existe (esto es útil en Replit)
    $pdo->exec("CREATE TABLE IF NOT EXISTS usuarios (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT NOT NULL UNIQUE,
        email TEXT NOT NULL,
        password TEXT NOT NULL
    )");
    
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
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
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
