<?php
include "koneksi.php";

// ======================================================
// FEATURED ARTICLE (JOIN CATEGORY)
// ======================================================
$featured = mysqli_query($conn, "
    SELECT a.*, c.nama_kategori
    FROM articles a
    LEFT JOIN categories c ON a.id_kategori = c.id
    WHERE a.status='published'
    ORDER BY a.tanggal_publish DESC
    LIMIT 1
");

$feat = mysqli_fetch_assoc($featured);

// ======================================================
// GRID ARTICLES (JOIN CATEGORY)
// ======================================================
$grid = mysqli_query($conn, "
    SELECT a.*, c.nama_kategori
    FROM articles a
    LEFT JOIN categories c ON a.id_kategori = c.id
    WHERE a.status='published'
    AND a.id != {$feat['id']}
    ORDER BY a.tanggal_publish DESC
    LIMIT 6
");
// 3 berita terbaru setelah featured
$top3 = mysqli_query($conn, "
    SELECT a.*, c.nama_kategori
    FROM articles a
    LEFT JOIN categories c ON a.id_kategori = c.id
    WHERE a.status='published'
    AND a.id != {$feat['id']}
    ORDER BY a.tanggal_publish DESC
    LIMIT 3
");

// ======================================================
// SIDEBAR 1 → NEWS
// ======================================================
$sidebar1 = mysqli_query($conn, "
    SELECT a.*, c.nama_kategori
    FROM articles a
    LEFT JOIN categories c ON a.id_kategori = c.id
    WHERE a.status='published' AND a.id_kategori = 1
    ORDER BY a.tanggal_publish DESC
    LIMIT 2
");

// ======================================================
// SIDEBAR 2 → ECONOMY
// ======================================================
$sidebar2 = mysqli_query($conn, "
    SELECT a.*, c.nama_kategori
    FROM articles a
    LEFT JOIN categories c ON a.id_kategori = c.id
    WHERE a.status='published' AND a.id_kategori = 2
    ORDER BY a.tanggal_publish DESC
    LIMIT 2
");

// ======================================================
// SIDEBAR 3 → LIFESTYLE
// ======================================================
$sidebar3 = mysqli_query($conn, "
    SELECT a.*, c.nama_kategori
    FROM articles a
    LEFT JOIN categories c ON a.id_kategori = c.id
    WHERE a.status='published' AND a.id_kategori = 3
    ORDER BY a.tanggal_publish DESC
    LIMIT 2
");

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>The Surabaya iNews</title>
  <link rel="stylesheet" href="CSS/home.css">
</head>

<body>

<!-- HEADER -->
<header class="header">
    <div class="header-top"><?= strtoupper(date("F d, Y")); ?></div>

    <div class="header-main">
        <div class="left">
            <button class="menu-btn">☰</button>
            <div class="weather">
                ☀ <?= date("H") <= 17 ? "34°" : "28°"; ?> Surabaya
            </div>
        </div>

        <div class="center">
            <img src="Gambar/logo-berita.png" class="logo-img">
            <p class="tagline">PORTAL BERITA TERPERCAYA UNTUK SURABAYA</p>
        </div>
    </div>
</header>

<!-- NAVIGATION -->
<nav class="nav">
    <ul class="nav-links">
        <li><a href="home.php">News</a></li>
        <li><a href="kategori.php?id=2">Economy</a></li>
        <li><a href="kategori.php?id=3">Lifestyle</a></li>
        <li><a href="kategori.php?id=4">Culture</a></li>
        <li><a href="kategori.php?id=5">Sports</a></li>
        <li><a href="kategori.php?id=6">World</a></li>
        <li><a href="kategori.php?id=7">Fashion</a></li>
    </ul>
</nav>


<!-- MAIN -->
<div class="main-container">
    <main class="main-content">

        <!-- FEATURED ARTICLE -->
        <?php if($feat): ?>
        <article class="featured-article">

            <img src="<?= $feat['gambar_sampul']; ?>">

            <div class="featured-content">
                <span class="category-tag">
                    <?= strtoupper($feat['nama_kategori']); ?>
                </span>

                <h1>
                    <a href="detail-isi-berita.php?id=<?= $feat['id']; ?>" class="featured-title">
                        <?= htmlspecialchars($feat['judul']); ?>
                    </a>
                </h1>

                <p class="featured-excerpt">
                    <?= substr(strip_tags($feat['konten']), 0, 150); ?>...
                </p>
            </div>

        </article>
        <?php endif; ?>

<!-- 3 BERITA DI BAWAH FEATURED -->
<div class="top3-container">

    <?php while($t = mysqli_fetch_assoc($top3)): ?>
    <article class="top3-card">

        <img src="<?= $t['gambar_sampul']; ?>" class="top3-image">

        <div class="top3-content">
            
            <span class="category-tag"><?= strtoupper($t['nama_kategori']); ?></span>

            <h2>
                <a href="detail-isi-berita.php?id=<?= $t['id']; ?>">
                    <?= htmlspecialchars($t['judul']); ?>
                </a>
            </h2>

            <p><?= substr(strip_tags($t['konten']), 0, 120); ?>...</p>
        </div>

    </article>
    <?php endwhile; ?>

</div>

        <!-- GRID ARTICLES -->
        <div class="articles-grid">

            <?php while($row = mysqli_fetch_assoc($grid)): ?>
            <article class="article-card">

                <img src="<?= $row['gambar_sampul']; ?>" class="article-image">

                <div class="article-content">
                    <span class="category-tag"><?= strtoupper($row['nama_kategori']); ?></span>

                    <h2 class="article-title">
                        <a href="detail-isi-berita.php?id=<?= $row['id']; ?>">
                            <?= htmlspecialchars($row['judul']); ?>
                        </a>
                    </h2>

                    <p class="article-excerpt">
                        <?= substr(strip_tags($row['konten']), 0, 160); ?>...
                    </p>
                </div>

            </article>
            <?php endwhile; ?>

        </div>

    </main>


    <!-- SIDEBAR -->
    <aside class="sidebar">

        <!-- SEARCH -->
        <div class="search-box">
            <input type="text" class="search-input" placeholder="Search">
        </div>

        <!-- SIDEBAR NEWS -->
        <div class="news-section">
            <h2 class="section-title">News</h2>

            <?php while($s1 = mysqli_fetch_assoc($sidebar1)): ?>
            <div class="news-item">
                <h3>
                    <a href="detail-isi-berita.php?id=<?= $s1['id']; ?>">
                        <?= htmlspecialchars($s1['judul']); ?>
                    </a>
                </h3>
                <p><?= substr(strip_tags($s1['konten']), 0, 120); ?>...</p>
            </div>
            <?php endwhile; ?>
        </div>


        <!-- SIDEBAR ECONOMY -->
        <div class="news-section">
            <h2 class="section-title">Economy</h2>

            <?php while($s2 = mysqli_fetch_assoc($sidebar2)): ?>
            <div class="news-item">
                <h3>
                    <a href="detail-isi-berita.php?id=<?= $s2['id']; ?>">
                        <?= htmlspecialchars($s2['judul']); ?>
                    </a>
                </h3>
                <p><?= substr(strip_tags($s2['konten']), 0, 120); ?>...</p>
            </div>
            <?php endwhile; ?>
        </div>


        <!-- SIDEBAR LIFESTYLE -->
        <div class="news-section">
            <h2 class="section-title">Lifestyle</h2>

            <?php while($s3 = mysqli_fetch_assoc($sidebar3)): ?>
            <div class="news-item">
                <h3>
                    <a href="detail-isi-berita.php?id=<?= $s3['id']; ?>">
                        <?= htmlspecialchars($s3['judul']); ?>
                    </a>
                </h3>
                <p><?= substr(strip_tags($s3['konten']), 0, 120); ?>...</p>
            </div>
            <?php endwhile; ?>
        </div>

    </aside>

</div>

<!-- FOOTER -->
<footer class="footer">
    <div class="footer-container">
      <div class="footer-logo"><img src="Gambar/logo-berita.png" class="footer-logo-img"></div>
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
