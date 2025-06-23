<?php
require_once 'config.php';

$error = '';

// Procesar login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    // Usar el hash enviado por el formulario, no la contraseña sin hash
    $hashedPassword = $_POST['hashed_password'] ?? '';

    $user = verificarLogin($_POST['username'], $hashedPassword);

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: monitoreo.php");
        exit;
    } else {
        $error = "Usuario o contraseña incorrectos";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="estilos.css"> 

    <!-- Agrega la librería CryptoJS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>
</head>
<body>
    <div class="container">
        <h1>Monitoreo Simulado de Red WiFi</h1>
        <p class="text-center">Inicia sesión para acceder al monitoreo de red.
        </p>

        <?php if ($error): ?>
            <div class="alert error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" onsubmit="return hashearPassword();">
            <div class="form-group">
                <label><br>Usuario:</label>
                <input type="text" name="username" required>
            </div>

            <div class="form-group">
                <label>Contraseña:</label>
                <input type="password" id="password" required>
            </div>

            <input type="hidden" name="hashed_password" id="hashed_password">
            <button type="submit" name="login">Ingresar</button>
        </form>

        <p class="text-center" style="margin-top: 20px;">
        ¿No tienes una cuenta? <a href="registrar.php">Regístrate aquí</a></p>
    
    </div>

    <script>
        function hashearPassword() {
            const passwordInput = document.getElementById('password');
            const hashedPassword = CryptoJS.SHA256(passwordInput.value).toString();
            document.getElementById('hashed_password').value = hashedPassword;
            // Limpiamos la contraseña en texto plano antes de enviar
            passwordInput.value = '';
            return true;
        }
    </script>
</body>
</html>
