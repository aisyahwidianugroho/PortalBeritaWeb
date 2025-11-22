// ==============================
// Kategori_news.js (FINAL FIX)
// ==============================

// ------------------------------
// 1️⃣ TANGGAL OTOMATIS HEADER
// ------------------------------
const headerTop = document.querySelector(".header-top");
const bulan = [
  "JANUARY", "FEBRUARY", "MARCH", "APRIL", "MAY", "JUNE",
  "JULY", "AUGUST", "SEPTEMBER", "OCTOBER", "NOVEMBER", "DECEMBER"
];
const now = new Date();
headerTop.textContent = `${bulan[now.getMonth()]} ${now.getDate()}, ${now.getFullYear()}`;


// ------------------------------
// 2️⃣ MENU BUTTON (☰) → DROPDOWN
// ------------------------------
const menuBtn = document.getElementById("menuBtn");
const dropdownMenu = document.getElementById("dropdownMenu");

menuBtn.addEventListener("click", (e) => {
  e.stopPropagation();  
  dropdownMenu.classList.toggle("show-menu");
});

// Klik di luar → tutup dropdown
document.addEventListener("click", (e) => {
  if (!dropdownMenu.contains(e.target)) {
    dropdownMenu.classList.remove("show-menu");
  }
});


// ------------------------------
// 3️⃣ IKON USER → LOGIN
// ------------------------------
const userIcon = document.querySelector(".right span");
if (userIcon) {
  userIcon.style.cursor = "pointer";
  userIcon.addEventListener("click", () => {
    window.location.href = "Login.html";
  });
}


// ------------------------------
// 4️⃣ ANIMASI SCROLL ARTIKEL
// ------------------------------
window.addEventListener("scroll", () => {
  const articles = document.querySelectorAll(".news-item");
  const triggerBottom = window.innerHeight * 0.85;

  articles.forEach(article => {
    const top = article.getBoundingClientRect().top;
    if (top < triggerBottom) {
      article.classList.add("show");
    }
  });
});
