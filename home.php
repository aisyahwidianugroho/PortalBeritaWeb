<?php
include 'koneksi.php';

$q = mysqli_query($conn, "SELECT * FROM berita WHERE status='dipublikasikan' ORDER BY id_berita DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>The Surabaya iNews</title>
  <link rel="stylesheet" href="home.css">
 <script src="home.js"></script>
</head>
<body>
  <!-- HEADER -->
  <header class="header">
    <div class="header-top">SEPTEMBER 15, 2025</div>

    <div class="header-main">
<!-- Kiri -->
<div class="left">
  <button class="menu-btn">☰</button>
  <div class="dropdown-menu">
    <a href="Kategori(news).html">News</a>
    <a href="#">Economy</a>
    <a href="#">Lifestyle</a>
    <a href="#">Culture</a>
    <a href="#">Sports</a>
    <a href="#">World</a>
    <a href="#">Fashion</a>
  </div>
  <div class="weather">☀ 38° Surabaya</div>
</div>
      <!-- Tengah -->
      <div class="center">
        <img src="Gambar/logo-berita.png" class="logo-img">
        <p class="tagline">PORTAL BERITA TERPERCAYA UNTUK SURABAYA</p>
      </div>
      <!-- Kanan -->
      <div class="right">
        <span><a href="Login.html" target="_self">👤</a></span>
      </div>
    </div>
  </header>

  <!-- NAVIGATION -->
  <nav class="nav">
    <ul class="nav-links">
      <li><a href="Kategori(news).html">News</a></li>
      <li><a href="#">Economy</a></li>
      <li><a href="#">Lifestyle</a></li>
      <li><a href="#">Culture</a></li>
      <li><a href="#">Sports</a></li>
      <li><a href="#">World</a></li>
      <li><a href="#">Fashion</a></li>
    </ul>
  </nav>

  <!-- MAIN CONTENT -->
  <div class="main-container">
    <main class="main-content">
      <!-- Featured -->
      <article class="featured-article">
        <img src="Gambar/Tari-Gending.jpg">
        <div class="featured-content">
          <span class="category-tag">CULTURE</span>
           <h1><a href="detail-isi-berita.html" target="_self" class="featured-title">Budaya Indonesia Pukau Dunia, Tari Gending Sriwijaya Sedot Perhatian di Osaka</a></h1>
          <p class="featured-excerpt">Melalui komunitas tari ArtistSurkis, kekayaan tradisi Nusantara dipertunjukkan penuh warna di ajang budaya Expo 2025 Jepang</p>
        </div>
      </article>

      <!-- Grid Articles -->
      <div class="articles-grid">
        <!-- Card 1 -->
        <article class="article-card">
          <img src="Gambar/Kereta-SRRL.webp" alt="Construction Project" class="article-image">
          <div class="article-content">
            <span class="category-tag">NEWS</span>
            <h2 class="article-title">Bangun Mega Proyek Kereta SRRL Surabaya-Sidoarjo, Kemenhub Pakai Dana Pinjaman Jerman Rp4,2 T</h2>
            <p class="article-excerpt">Pembangunan mega proyek rel kereta api Surabaya-Sidoarjo resmi dimulai. Proyek ini berjalan setelah Kementerian Perhubungan (Kemenhub) RI mendapatkan kucuran dana pinjaman dari Bank Pembangunan Jerman(KfW) senilai 230 juta Euro atau setara Rp4,42 triliun.</p>
          </div>
        </article>

        <!-- Card 2 -->
        <article class="article-card">
          <img src="Gambar/Bank Indo.jpg" alt="Bank Meeting" class="article-image">
          <div class="article-content">
            <span class="category-tag">ECONOMY</span>
            <h2 class="article-title">Bank-Bank Bingung Serap Dana Rp200 Triliun dari Pemerintah, Hanya Sanggup Rp7 Triliun?</h2>
            <p class="article-excerpt">Dana segar sebesar Rp200 triliun telah resmi digelontorkan pemerintah ke lima bank besar Indonesia. Namun alih-alih lega, para petinggi perbankan justru mengaku kebingungan saat menerima guyuran dana jumbo tersebut. Mengapa?</p>
          </div>
        </article>

        <!-- Card 3 -->
        <article class="article-card">
          <img src="Gambar/Wisata-Edelweis.jpg" alt="Edelweiss Flower" class="article-image">
          <div class="article-content">
            <span class="category-tag">LIFESTYLE</span>
            <h2 class="article-title">Pesona Wisata Edelweiss Wonokitri, Lokasi dengan Cerita Tak Terlupakan Bagi Mahasiswa UI</h2>
            <p class="article-excerpt">Perjalanan panjang dari Jakarta ke Pasuruan tak menghalangi semangat mahasiswa Universitas Indonesia (UI) untuk mengabdi di Desa Wisata Edelweiss, Wonokitri, Jawa Timur. Selama kurang lebih satu bulan, mereka menjalankan program bertajuk “SAVANA: Sustainable Action for Village, Agriculture, Nature, and Health.”</p>
          </div>
        </article>

        <!-- Card 4 -->
        <article class="article-card">
          <img src="Gambar/Badminton Prada BC.webp" alt="Badminton Team" class="article-image">
          <div class="article-content">
            <span class="category-tag">SPORTS</span>
            <h2 class="article-title">Prestasi Membanggakan Prada BC di Tengah Dominasi Klub Bulutangkis Raksasa</h2>
            <p class="article-excerpt">Gelaran akbar Kejuaraan Bulutangkis Sirkuit Nasional Kajati Cup Jatim 2025 yang diselenggarakan oleh Kejaksaan Tinggi Jawa Timur pada 1-7 September lalu sukses besar.</p>
          </div>
        </article>

        <!-- Card 5 -->
        <article class="article-card">
          <img src="Gambar/Perang-Gaza.jpg" alt="Gaza Conflict" class="article-image">
          <div class="article-content">
            <span class="category-tag">WORLD</span>
            <h2 class="article-title">Israel Jangkau 200.000 Warga Palestina Tewas-Luka Selama Perang Gaza</h2>
            <p class="article-excerpt">Mantan Kepala Staf Umum Angkatan Bersenjata Israel (IDF), Herzi Halevi, menyebutkan bahwa lebih dari 200.000 warga Palestina di Jalur Gaza tewas atau luka-luka akibat serangan militer Israel.</p>
          </div>
        </article>

        <!-- Card 6 -->
        <article class="article-card">
          <img src="Gambar/Fashion-Show.webp" alt="Fashion Show" class="article-image">
          <div class="article-content">
            <span class="category-tag">FASHION</span>
            <h2 class="article-title">Fashion Show Vastra Jiva Tutup FESyar Jawa 2025 Bank Indonesia Jatim</h2>
            <p class="article-excerpt">Fashion show yang digelar khusus di Atrium Tunjungan Plaza (TP) 6 Surabaya ini mengusung tema “Vastra Jiva”, yang memiliki makna kain kehidupan, yang menggambarkan bagaimana karya busana bukan sekadar estetika, melainkan juga identitas, nilai, serta keberlanjutan.</p>
          </div>
        </article>
      </div>
    </main>

    <!-- SIDEBAR -->
    <aside class="sidebar">
      <!-- Search -->
      <div class="search-box">
        <input type="text" class="search-input" placeholder="Search">
      </div>

      <!-- Sidebar Sections -->
      <div class="news-section">
        <h2 class="section-title">News</h2>
        <div class="news-item">
          <h3>Bangun Mega Proyek Kereta SRRL Surabaya-Sidoarjo, Kemenhub Dana Pinjaman Jerman Rp4,2 T</h3>
          <p>Direktur Lalu Lintas dan Angkutan Ditjen Perkeretaapian Kemenhub, Arif Anwar, menegaskan bahwa pembangunan tahap pertama segera dimulai. Salah satu elemen penting dalam proyek ini adalah pembangunan Dipo Sidotomo sebagai bagian dari fasilitas penunjang.</p>
        </div>
        <div class="news-item">
          <h3>Kasus Campak di Pamekasan Melonjak, Ini Penyebab yang Ditemukan</h3>
          <p>Selain imunisasi massal, langkah lain yang ditempuh adalah surveilans aktif, deteksi dini, serta edukasi kesehatan di masyarakat. Pemantauan kasus dilakukan berjenjang mulai dari puskesmas, rumah sakit, hingga laporan warga.</p>
        </div>
        <div class="news-item">
          <h3>Pemulihan Gedung Grahadi, Pemerintah Gelontorkan Rp9 Miliar</h3>
          <p>Rekonstruksi diperkirakan berlangsung sekitar tiga bulan dan melibatkan berbagai pihak terkait. Pemprov menargetkan pemulihan selesai sebelum pergantian tahun.</p>
        </div>
      </div>

      <div class="news-section">
        <h2 class="section-title">Economy</h2>
        <div class="news-item">
          <h3>Inovasi Dosen Surabaya Buat UMKM Probiotik Naik Kelas</h3>
          <p>Salah satu penerima manfaat program tersebut adalah Wijaya Bakery, UMKM roti asal Desa Bucor Kulon, Kecamatan Pakuniran, Probolinggo. Selama ini, usaha rumahan tersebut kerap kesulitan memenuhi pesanan besar karena keterbatasan peralatan produksi.</p>
        </div>
        <div class="news-item">
          <h3>Utang Bank Jatim Naik 30% di Triwulan II 2025</h3>
          <p>Kinerja keuangan Bank Jatim hingga akhir Juni 2025 terbilang impresif. Secara konsolidasi, aset tercatat Rp118,15 triliun, tumbuh 16,71% dibanding periode sama tahun lalu. Laba bersih mencapai Rp811 miliar, atau naik 30,64% secara tahunan (YoY).</p>
        </div>
        <div class="news-item">
          <h3>Wagub Emil Dardak: Tidak Ada PHK Massal di PT Gudang Garam</h3>
          <p>Pemerintah Provinsi Jatim bersama aparat penegak hukum, bupati, dan wali kota terus meningkatkan pengawasan untuk menekan peredaran rokok ilegal. Upaya ini dilakukan agar industri resmi tetap terlindungi dan mampu bertahan menghadapi kompetisi.</p>
        </div>
      </div>

      <div class="news-section">
        <h2 class="section-title">Lifestyle</h2>
        <div class="news-item">
          <h3>Tren Traveling Naik, Surabaya Jadi Tuan Rumah Pameran Wisata</h3>
          <p>Setelah mencatat kesuksesan besar tahun lalu, event ini kembali digelar guna menjawab tingginya minat masyarakat terhadap perjalanan wisata. Menurut Hendri Yapto, Chief Operations Officer Dwidayatour, antusiasme masyarakat Surabaya terhadap traveling semakin meningkat.</p>
        </div>
        <div class="news-item">
          <h3>Audisi Indonesian Idol XIV 2025 di Surabaya</h3>
          <p>Ajang pencarian bakat Indonesian Idol Season XIV 2025 kembali digelar dan langsung menyedot perhatian generasi muda. Pada hari pertama Big Audition yang berlangsung di Auditorium Benedictus Universitas Katolik Widya Mandala Surabaya (UKWMS) Kampus Dinoyo, Jumat (12/9/2025), ratusan peserta antusias hadir sejak pagi untuk menunjukkan kemampuan terbaik mereka di bidang tarik suara.</p>
        </div>
        <div class="news-item">
          <h3>Tren Desain Interior Minimalis Masih Digemari</h3>
          <p>Tren desain interior di tahun 2025 hingga 2026 diprediksi masih akan didominasi konsep minimalis. Pilihan ini semakin relevan seiring keterbatasan lahan dan meningkatnya kebutuhan hunian modern. </p>
        </div>
      </div>
    </aside>
  </div>

  <!-- FOOTER -->
  <footer class="footer">
    <div class="footer-container">
      <div class="footer-logo">
        <img src="Gambar/logo-berita.png" alt="logo-berita" class="footer-logo-img">
      </div>
      <div class="footer-links">
        <h4>QUICK LINKS</h4>
        <ul>
          <li><a href="#">News</a></li>
          <li><a href="#">Economy</a></li>
          <li><a href="#">Lifestyle</a></li>
          <li><a href="#">Culture</a></li>
          <li><a href="#">Sports</a></li>
          <li><a href="#">World</a></li>
          <li><a href="#">Fashion</a></li>
        </ul>
      </div>
      <div class="footer-social">
        <h4>FOLLOW US</h4>
        <div class="social-links">
          <a href="#"> <img src="Gambar/logo-ig.png" alt="logo-ig"></a>
          <a href="#"> <img src="Gambar/logo-x.webp" alt="logo-x"></a>
          <a href="#"> <img src="Gambar/logo-linkedin.png" alt="logo-linkedin"></a>
        </div>
      </div>
    </div>
    <div class="copyright">
      © 2025 The Surabaya iNews. All rights reserved.
    </div>
  </footer>

</body>
</html>
