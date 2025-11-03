document.addEventListener("DOMContentLoaded", function() {
    const form = document.querySelector(".signup-form");

    form.addEventListener("submit", function(e) {
        e.preventDefault();

        const email = document.getElementById("email").value.trim();
        const username = document.getElementById("username").value.trim();
        const password = document.getElementById("password").value.trim();

        // Validasi sederhana
        if (email === "" || username === "" || password === "") {
            alert("⚠️ Harap isi semua kolom terlebih dahulu!");
            return;
        }

        // Simpan data user ke localStorage
        localStorage.setItem("registeredEmail", email);
        localStorage.setItem("registeredUser", username);
        localStorage.setItem("registeredPass", password);

        // Tambahkan status agar Login.html tahu kalau Sign Up berhasil
        localStorage.setItem("signupSuccess", "true");

        // Arahkan ke halaman login
        window.location.href = "Login.html";
    });
});
