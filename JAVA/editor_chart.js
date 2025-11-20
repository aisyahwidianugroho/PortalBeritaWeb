// Ambil angka dari box statistik
let perluReview = parseInt(document.querySelector(".box:nth-child(1) h2").innerText);
let dalamEdit   = parseInt(document.querySelector(".box:nth-child(2) h2").innerText);
let disetujui   = parseInt(document.querySelector(".box:nth-child(3) h2").innerText);

// GRAFIK 1 — Status Artikel
const ctx1 = document.getElementById("chart1").getContext("2d");

new Chart(ctx1, {
    type: "bar",
    data: {
        labels: ["Perlu Review", "Dalam Edit", "Disetujui"],
        datasets: [{
            label: "Jumlah Artikel",
            data: [perluReview, dalamEdit, disetujui],
            backgroundColor: "rgba(108, 99, 255, 0.6)",
            borderColor: "rgba(108, 99, 255, 1)",
            borderWidth: 1,
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1 }
            }
        }
    }
});

// GRAFIK 2 — Perkembangan Bulanan (Dummy Data)
const ctx2 = document.getElementById("chart2").getContext("2d");

new Chart(ctx2, {
    type: "line",
    data: {
        labels: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Aug", "Sep", "Okt", "Nov", "Des"],
        datasets: [{
            label: "Jumlah Artikel",
            data: [0, 0, 1, 0, 2, 0, 3, 1, 0, 0, 0, 0], // bisa kamu ganti kapan saja
            fill: false,
            borderColor: "rgba(108, 99, 255, 1)",
            tension: 0.3,
            pointRadius: 4
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: { beginAtZero: true }
        }
    }
});
