<?php
include 'koneksi.php';

// -----------------------------------------
// PART 1 → KATEGORI DINAMIS
// -----------------------------------------
$id_kategori = isset($_GET['id']) ? (int)$_GET['id'] : 1;

// Ambil nama kategori
$getCat = mysqli_query($conn, "SELECT nama_kategori FROM categories WHERE id = $id_kategori");
$cat = mysqli_fetch_assoc($getCat);
$namaKategori = $cat ? $cat['nama_kategori'] : 'News';

// Ambil artikel sesuai kategori
$q = mysqli_query($conn, "
    SELECT * FROM articles 
    WHERE id_kategori = $id_kategori AND status = 'published'
    ORDER BY tanggal_publish DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $namaKategori ?> - theSurabaya iNews</title>
    <link rel="stylesheet" href="CSS/Kategori_news.css">
</head>
<body>

<!-- Header -->
<header class="header">
    <div class="header-top">SEPTEMBER 15, 2025</div>

    <div class="header-main">
        <div class="left">
            <button class="menu-btn">☰</button>
            <div class="weather">☀ 38° Surabaya</div>
        </div>

        <div class="center">
            <img src="Gambar/logo-berita.png" class="logo-img">
            <p class="tagline">PORTAL BERITA TERPERCAYA UNTUK SURABAYA</p>
        </div>

        <div class="right">
            <span>👤</span>
        </div>
    </div>
</header>

<!-- NAVIGATION -->
<nav class="nav">
    <ul class="nav-links">
        <li><a href="kategori.php?id=1">News</a></li>
        <li><a href="kategori.php?id=2">Economy</a></li>
        <li><a href="kategori.php?id=3">Lifestyle</a></li>
        <li><a href="kategori.php?id=4">Culture</a></li>
        <li><a href="kategori.php?id=5">Sports</a></li>
        <li><a href="kategori.php?id=6">World</a></li>
        <li><a href="kategori.php?id=7">Fashion</a></li>
    </ul>
</nav>

<main>

    <!-- OPTIONAL: FEATURED ARTICLE MASIH STATIS (NANTI BISA AKU BUATKAN DINAMIS JUGA) -->
    <section class="featured-article">
        <div class="featured-image">
            <img src="Gambar/guru-besar.jpg" alt="">
        </div>
        <h2>Resmi Jadi Guru Besar, Prof Hufron Singgung Pemakzulan...</h2>
        <p>Universitas 17 Agustus 1945 (Untag) Surabaya mencatat sejarah penting...</p>
    </section>

    <!-- LIST ARTIKEL DINAMIS -->
    <section class="latest-news">
        <h3>Latest <?= $namaKategori ?></h3>

        <?php if(mysqli_num_rows($q) === 0): ?>
            <p style="padding: 20px; font-size: 18px; color: #666;">
                Belum ada artikel untuk kategori <strong><?= $namaKategori ?></strong>.
            </p>
        <?php endif; ?>

        <?php while($row = mysqli_fetch_assoc($q)): ?>
        <article class="news-item">

            <div class="news-image">
                <img src="<?= $row['gambar_sampul']; ?>" alt="gambar artikel">
            </div>

            <div class="news-content">
                <h4>
                    <a href="detail-isi-berita.php?id=<?= $row['id']; ?>">
                        <?= $row['judul']; ?>
                    </a>
                </h4>

                <p><?= substr($row['konten'], 0, 160); ?>...</p>

                <span class="news-date">
                    <?= date("d F Y", strtotime($row['tanggal_publish'])); ?>
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
            <img src="Gambar/logo-berita.png" alt="" class="footer-logo-img">
        </div>

        <div class="footer-links">
            <h4>QUICK LINKS</h4>
            <ul>
                <li><a href="kategori.php?id=1">News</a></li>
                <li><a href="kategori.php?id=2">Economy</a></li>
                <li><a href="kategori.php?id=3">Lifestyle</a></li>
                <li><a href="kategori.php?id=4">Culture</a></li>
                <li><a href="kategori.php?id=5">Sports</a></li>
                <li><a href="kategori.php?id=6">World</a></li>
                <li><a href="kategori.php?id=7">Fashion</a></li>
            </ul>
        </div>

        <div class="footer-social">
            <h4>FOLLOW US</h4>
            <div class="social-links">
                <a href="#"><img src="Gambar/logo-ig.png"></a>
                <a href="#"><img src="Gambar/logo-x.webp"></a>
                <a href="#"><img src="Gambar/logo-linkedin.png"></a>
            </div>
        </div>
    </div>

    <div class="copyright">
        © 2025 The Surabaya iNews. All rights reserved.
    </div>
</footer>

</body>
</html>
