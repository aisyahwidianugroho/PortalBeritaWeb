<?php
include "koneksi.php";

/* =========================
   Helper
========================= */
function esc($s) {
  return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8');
}
function excerpt($html, $len = 140) {
  $txt = trim(preg_replace('/\s+/', ' ', strip_tags($html ?? '')));
  if (mb_strlen($txt) <= $len) return $txt;
  return mb_substr($txt, 0, $len) . '...';
}

/* =========================
   Ambil keyword search (untuk isi value input)
========================= */
$q = trim($_GET['q'] ?? '');

/* =========================
   1) Featured terbaru
========================= */
$featured = null;
$featured_id = 0;

$featuredQ = mysqli_query($conn, "
  SELECT a.*, c.nama_kategori
  FROM articles a
  LEFT JOIN categories c ON a.id_kategori = c.id
  WHERE a.status='published'
  ORDER BY a.tanggal_publish DESC
  LIMIT 1
");
if ($featuredQ) {
  $featured = mysqli_fetch_assoc($featuredQ);
  $featured_id = $featured ? (int)$featured['id'] : 0;
}

/* =========================
   2) Top 3 setelah featured
========================= */
$top3 = mysqli_query($conn, "
  SELECT a.*, c.nama_kategori
  FROM articles a
  LEFT JOIN categories c ON a.id_kategori = c.id
  WHERE a.status='published'
    AND a.id != $featured_id
  ORDER BY a.tanggal_publish DESC
  LIMIT 3
");

/* =========================
   3) Grid setelah top3 (ambil 10 setelah offset 3)
========================= */
$grid = mysqli_query($conn, "
  SELECT a.*, c.nama_kategori
  FROM articles a
  LEFT JOIN categories c ON a.id_kategori = c.id
  WHERE a.status='published'
    AND a.id != $featured_id
  ORDER BY a.tanggal_publish DESC
  LIMIT 10 OFFSET 3
");

/* =========================
   4) Sidebar per kategori
========================= */
$sidebar_economy = mysqli_query($conn, "
  SELECT a.*, c.nama_kategori
  FROM articles a
  LEFT JOIN categories c ON a.id_kategori = c.id
  WHERE a.status='published' AND a.id_kategori=2
  ORDER BY a.tanggal_publish DESC
  LIMIT 2
");

$sidebar_life = mysqli_query($conn, "
  SELECT a.*, c.nama_kategori
  FROM articles a
  LEFT JOIN categories c ON a.id_kategori = c.id
  WHERE a.status='published' AND a.id_kategori=3
  ORDER BY a.tanggal_publish DESC
  LIMIT 2
");

$sidebar_culture = mysqli_query($conn, "
  SELECT a.*, c.nama_kategori
  FROM articles a
  LEFT JOIN categories c ON a.id_kategori = c.id
  WHERE a.status='published' AND a.id_kategori=4
  ORDER BY a.tanggal_publish DESC
  LIMIT 2
");
?>
<!DOCTYPE html>
<html lang="id">
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
    <!-- LEFT -->
    <div class="left">
      <button class="menu-btn" type="button" aria-label="Menu">☰</button>
      <div class="weather">☀ <?= (date("H") <= 17 ? "34°" : "28°") ?> Surabaya</div>
    </div>

    <!-- CENTER -->
    <div class="center">
      <img src="Gambar/logo-berita.png" class="logo-img" alt="the Surabaya iNews">
      <p class="tagline">PORTAL BERITA TERPERCAYA UNTUK SURABAYA</p>
    </div>

    <!-- RIGHT (penyeimbang biar center bener2 center) -->
    <div class="right">
      <span class="header-space"></span>
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
    <li><a href="kategori.php?id=7">Fashion</a></li>
  </ul>
</nav>

<!-- MAIN WRAPPER -->
<div class="main-container">
  <main class="main-content">

    <!-- FEATURED -->
    <?php if($featured): ?>
      <article class="featured-article">
        <img src="<?= esc($featured['gambar_sampul']) ?>" alt="<?= esc($featured['judul']) ?>">

        <div class="featured-content">
          <span class="category-tag"><?= esc(strtoupper($featured['nama_kategori'] ?? 'NEWS')) ?></span>

          <h1 class="featured-title">
            <a href="detail-isi-berita.php?id=<?= $featured_id ?>">
              <?= esc($featured['judul']) ?>
            </a>
          </h1>

          <p class="featured-excerpt"><?= esc(excerpt($featured['konten'], 160)) ?></p>
        </div>
      </article>
    <?php endif; ?>

    <!-- TOP 3 -->
    <div class="top3-container">
      <?php if($top3): while($t = mysqli_fetch_assoc($top3)): ?>
        <article class="top3-card">
          <img src="<?= esc($t['gambar_sampul']) ?>" class="top3-image" alt="<?= esc($t['judul']) ?>">

          <div class="top3-content">
            <span class="category-tag"><?= esc(strtoupper($t['nama_kategori'] ?? 'NEWS')) ?></span>

            <h2>
              <a href="detail-isi-berita.php?id=<?= (int)$t['id'] ?>">
                <?= esc($t['judul']) ?>
              </a>
            </h2>

            <p><?= esc(excerpt($t['konten'], 130)) ?></p>
          </div>
        </article>
      <?php endwhile; endif; ?>
    </div>

    <!-- GRID ARTICLES -->
    <div class="articles-grid">
      <?php if($grid): while($g = mysqli_fetch_assoc($grid)): ?>
        <article class="article-card">
          <img src="<?= esc($g['gambar_sampul']) ?>" class="article-image" alt="<?= esc($g['judul']) ?>">

          <div class="article-content">
            <span class="category-tag"><?= esc(strtoupper($g['nama_kategori'] ?? 'NEWS')) ?></span>

            <h2 class="article-title">
              <a href="detail-isi-berita.php?id=<?= (int)$g['id'] ?>">
                <?= esc($g['judul']) ?>
              </a>
            </h2>

            <p><?= esc(excerpt($g['konten'], 170)) ?></p>
          </div>
        </article>
      <?php endwhile; endif; ?>
    </div>

  </main>

  <!-- SIDEBAR -->
  <aside class="sidebar">

    <!-- SEARCH (jalan ke search.php) -->
    <form class="search-box" action="search.php" method="GET">
      <input
        type="text"
        class="search-input"
        name="q"
        placeholder="Cari berita..."
        autocomplete="off"
        value="<?= esc($q) ?>"
      >
      <button class="search-btn" type="submit" aria-label="Cari">
        <i class="fa fa-search"></i>
      </button>
    </form>

    <!-- ECONOMY -->
    <div class="news-section">
      <h2 class="section-title">Economy</h2>
      <?php if($sidebar_economy): while($s = mysqli_fetch_assoc($sidebar_economy)): ?>
        <div class="news-item">
          <h3>
            <a href="detail-isi-berita.php?id=<?= (int)$s['id'] ?>">
              <?= esc($s['judul']) ?>
            </a>
          </h3>
          <p><?= esc(excerpt($s['konten'], 120)) ?></p>
        </div>
      <?php endwhile; endif; ?>
    </div>

    <!-- LIFESTYLE -->
    <div class="news-section">
      <h2 class="section-title">Lifestyle</h2>
      <?php if($sidebar_life): while($s = mysqli_fetch_assoc($sidebar_life)): ?>
        <div class="news-item">
          <h3>
            <a href="detail-isi-berita.php?id=<?= (int)$s['id'] ?>">
              <?= esc($s['judul']) ?>
            </a>
          </h3>
          <p><?= esc(excerpt($s['konten'], 120)) ?></p>
        </div>
      <?php endwhile; endif; ?>
    </div>

    <!-- CULTURE -->
    <div class="news-section">
      <h2 class="section-title">Culture</h2>
      <?php if($sidebar_culture): while($s = mysqli_fetch_assoc($sidebar_culture)): ?>
        <div class="news-item">
          <h3>
            <a href="detail-isi-berita.php?id=<?= (int)$s['id'] ?>">
              <?= esc($s['judul']) ?>
            </a>
          </h3>
          <p><?= esc(excerpt($s['konten'], 120)) ?></p>
        </div>
      <?php endwhile; endif; ?>
    </div>

  </aside>
</div>

<!-- FOOTER -->
<footer class="footer">
  <div class="footer-container">
    <div class="footer-logo">
      <img src="Gambar/logo-berita.png" class="footer-logo-img" alt="the Surabaya iNews">
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
        <li><a href="kategori.php?id=7">Fashion</a></li>
      </ul>
    </div>

    <div class="footer-social">
      <h4>FOLLOW US</h4>
      <div class="social-links">
        <a href="#"><img src="Gambar/logo-ig.png" alt="Instagram"></a>
        <a href="#"><img src="Gambar/logo-x.webp" alt="X"></a>
        <a href="#"><img src="Gambar/logo-linkedin.png" alt="LinkedIn"></a>
      </div>
    </div>
  </div>

  <div class="copyright">
    © 2025 The Surabaya iNews. All rights reserved.
  </div>
</footer>

</body>
</html>
