<?php
session_start();

// Verifica si hay una sesión iniciada
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); // Redirige al login si no hay sesión
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoreo Simulado de Red WiFi</title>
    <link rel="stylesheet" href="estilos.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>
    <div class="container">
        <h1>Monitoreo Simulado de Red WiFi</h1>

        <p class="text-center" style="margin-bottom: 18px;">
            <strong>Bienvenido <?= htmlspecialchars($_SESSION['username']) ?>!</strong>
        </p>

        <p class="text-center" style="margin-bottom: 28px;">
            Monitorea el estado de tu red WiFi, visualiza la latencia promedio y los protocolos utilizados.<br>
        </p>

        <div id="inputPanel" class="panel" style="text-align:center;">
            <p>Agrega nuevas URLs para seguimiento:</p> <br>
            <input type="text" id="nuevaURL" placeholder="https://ejemplo.com" style="margin-bottom:10px;">
            <button onclick="agregarNuevaURL()">Agregar URL al monitoreo</button>
        </div>

        <div id="latencyPanel" class="panel">
            Latencia promedio: <span id="latencyValue">calculando...</span>
        </div>

        <div class="panel">
            <canvas id="protocolChart"></canvas>
        </div>
        <div class="panel">
            <canvas id="domainChart"></canvas>
        </div>

        <p class="text-center" style="margin-bottom: 0;">
            <a href="logout.php">Cerrar sesión</a>
        </p>
    </div>

    <script src="script.js"></script>
</body>
</html>
