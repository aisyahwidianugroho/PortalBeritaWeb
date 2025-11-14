<?php
include "koneksi.php";

// Ambil ID artikel dari URL
$id_artikel = $_GET['id'] ?? 0;

// Query artikel
$sql = "SELECT a.*, c.nama_kategori 
        FROM articles a
        LEFT JOIN categories c ON a.id_kategori = c.id
        WHERE a.id = $id_artikel";

$artikel = mysqli_query($conn, $sql);
$data = mysqli_fetch_assoc($artikel);

// Jika artikel tidak ditemukan
if (!$data) {
    echo "Artikel tidak ditemukan.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Budaya Indonesia Pukau Dunia</title>
  <link rel="stylesheet" href="CSS/detail-isi-berita.css">
  <script src="detail-isi-berita.js"></script>
</head>
<body>

  <!-- Header -->
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
        <span>👤</span>
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

  <!-- Content -->
  <main>
  <!-- Artikel utama -->
  <article class="content">
    <p class="tag"><?= strtoupper($data['nama_kategori']) ?></p>
    <h2><?= htmlspecialchars($data['judul']) ?></h2>
    <p class="date"><?= $data['tanggal_dibuat'] ?></p>

    <p><?= nl2br($data['konten']) ?></p>

    <img src="<?= $data['gambar_sampul'] ?>" alt="Gambar Artikel">

    <p><?= nl2br($data['konten2']) ?></p>
  </article>

  <!-- Sidebar -->
  <aside class="sidebar">
    <h3>POPULER NEWS</h3>
    <ul>
      <li><a href="#">Resmi Jadi Guru Besar, Prof Hufron Singgung Pemakzulan, Prof Fajar Bicara Kecanggihan Teknologi</a></li>
      <li><a href="#">Tren Traveling Naik, Surabaya Jadi Tuan Rumah Pameran Wisata Lokal Hingga Mancanegara</a></li>
      <li><a href="#">Tren Desain Interior, Minimalis Masih Digemari Warga Hingga Tahun Depan</a></li>
      <li><a href="#">Spillway Sungai Tanggul Dibangun, 1.046 Hektare Sawah di Jember Siap Terairi Lagi</a></li>
      <li><a href="#">Aneh, Bank-Bank Bingung Serap Dana Rp200 Triliun dari Pemerintah, Hanya Sanggup Rp7 Triliun?</a></li>
    </ul>

    <!-- Write Comment -->
    <div class="write-comment">
      <h3>Write Comment</h3>
      <div class="comment-form">
        <div class="avatar">👤</div>
        <div class="form-body">
          <div class="user-name">aik</div>
          <textarea placeholder="Ketik Komentar"></textarea>
          <button type="submit">Post</button>
        </div>
      </div>
    </div>

    <!-- Comments list -->
    <section class="comments">
      <h3>Comments (5)</h3>

      <div class="comment">
        <div class="avatar">👦</div>
        <div class="comment-body">
          <div class="comment-header">
            <span class="name">Faris Adhyaksa</span>
          </div>
          <p class="comment-text">❤️🔥👍👏</p>
        </div>
      </div>

      <div class="comment">
        <div class="avatar">👩</div>
        <div class="comment-body">
          <div class="comment-header">
            <span class="name">Oktania Aya</span>
          </div>
          <p class="comment-text">Wihhh keren banget! Budaya kita bisa go internasional. Salut buat tim yang ngebawa ke Jepang 👏</p>
        </div>
      </div>

      <div class="comment">
        <div class="avatar">👩</div>
        <div class="comment-body">
          <div class="comment-header">
            <span class="name">Amelia Clarke</span>
          </div>
          <p class="comment-text">😍😍😍</p>
        </div>
      </div>

      <a href="#" class="view-more">View More</a>
    </section>
  </aside>
</main>

  <!-- FIND MORE -->
  <section class="find-more">
    <h2>FIND MORE</h2>
    <div class="find-grid">
      <div class="find-card">
        <img src="Gambar/PBNU.webp" alt="Berita 1">
        <div class="find-content">
          <h3>PBNU Tegaskan Tak Terima Dana Kuota Haji, Desak KPK Ungkap Oknum yang Terlibat</h3>
          <p>Kalau misalnya ada bendahara dari Muhammadiyah terlibat, tidak pernah kemudian disebut ada aliran dana ke Muhammadiyah. Jadi jangan hanya karena ada individu dari NU lalu dikaitkan dengan PBNU</p>
        </div>
      </div>

      <div class="find-card">
        <img src="Gambar/Penemuan-Jenazah-di-Mojokerto.webp" alt="Berita 2">
        <div class="find-content">
          <h3>Misteri Mutilasi Mojokerto Terungkap, Korban Mahasiswi Universitas Trunojoyo Madura Dibunuh Pacarnya</h3>
          <p>Polisi bersama relawan kemudian menyisir lokasi dengan bantuan anjing pelacak. Hasilnya mencengangkan: ditemukan 65 potongan tubuh manusia termasuk pergelangan tangan, gumpalan daging, hingga potongan tulang.</p>
        </div>
      </div>

      <div class="find-card">
        <img src="Gambar/prabowo-reshuffle.jpeg" alt="Berita 3">
        <div class="find-content">
          <h3>Prabowo Reshuffle Kabinet, 5 Menteri Diganti Menkeu Legendaris Sri Mulyani Jadi Korban Pergantian</h3>
          <p>Dengan mempertimbangkan berbagai masukan dan evaluasi, Presiden memutuskan untuk melakukan perubahan pada sejumlah jabatan menteri Kabinet Merah Putih.</p>
        </div>
      </div>

      <div class="find-card">
        <img src="Gambar/kerusuhan-grahadi.jpeg" alt="Berita 4">
        <div class="find-content">
          <h3>Dua Provokator Kerusuhan Grahadi Ditangkap, Polda Jatim Bongkar Adanya Jaringan Anarkis</h3>
          <p>Kerusuhan akhir Agustus lalu tidak hanya membakar Gedung Negara Grahadi. Massa juga menyerang Mapolsek Tegalsari dan beberapa pos polisi di Surabaya. </p>
        </div>
      </div>
    </div>
  </section>

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
