document.addEventListener("DOMContentLoaded", function () {
  document.title = "The Surabaya iNews";

  // ====== ALERT LOGIN ======
  const status = localStorage.getItem("loginStatus");
  const username = localStorage.getItem("username");

  if (status === "success") {
    alert("✅ Login berhasil! Selamat datang, " + username + "!");
    localStorage.removeItem("loginStatus");
  }

  // ====== MENU POPUP ======
  const menuBtn = document.querySelector(".menu-btn");
  const dropdown = document.querySelector(".dropdown-menu");

  menuBtn.addEventListener("click", () => {
    dropdown.classList.toggle("show");
  });

  // Klik di luar menu untuk menutupnya
  document.addEventListener("click", (e) => {
    if (!menuBtn.contains(e.target) && !dropdown.contains(e.target)) {
      dropdown.classList.remove("show");
    }
  });
});
