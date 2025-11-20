<?php
include 'koneksi.php';

// Ambil kategori dari URL (opsional)
$id_kategori = 1; // 1 = News berdasarkan database

// Ambil semua artikel published dengan kategori News
$q = mysqli_query($conn, "
    SELECT * FROM articles 
    WHERE id_kategori = 1 AND status = 'published'
    ORDER BY tanggal_publish DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>theSurabaya iNews - Portal Berita Terpercaya untuk Surabaya</title>
    <link rel="stylesheet" href="CSS\Kategori_news.css">
</head>
<body>
 <!-- Header -->
  <header class="header">
    <div class="header-top">SEPTEMBER 15, 2025</div>

    <div class="header-main">
      <!-- Kiri -->
      <div class="left">
        <button class="menu-btn">☰</button>
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
      <li><a href="#">News</a></li>
      <li><a href="#">Economy</a></li>
      <li><a href="#">Lifestyle</a></li>
      <li><a href="#">Culture</a></li>
      <li><a href="#">Sports</a></li>
      <li><a href="#">World</a></li>
      <li><a href="#">Fashion</a></li>
    </ul>
  </nav>

        <main>
            <section class="featured-article">
                <div class="featured-image">
                    <img src="Gambar/guru-besar.jpg" alt="Prof Guru Besar Untag Surabaya">
                </div>
                <h2>Resmi Jadi Guru Besar, Prof Hufron Singgung Pemakzulan, Prof Fajar Bicara Kecanggihan Teknologi</h2>
                <p>Universitas 17 Agustus 1945 (Untag) Surabaya mencatat sejarah penting dengan mengukuhkan dua Guru Besar baru dalam rapat terbuka di Auditorium Suparman Hadipranoto, Ciha Wiyata, lantai 9, Selasa (16/9/2025). Momen ini menjadi tonggak strategis bagi "Kampus Merah Putih" untuk memperkuat kontribusi akademik di bidang hukum tata negara serta teknologi pengolahan citra digital.</p>
            </section>

<section class="latest-news">
    <h3>Latest News</h3>

    <?php while($row = mysqli_fetch_assoc($q)): ?>
    <article class="news-item">

        <div class="news-image">
            <img src="<?php echo $row['gambar_sampul']; ?>" alt="gambar">
        </div>

        <div class="news-content">
            <h4>
                <a href="detail-isi-berita.php?id=<?php echo $row['id']; ?>">
                    <?php echo $row['judul']; ?>
                </a>
            </h4>

            <p><?php echo substr($row['konten'], 0, 160); ?>...</p>

            <span class="news-date">
                <?php echo date("d F Y", strtotime($row['tanggal_publish'])); ?>
            </span>
        </div>

    </article>
    <?php endwhile; ?>
</section>

        </main>

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