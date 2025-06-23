let urls = {
    "https://www.google.com": "HTTPS",
    "https://www.youtube.com": "HTTPS",
    "http://example.com": "HTTP",
    "https://cloudflare-dns.com": "DNS"
};

let protocolUsage = { HTTP: 0, HTTPS: 0, DNS: 0 };
let domainTraffic = {}; //Objeto para almacenar el tráfico acumulado por dominio
let totalLatency = 0, latencyCount = 0;

const ctxProtocol = document.getElementById("protocolChart").getContext("2d");
const ctxDomain = document.getElementById("domainChart").getContext("2d");

const protocolChart = new Chart(ctxProtocol, {
    type: 'bar',
    data: {
    labels: ["HTTP", "HTTPS", "DNS"],
    datasets: [{
        label: "% Uso por Protocolo",
        data: [0, 0, 0],
        backgroundColor: ['#4e73df', '#1cc88a', '#e74a3b']
        }]
    },
    options: {
    responsive: true,
    scales: {
        y: { beginAtZero: true, max: 100 }
    }
    }
});

const domainChart = new Chart(ctxDomain, {
    type: 'pie',
    data: {
    labels: [],
    datasets: [{
        label: 'Tráfico por Dominio',
        data: [],
        backgroundColor: ['#36b9cc', '#f6c23e', '#858796', '#e74a3b', '#1cc88a', '#4e73df']
    }]
    },
    options: { responsive: true }
});

async function medirLatencia(url) {
    try {
        const inicio = performance.now();
        await fetch(url, { mode: 'no-cors' });
        const fin = performance.now();
        return fin - inicio;
    } catch {
        return Math.random() * 200 + 100;
    }
}

async function actualizarDatos() {
    protocolUsage = { HTTP: 0, HTTPS: 0, DNS: 0 };
    domainTraffic = {};// Reinicia el tráfico antes de actualizar
    totalLatency = 0;
    latencyCount = 0;

    for (const [url, protocolo] of Object.entries(urls)) {
        const latencia = await medirLatencia(url);
        totalLatency += latencia;
        latencyCount++;

        const dominio = new URL(url).hostname;
        protocolUsage[protocolo] += 1;
        // Acumula tráfico estimado basado en latencia multiplicada por un número aleatorio
        domainTraffic[dominio] = (domainTraffic[dominio] || 0) + Math.floor(latencia * Math.random());
    }

    //se llama a actualizarGraficas() para refrescar visualizaciones
    actualizarGraficas();
}

// Actualización de datos en la gráfica
function actualizarGraficas() {
    const promedio = (totalLatency / latencyCount).toFixed(2);
    document.getElementById("latencyPanel").innerText = `Latencia promedio: ${promedio} ms`;

    const total = Object.values(protocolUsage).reduce((a, b) => a + b, 0);
    protocolChart.data.datasets[0].data = [
        ((protocolUsage.HTTP / total) * 100).toFixed(1),
        ((protocolUsage.HTTPS / total) * 100).toFixed(1),
        ((protocolUsage.DNS / total) * 100).toFixed(1)
    ];
    protocolChart.update();

    // Actualiza etiquetas y datos del gráfico de tráfico por dominio
    domainChart.data.labels = Object.keys(domainTraffic);
    domainChart.data.datasets[0].data = Object.values(domainTraffic);
    domainChart.update();
}

function detectarProtocolo(url) {
    if (url.startsWith("https://")) return "HTTPS";
    if (url.startsWith("http://")) return "HTTP";
    if (url.includes("dns") || url.includes("53")) return "DNS";
    return "HTTP";
}

function agregarNuevaURL() {
    const input = document.getElementById("nuevaURL");
    const nueva = input.value.trim();

    // Validación básica de URL
    if (!nueva.startsWith("http://") && !nueva.startsWith("https://")) {
        alert("Ingresa una URL válida que comience con http o https");
        return;
    }

    // Prevención contra código malicioso
    const patronXSS = /<|>|javascript:|data:|script|onerror|onload/gi;
    if (patronXSS.test(nueva)) {
        alert("La URL contiene código sospechoso.");
        return;
    }

    // Evitar duplicados
    if (!urls[nueva]) {
        const protocolo = detectarProtocolo(nueva);
        urls[nueva] = protocolo;
        window.open(nueva, "_blank"); // abrir URL en nueva pestaña
        input.value = "";
    } else {
        alert("Esta URL ya está en monitoreo.");
    }
}


actualizarDatos();
setInterval(actualizarDatos, 10000);

//clearInterval(window.intervalID);
//window.intervalID = setInterval(actualizarDatos, 10000);
