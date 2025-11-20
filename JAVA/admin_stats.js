// Ambil data dari elemen HTML (dataset)
const publish = parseInt(document.getElementById("stat-publish").dataset.value);
const pending = parseInt(document.getElementById("stat-pending").dataset.value);
const jurnalis = parseInt(document.getElementById("stat-jurnalis").dataset.value);
const pembaca = parseInt(document.getElementById("stat-pembaca").dataset.value);

// ========== GRAFIK BATANG ==========
new Chart(document.getElementById("chartArtikel"), {
    type: "bar",
    data: {
        labels: ["Terbit", "Pending", "Jurnalis"],
        datasets: [{
            label: "Total",
            data: [publish, pending, jurnalis],
            backgroundColor: ["#2563eb", "#d97706", "#047857"]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});

// ========== GRAFIK LINE ==========
let pembacaPerBulan = Array(12).fill(0);
let avg = pembaca / 12;

// isi rata
pembacaPerBulan = pembacaPerBulan.map(() => avg);

new Chart(document.getElementById("chartPembaca"), {
    type: "line",
    data: {
        labels: ["Jan","Feb","Mar","Apr","Mei","Jun","Jul","Agu","Sep","Okt","Nov","Des"],
        datasets: [{
            label: "Total Pembaca",
            data: pembacaPerBulan,
            borderWidth: 2,
            borderColor: "#7c3aed",
            tension: 0.35
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});
