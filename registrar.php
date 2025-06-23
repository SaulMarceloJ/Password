<?php
require_once 'config.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    // Validar que hayan enviado los hashes
    if (empty($_POST['hashed_password']) || empty($_POST['hashed_confirm_password'])) {
        $error = "Por favor, completa las contraseñas.";
    } else {
        // Compara hashes
        if ($_POST['hashed_password'] !== $_POST['hashed_confirm_password']) {
            $error = "Las contraseñas no coinciden.";
        } else {
            if (registrarUsuario($_POST['username'], $_POST['email'], $_POST['hashed_password'])) {
                $success = "¡Registro exitoso! Ahora puedes iniciar sesión";
            } else {
                $error = "Error al registrar el usuario (¿usuario/email ya existe?)";
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="estilos.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>
</head>
<body>
<div class="container">
    <h1>Registro de Usuario</h1>
    <?php if ($error): ?>
        <div class="alert error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="alert success"><?= htmlspecialchars($success) ?></div>
        <p><a href="index.php">Inicia sesión aquí</a></p>
    <?php else: ?>
    <form method="POST" onsubmit="return hashearPassword();">
        <div class="form-group">
            <label>Usuario:</label>
            <input type="text" name="username" required>
        </div>
        <div class="form-group">
            <label>Email:</label>
            <input type="email" name="email" required>
        </div>
        <div class="form-group">
            <label>Contraseña:</label>
            <input type="password" id="password" required>
        </div>
        <div class="form-group">
            <label>Confirmar Contraseña:</label>
            <input type="password" id="confirm_password" required>
        </div>
        <input type="hidden" name="hashed_password" id="hashed_password">
        <input type="hidden" name="hashed_confirm_password" id="hashed_confirm_password">
        <button type="submit" name="register">Registrarse</button>
    </form>

        <p class="text-center" style="margin-top: 20px;">
        ¿Ya tienes una cuenta? <a href="index.php">Inicia sesión</a></p>
    
    <?php endif; ?>
</div>

<script>
    function hashearPassword() {
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('confirm_password');

        if (passwordInput.value !== confirmPasswordInput.value) {
            alert('Las contraseñas no coinciden');
            return false;
        }

        const hashedPassword = CryptoJS.SHA256(passwordInput.value).toString();
        const hashedConfirmPassword = CryptoJS.SHA256(confirmPasswordInput.value).toString();

        document.getElementById('hashed_password').value = hashedPassword;
        document.getElementById('hashed_confirm_password').value = hashedConfirmPassword;

        passwordInput.value = '';
        confirmPasswordInput.value = '';

        return true;
    }

</script>

</body>
</html>

