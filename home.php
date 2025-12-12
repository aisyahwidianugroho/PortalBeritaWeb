<?php
include "koneksi.php";
//
// ======================================================
// 1. AMBIL FEATURED ARTIKEL (TERBARU)
// ======================================================
$featuredQ = mysqli_query($conn,"
    SELECT a.*, c.nama_kategori
    FROM articles a
    LEFT JOIN categories c ON a.id_kategori = c.id
    WHERE a.status='published'
    ORDER BY a.tanggal_publish DESC
    LIMIT 1
");
$featured = mysqli_fetch_assoc($featuredQ);
$featured_id = $featured ? $featured['id'] : 0;


//
// ======================================================
// 2. AMBIL 3 ARTIKEL SETELAH FEATURED (TOP 3)
// ======================================================
$top3 = mysqli_query($conn,"
    SELECT a.*, c.nama_kategori
    FROM articles a
    LEFT JOIN categories c ON a.id_kategori = c.id
    WHERE a.status='published'
    AND a.id != $featured_id
    ORDER BY a.tanggal_publish DESC
    LIMIT 3
");


//
// ======================================================
// 3. AMBIL 6 ARTIKEL UNTUK GRID SETELAH TOP 3
// ======================================================
$grid = mysqli_query($conn,"
    SELECT a.*, c.nama_kategori
    FROM articles a
    LEFT JOIN categories c ON a.id_kategori = c.id
    WHERE a.status='published'
    AND a.id != $featured_id
    ORDER BY a.tanggal_publish DESC
    LIMIT 10 OFFSET 3
");


//
// ======================================================
// 4. SIDEBAR CATEGORIES (Economy, Lifestyle, Culture)
// ======================================================
$sidebar_economy = mysqli_query($conn,"
    SELECT a.*, c.nama_kategori
    FROM articles a
    LEFT JOIN categories c ON a.id_kategori = c.id
    WHERE a.status='published' AND a.id_kategori=2
    ORDER BY a.tanggal_publish DESC
    LIMIT 2
");

$sidebar_life = mysqli_query($conn,"
    SELECT a.*, c.nama_kategori
    FROM articles a
    LEFT JOIN categories c ON a.id_kategori = c.id
    WHERE a.status='published' AND a.id_kategori=3
    ORDER BY a.tanggal_publish DESC
    LIMIT 2
");

$sidebar_culture = mysqli_query($conn,"
    SELECT a.*, c.nama_kategori
    FROM articles a
    LEFT JOIN categories c ON a.id_kategori = c.id
    WHERE a.status='published' AND a.id_kategori=4
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
<link rel="stylesheet" href="CSS/base.css">
<link rel="stylesheet" href="CSS/home.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>
<body>

<!-- HEADER -->
<header class="header">
    <div class="header-top"><?= strtoupper(date("F d, Y")) ?></div>

    <div class="header-main">
        <div class="left">
            <div class="weather">☀ <?= date("H") <= 17 ? "34°" : "28°" ?> Surabaya</div>
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
        <li><a href="home.php">Home</a></li>
        <li><a href="kategori.php?id=2">Economy</a></li>
        <li><a href="kategori.php?id=3">Lifestyle</a></li>
        <li><a href="kategori.php?id=4">Culture</a></li>
        <li><a href="kategori.php?id=5">Sports</a></li>
        <li><a href="kategori.php?id=6">World</a></li>
        <li><a href="kategori.php?id=9">Fashion</a></li>
    </ul>
</nav>

<!-- MAIN WRAPPER -->
<div class="main-container">
<main class="main-content">

    <!-- FEATURED -->
    <?php if($featured): ?>
    <article class="featured-article">
        <img src="<?= $featured['gambar_sampul'] ?>">

        <div class="featured-content">
            <span class="category-tag"><?= strtoupper($featured['nama_kategori']) ?></span>

            <h1>
                <a href="detail-isi-berita.php?id=<?= $featured_id ?>">
                    <?= htmlspecialchars($featured['judul']) ?>
                </a>
            </h1>

            <p><?= substr(strip_tags($featured['konten']), 0, 150) ?>...</p>
        </div>
    </article>
    <?php endif; ?>

    <!-- TOP 3 -->
    <div class="top3-container">
        <?php while($t = mysqli_fetch_assoc($top3)): ?>
        <article class="top3-card">
            <img src="<?= $t['gambar_sampul'] ?>" class="top3-image">

            <div class="top3-content">
                <span class="category-tag"><?= strtoupper($t['nama_kategori']) ?></span>

                <h2><a href="detail-isi-berita.php?id=<?= $t['id'] ?>"><?= htmlspecialchars($t['judul']) ?></a></h2>
                <p><?= substr(strip_tags($t['konten']), 0, 120) ?>...</p>
            </div>
        </article>
        <?php endwhile; ?>
    </div>

    <!-- GRID ARTICLES -->
    <div class="articles-grid">
    <?php while($g = mysqli_fetch_assoc($grid)): ?>
        <article class="article-card">
            <img src="<?= $g['gambar_sampul'] ?>" class="article-image">

            <div class="article-content">
                <span class="category-tag"><?= strtoupper($g['nama_kategori']) ?></span>

                <h2 class="article-title">
                    <a href="detail-isi-berita.php?id=<?= $g['id'] ?>">
                        <?= htmlspecialchars($g['judul']) ?>
                    </a>
                </h2>

                <p><?= substr(strip_tags($g['konten']), 0, 160) ?>...</p>
            </div>
        </article>
    <?php endwhile; ?>
    </div>

</main>

<!-- SIDEBAR -->
<aside class="sidebar">

<form class="search-box" action="search.php" method="GET">
    <input
      type="text"
      class="search-input"
      name="q"
      placeholder="Cari berita..."
      autocomplete="off"
      required
    >
    <button class="search-btn" type="submit" aria-label="Cari">
        <i class="fa fa-search"></i>
    </button>
</form>

    <!-- ECONOMY -->
    <div class="news-section">
        <h2 class="section-title">Economy</h2>
        <?php while($s = mysqli_fetch_assoc($sidebar_economy)): ?>
        <div class="news-item">
            <h3><a href="detail-isi-berita.php?id=<?= $s['id'] ?>"><?= htmlspecialchars($s['judul']) ?></a></h3>
            <p><?= substr(strip_tags($s['konten']), 0, 120) ?>...</p>
        </div>
        <?php endwhile; ?>
    </div>

    <!-- LIFESTYLE -->
    <div class="news-section">
        <h2 class="section-title">Lifestyle</h2>
        <?php while($s = mysqli_fetch_assoc($sidebar_life)): ?>
        <div class="news-item">
            <h3><a href="detail-isi-berita.php?id=<?= $s['id'] ?>"><?= htmlspecialchars($s['judul']) ?></a></h3>
            <p><?= substr(strip_tags($s['konten']), 0, 120) ?>...</p>
        </div>
        <?php endwhile; ?>
    </div>

    <!-- CULTURE -->
    <div class="news-section">
        <h2 class="section-title">Culture</h2>
        <?php while($s = mysqli_fetch_assoc($sidebar_culture)): ?>
        <div class="news-item">
            <h3><a href="detail-isi-berita.php?id=<?= $s['id'] ?>"><?= htmlspecialchars($s['judul']) ?></a></h3>
            <p><?= substr(strip_tags($s['konten']), 0, 120) ?>...</p>
        </div>
        <?php endwhile; ?>
    </div>

</aside>
</div>

<!-- FOOTER -->
<footer class="footer">
    <div class="footer-container">
        <div class="footer-logo">
            <img src="Gambar/logo-berita.png" class="footer-logo-img">
        </div>

        <div class="footer-links">
            <h4>QUICK LINKS</h4>
            <ul>
                <li><a href="home.php">Home</a></li>
                <li><a href="kategori.php?id=2">Economy</a></li>
                <li><a href="kategori.php?id=3">Lifestyle</a></li>
                <li><a href="kategori.php?id=4">Culture</a></li>
                <li><a href="kategori.php?id=5">Sports</a></li>
                <li><a href="kategori.php?id=6">World</a></li>
                <li><a href="kategori.php?id=9">Fashion</a></li>
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
