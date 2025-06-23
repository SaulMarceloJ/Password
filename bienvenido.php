<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Bienvenido</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <div class="container">
        <h1>¡Bienvenido <?= htmlspecialchars($_SESSION['username']) ?>!</h1>
        <p>Has iniciado sesión correctamente.</p>
        <a href="logout.php">Cerrar sesión</a>
    </div>
</body>
</html>
