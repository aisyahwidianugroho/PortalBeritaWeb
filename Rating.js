document.addEventListener('DOMContentLoaded', function() {
    // Ambil elemen-elemen yang dibutuhkan
    const sendBtn = document.getElementById('sendRatingBtn');
    const closeModalBtn = document.getElementById('closeModal');
    const modalContainer = document.querySelector('.rating-modal-container');
    const ratingInputs = document.querySelectorAll('input[name="rating"]');

    // URL yang dituju setelah rating (Ganti dengan URL login Anda)
    const loginPageUrl = 'login.html';

    /**
     * Fungsi untuk menangani aksi 'Send'
     */
    function handleSendRating() {
        // Cek apakah ada bintang yang dipilih
        let selectedRating = null;
        ratingInputs.forEach(input => {
            if (input.checked) {
                selectedRating = input.value;
            }
        });

        // Tampilkan rating yang dipilih di console (opsional)
        // console.log("Rating yang diberikan:", selectedRating ? selectedRating : "Tidak ada rating");

        // 1. Tampilkan pop-up jendela Windows (alert)
        alert('Terima kasih telah memberikan rating!');

        // 2. Arahkan pengguna ke halaman login
        window.location.href = loginPageUrl;
    }

    /**
     * Fungsi untuk menutup pop-up
     */
    function closeModal() {
        modalContainer.style.display = 'none';
    }

    // Tambahkan event listener untuk tombol 'Send'
    if (sendBtn) {
        sendBtn.addEventListener('click', handleSendRating);
    }

    // Tambahkan event listener untuk tombol 'x' (Close)
    if (closeModalBtn) {
        closeModalBtn.addEventListener('click', closeModal);
    }

    // Opsional: Tutup pop-up jika mengklik di luar modal
    if (modalContainer) {
        modalContainer.addEventListener('click', function(e) {
            // Pastikan yang diklik adalah container, bukan modal itu sendiri
            if (e.target === modalContainer) {
                closeModal();
            }
        });
    }
});