document.addEventListener("DOMContentLoaded", function() {
      // ✅ CEK: Apakah user baru saja daftar?
    if (localStorage.getItem("signupSuccess") === "true") {
        alert("✅ Pendaftaran berhasil! Silakan login dengan akun Anda.");
        localStorage.removeItem("signupSuccess"); // hapus biar tidak muncul lagi nanti
    }  
  const form = document.querySelector(".login-form");

    form.addEventListener("submit", function(e) {
        e.preventDefault();

        const username = document.getElementById("username").value.trim();
        const password = document.getElementById("password").value.trim();

        if (username === "" || password === "") {
            alert("Harap isi semua kolom terlebih dahulu!");
            return;
        }

        const validUser = localStorage.getItem("registeredUser");
        const validPass = localStorage.getItem("registeredPass");

        // Jika benar → simpan status login dan arahkan ke home.html
        if (username === validUser && password === validPass) {
            // Simpan data ke localStorage
            localStorage.setItem("loginStatus", "success");
            localStorage.setItem("username", username);

            // Pindah ke halaman home
            window.location.href = "home.html";
        } else {
            alert("❌ Username atau password salah!");
        }
    });
});
