<?php
include "koneksi.php";

// Ambil ID artikel dari URL
$id_artikel = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Query artikel
$sql = "SELECT a.*, c.nama_kategori 
        FROM articles a
        LEFT JOIN categories c ON a.id_kategori = c.id
        WHERE a.id = $id_artikel";

$artikel = mysqli_query($conn, $sql);
$data = mysqli_fetch_assoc($artikel);

if (!$data) {
    echo "Artikel tidak ditemukan.";
    exit;
}

// FIND MORE
$findMore = mysqli_query($conn, "
    SELECT * FROM articles
    WHERE status = 'published' AND id != $id_artikel
    ORDER BY tanggal_publish DESC
    LIMIT 4
");

// KOMENTAR
$komen = mysqli_query($conn, "
    SELECT * FROM comments 
    WHERE artikel_id = $id_artikel AND status='approved'
    ORDER BY tanggal DESC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($data['judul']) ?></title>
  <link rel="stylesheet" href="CSS/detail-isi-berita.css">
</head>
<body>

<header class="header">
    <div class="header-top"><?= strtoupper(date("F d, Y")); ?></div>

    <div class="header-main">
        <div class="left">
            <button class="menu-btn">☰</button>

            <div class="dropdown-menu">
                <a href="kategori.php?id=1">News</a>
                <a href="kategori.php?id=2">Economy</a>
                <a href="kategori.php?id=3">Lifestyle</a>
                <a href="kategori.php?id=4">Culture</a>
                <a href="kategori.php?id=5">Sports</a>
                <a href="kategori.php?id=6">World</a>
                <a href="kategori.php?id=7">Fashion</a>
            </div>

            <div class="weather">☀ 38° Surabaya</div>
        </div>

        <div class="center">
            <img src="Gambar/logo-berita.png" class="logo-img">
            <p class="tagline">PORTAL BERITA TERPERCAYA UNTUK SURABAYA</p>
        </div>

        <div class="right"><span>👤</span></div>
    </div>
</header>

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
    <article class="content">
        <p class="tag"><?= strtoupper($data['nama_kategori']) ?></p>
        <h2><?= htmlspecialchars($data['judul']) ?></h2>

        <p class="date"><?= date("d F Y H:i", strtotime($data['tanggal_dibuat'])) ?></p>

        <p><?= nl2br(htmlspecialchars($data['konten'])) ?></p>

        <img src="<?= $data['gambar_sampul'] ?>" alt="Gambar Artikel">

        <p><?= nl2br(htmlspecialchars($data['konten2'])) ?></p>
    </article>

    <aside class="sidebar">

        <!-- Form Komentar -->
        <div class="write-comment">
            <h3>Write Comment</h3>

            <form action="simpan_komentar.php" method="POST" class="comment-form">
                
                <div class="form-body">

                    <label>Nama Anda:</label>
                    <input type="text" name="nama" required placeholder="Masukkan nama Anda" class="input-name">

                    <input type="hidden" name="artikel_id" value="<?= $id_artikel ?>">

                    <textarea name="komentar" required placeholder="Ketik komentar..."></textarea>

                    <button type="submit">Post</button>
                </div>
            </form>
        </div>

        <!-- Daftar Komentar -->
        <section class="comments">
            <h3>Comments (<?= mysqli_num_rows($komen) ?>)</h3>

            <?php if (mysqli_num_rows($komen) == 0): ?>
                <p class="no-comment">Belum ada komentar.</p>
            <?php endif; ?>

            <?php while ($k = mysqli_fetch_assoc($komen)): ?>
            <div class="comment">
                <div class="avatar">👤</div>

                <div class="comment-body">
                    <span class="name"><?= htmlspecialchars($k['nama']) ?></span>
                    <p class="comment-text"><?= nl2br(htmlspecialchars($k['komentar'])) ?></p>
                    <small><?= $k['tanggal'] ?></small>
                </div>
            </div>
            <?php endwhile; ?>
        </section>

    </aside>
</main>

<!-- FIND MORE -->
<section class="find-more">
    <h2>FIND MORE</h2>
    <div class="find-grid">
        <?php while ($fm = mysqli_fetch_assoc($findMore)): ?>
            <div class="find-card">
                <img src="<?= $fm['gambar_sampul'] ?>" alt="">
                <div class="find-content">
                    <h3><a href="detail-isi-berita.php?id=<?= $fm['id'] ?>">
                        <?= htmlspecialchars($fm['judul']) ?>
                    </a></h3>
                    <p><?= substr($fm['konten'], 0, 150) ?>...</p>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</section>
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
