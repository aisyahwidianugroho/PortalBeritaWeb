<?php
include "koneksi.php";

function esc($s){
  return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8');
}
function excerpt($html, $len = 160){
  $txt = trim(preg_replace('/\s+/', ' ', strip_tags($html ?? '')));
  if (mb_strlen($txt) <= $len) return $txt;
  return mb_substr($txt, 0, $len) . '...';
}
function highlightHtml($safeText, $q){
  $q = trim($q);
  if ($q === '') return $safeText;
  $pattern = '/' . preg_quote($q, '/') . '/iu';
  return preg_replace($pattern, '<mark class="hl">$0</mark>', $safeText);
}

$q = trim($_GET['q'] ?? '');
$results = [];
$total = 0;

if ($q !== '') {
  $like = "%{$q}%";

  // COUNT
  $countSql = "
    SELECT COUNT(*) AS total
    FROM articles a
    WHERE a.status='published'
      AND (a.judul LIKE ? OR a.konten LIKE ? OR a.konten2 LIKE ?)
  ";
  $stmt = mysqli_prepare($conn, $countSql);
  mysqli_stmt_bind_param($stmt, "sss", $like, $like, $like);
  mysqli_stmt_execute($stmt);
  $res = mysqli_stmt_get_result($stmt);
  $row = mysqli_fetch_assoc($res);
  $total = (int)($row['total'] ?? 0);
  mysqli_stmt_close($stmt);

  // DATA
  $sql = "
    SELECT a.id, a.judul, a.konten, a.gambar_sampul, a.tanggal_publish,
           c.nama_kategori
    FROM articles a
    LEFT JOIN categories c ON a.id_kategori = c.id
    WHERE a.status='published'
      AND (a.judul LIKE ? OR a.konten LIKE ? OR a.konten2 LIKE ?)
    ORDER BY a.tanggal_publish DESC
    LIMIT 30
  ";
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt, "sss", $like, $like, $like);
  mysqli_stmt_execute($stmt);
  $res = mysqli_stmt_get_result($stmt);
  while ($r = mysqli_fetch_assoc($res)) $results[] = $r;
  mysqli_stmt_close($stmt);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Search Result</title>

  <link rel="stylesheet" href="CSS/base.css">
  <link rel="stylesheet" href="CSS/home.css">
  <link rel="stylesheet" href="CSS/search.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<!-- HEADER (samain kayak home) -->
<header class="header">
  <div class="header-top"><?= strtoupper(date("F d, Y")) ?></div>

  <div class="header-main">
    <div class="left">
      <button class="menu-btn" type="button">☰</button>
      <div class="weather">☀ <?= (date("H") <= 17 ? "34°" : "28°") ?> Surabaya</div>
    </div>

    <div class="center">
      <img src="Gambar/logo-berita.png" class="logo-img" alt="the Surabaya iNews">
      <p class="tagline">PORTAL BERITA TERPERCAYA UNTUK SURABAYA</p>
    </div>

    <div class="right">
      <span class="header-space"></span>
    </div>
  </div>
</header>

<!-- NAV -->
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

<div class="main-container">
  <main class="main-content">

    <div class="search-head">
      <h1 class="search-title">
        Search result
        <?php if ($q !== ''): ?>
          for: <span class="search-key">"<?= esc($q) ?>"</span>
        <?php endif; ?>
      </h1>

      <p class="search-sub">
        <?php if ($q === ''): ?>
          Ketik kata kunci untuk mencari berita.
        <?php else: ?>
          Ditemukan <strong><?= $total ?></strong> hasil (menampilkan max 30).
        <?php endif; ?>
      </p>

      <!-- Search bar (rapi + konsisten) -->
      <form class="search-box search-box--page" action="search.php" method="GET">
        <input class="search-input" type="text" name="q" placeholder="Cari berita..." value="<?= esc($q) ?>" autocomplete="off">
        <button class="search-btn" type="submit" aria-label="Cari">
          <i class="fa fa-search"></i>
        </button>
      </form>
    </div>

    <?php if ($q !== '' && $total === 0): ?>
      <div class="empty-state">
        <h3>Hasil tidak ditemukan 😭</h3>
        <p>Coba kata lain, atau pakai kata lebih umum (misal: “Surabaya”, “UNESA”, “Terminal”).</p>
      </div>
    <?php endif; ?>

    <?php if (!empty($results)): ?>
      <div class="articles-grid">
        <?php foreach ($results as $r): ?>
          <article class="article-card">
            <img src="<?= esc($r['gambar_sampul']) ?>" alt="<?= esc($r['judul']) ?>">

            <div class="article-content">
              <span class="category-tag"><?= esc(strtoupper($r['nama_kategori'] ?? 'NEWS')) ?></span>

              <?php
                $safeTitle = esc($r['judul']);
                $safeEx = esc(excerpt(($r['konten'] ?? '') . ' ' . ($r['konten2'] ?? ''), 170));
              ?>

              <h2 class="article-title">
                <a href="detail-isi-berita.php?id=<?= (int)$r['id'] ?>">
                  <?= highlightHtml($safeTitle, $q) ?>
                </a>
              </h2>

              <p class="article-excerpt"><?= highlightHtml($safeEx, $q) ?></p>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

  </main>

  <!-- Sidebar kecil biar gak kosong -->
  <aside class="sidebar">
    <div class="news-section">
      <h2 class="section-title">Tips Search</h2>
      <div class="news-item">
        <p>• Gunakan 1–2 kata kunci aja (contoh: <b>ratusan</b>, <b>terminal</b>, <b>UNESA</b>)</p>
        <p>• Coba variasi kata: “ekonomi”, “fashion”, “kegiatan”, dll.</p>
      </div>
    </div>
  </aside>
</div>

<!-- FOOTER (samain kayak home) -->
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
