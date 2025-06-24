// ===============================
// Cookie Helpers
// ===============================
function setCookie(naam, waarde, dagen) {
    const d = new Date();
    d.setTime(d.getTime() + (dagen * 24 * 60 * 60 * 1000));
    const expires = "expires=" + d.toUTCString();
    document.cookie = naam + "=" + encodeURIComponent(waarde) + ";" + expires + ";path=/";
}

function getCookie(naam) {
    const naamEQ = naam + "=";
    const ca = document.cookie.split(';');
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i].trim();
        if (c.indexOf(naamEQ) === 0) return decodeURIComponent(c.substring(naamEQ.length));
    }
    return null;
}

// ===============================
// Globale variabelen
// ===============================
const csvPad = "data/simulatie.csv";
const chartInstances = {};
let csvData = [];
let tijdstippen = [];

// Buffers voor instellingen
let geselecteerdeVeldenBuffer = [];
let formaatBuffer = null;
let grafiekTypeBuffer = null;

// ===============================
// CSV inladen
// ===============================
Papa.parse(csvPad, {
    download: true,
    header: true,
    delimiter: ";",
    complete: function(result) {
        csvData = result.data;
        tijdstippen = csvData.map(row => row["Tijdstip"]);
    }
});

// ===============================
// Event listeners: opslaan wijzigingen in buffer
// ===============================
document.getElementById("grafiekSelector").addEventListener("change", () => {
    geselecteerdeVeldenBuffer = Array.from(document.querySelectorAll("#grafiekSelector input:checked"))
        .map(cb => cb.value);
});

document.getElementById("formaatSelect").addEventListener("change", (e) => {
    formaatBuffer = e.target.value;
});

document.getElementById("grafiekTypeSelect").addEventListener("change", (e) => {
    grafiekTypeBuffer = e.target.value;
});

// ===============================
// "Pas toe" knop
// ===============================
document.getElementById("pasToeBtn").addEventListener("click", function() {
    // Verwijder bestaande grafieken
    Object.keys(chartInstances).forEach(verwijderGrafiek);

    // Gebruik buffers of huidige waarden
    const velden = geselecteerdeVeldenBuffer.length > 0
        ? geselecteerdeVeldenBuffer
        : Array.from(document.querySelectorAll("#grafiekSelector input:checked")).map(cb => cb.value);

    const formaat = formaatBuffer || document.getElementById("formaatSelect").value;
    const grafiekType = grafiekTypeBuffer || document.getElementById("grafiekTypeSelect").value;

    // Cookies opslaan
    setCookie("geselecteerdeVelden", JSON.stringify(velden), 7);
    setCookie("formaat", formaat, 7);
    setCookie("grafiekType", grafiekType, 7);

    // Grafieken tonen
    velden.forEach(maakGrafiek);
});

// ===============================
// Functie: grafiek maken
// ===============================
function maakGrafiek(veldnaam) {
    if (chartInstances[veldnaam]) return;

    const id = veldnaam.replace(/\s+/g, '_');
    const formaat = parseInt(document.getElementById("formaatSelect").value);
    const grafiekType = document.getElementById("grafiekTypeSelect").value;

    const container = document.createElement("div");
    container.className = "chart-container";
    container.id = "container_" + id;

    const titel = document.createElement("h3");
    titel.innerText = veldnaam;

    const canvas = document.createElement("canvas");
    canvas.id = "chart_" + id;
    canvas.style.maxWidth = formaat + "px";
    canvas.style.width = "100%";
    canvas.style.height = "400px";

    container.appendChild(titel);
    container.appendChild(canvas);
    document.getElementById("grafieken").appendChild(container);

    const yData = csvData.map(row => parseFloat((row[veldnaam] || "0").replace(",", ".")));

    const isCirkeldiagram = ['pie', 'doughnut', 'polarArea'].includes(grafiekType);
    const labels = isCirkeldiagram ? tijdstippen.slice(0, yData.length) : tijdstippen;
    const achtergrondKleuren = isCirkeldiagram
        ? tijdstippen.map((_, i) => `hsl(${(i * 40) % 360}, 70%, 60%)`)
        : 'rgba(75, 192, 192, 0.2)';

    const config = {
        type: grafiekType,
        data: {
            labels: labels,
            datasets: [{
                label: veldnaam,
                data: yData,
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: achtergrondKleuren,
                borderWidth: 1,
                fill: grafiekType === 'line',
                tension: grafiekType === 'line' ? 0.4 : 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'top' },
                title: { display: true, text: veldnaam }
            },
            scales: isCirkeldiagram ? {} : {
                x: {
                    ticks: { maxRotation: 90, minRotation: 45 }
                },
                y: {
                    beginAtZero: true
                }
            }
        }
    };

    const chart = new Chart(canvas.getContext("2d"), config);
    chartInstances[veldnaam] = chart;
}

// ===============================
// Functie: grafiek verwijderen
// ===============================
function verwijderGrafiek(veldnaam) {
    const id = veldnaam.replace(/\s+/g, '_');
    if (chartInstances[veldnaam]) {
        chartInstances[veldnaam].destroy();
        delete chartInstances[veldnaam];
    }
    const el = document.getElementById("container_" + id);
    if (el) el.remove();
}

// ===============================
// Herstel cookies bij laden
// ===============================
window.addEventListener("load", function() {
    const formaat = getCookie("formaat");
    if (formaat) document.getElementById("formaatSelect").value = formaat;

    const grafiekType = getCookie("grafiekType");
    if (grafiekType) document.getElementById("grafiekTypeSelect").value = grafiekType;

    const geselecteerde = getCookie("geselecteerdeVelden");
    if (geselecteerde) {
        try {
            const velden = JSON.parse(geselecteerde);
            velden.forEach(veldnaam => {
                const checkbox = document.querySelector(`#grafiekSelector input[value="${veldnaam}"]`);
                if (checkbox) {
                    checkbox.checked = true;
                }
            });
        } catch (e) {
            console.error("Ongeldige cookie data:", e);
        }
    }
});
