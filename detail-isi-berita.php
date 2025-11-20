<?php
include "koneksi.php";

// Ambil ID artikel dari URL
$id_artikel = $_GET['id'] ?? 0;

// Query artikel utama
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

// =============================================
// POIN 1 — QUERY FIND MORE (ambil 4 artikel terbaru selain artikel ini)
// =============================================
$id_aktif = $data['id'];

$findMore = mysqli_query($conn, "
    SELECT * FROM articles
    WHERE status = 'published' AND id != $id_aktif
    ORDER BY tanggal_publish DESC
    LIMIT 4
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

<!-- Header -->
<header class="header">
    <div class="header-top">SEPTEMBER 15, 2025</div>

    <div class="header-main">
        <!-- Kiri -->
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
        <li><a href="kategori.php?id=1">News</a></li>
        <li><a href="kategori.php?id=2">Economy</a></li>
        <li><a href="kategori.php?id=3">Lifestyle</a></li>
        <li><a href="kategori.php?id=4">Culture</a></li>
        <li><a href="kategori.php?id=5">Sports</a></li>
        <li><a href="kategori.php?id=6">World</a></li>
        <li><a href="kategori.php?id=7">Fashion</a></li>
    </ul>
</nav>

<!-- Content -->
<main>
    <!-- Artikel utama -->
    <article class="content">
        <p class="tag"><?= strtoupper($data['nama_kategori']) ?></p>

        <h2><?= htmlspecialchars($data['judul']) ?></h2>
        
        <p class="date">
            <?= date("d F Y H:i", strtotime($data['tanggal_dibuat'])) ?>
        </p>

        <p><?= nl2br($data['konten']) ?></p>

        <img src="<?= $data['gambar_sampul'] ?>" alt="Gambar Artikel">

        <p><?= nl2br($data['konten2']) ?></p>
    </article>

    <!-- Sidebar -->
    <aside class="sidebar">
        <h3>POPULER NEWS</h3>
        <ul>
            <li><a href="#">Resmi Jadi Guru Besar, Prof Hufron Singgung Pemakzulan...</a></li>
            <li><a href="#">Tren Traveling Naik, Surabaya Jadi Tuan Rumah...</a></li>
            <li><a href="#">Tren Desain Interior, Minimalis Masih Digemari...</a></li>
            <li><a href="#">Spillway Sungai Tanggul Dibangun...</a></li>
            <li><a href="#">Bank-Bank Bingung Serap Dana Rp200T...</a></li>
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
                    <span class="name">Faris Adhyaksa</span>
                    <p class="comment-text">❤️🔥👍👏</p>
                </div>
            </div>

            <div class="comment">
                <div class="avatar">👩</div>
                <div class="comment-body">
                    <span class="name">Oktania Aya</span>
                    <p class="comment-text">Wihhh keren banget! Budaya kita bisa go internasional 👏</p>
                </div>
            </div>

            <div class="comment">
                <div class="avatar">👩</div>
                <div class="comment-body">
                    <span class="name">Amelia Clarke</span>
                    <p class="comment-text">😍😍😍</p>
                </div>
            </div>

            <a href="#" class="view-more">View More</a>
        </section>
    </aside>
</main>

<!-- ============================================= -->
<!-- POIN 2 — FIND MORE DINAMIS -->
<!-- ============================================= -->
<section class="find-more">
    <h2>FIND MORE</h2>

    <div class="find-grid">

        <?php while ($fm = mysqli_fetch_assoc($findMore)): ?>
            <div class="find-card">

                <img src="<?= $fm['gambar_sampul']; ?>" alt="">

                <div class="find-content">
                    <h3>
                        <a href="detail-isi-berita.php?id=<?= $fm['id']; ?>">
                            <?= htmlspecialchars($fm['judul']); ?>
                        </a>
                    </h3>

                    <p><?= substr($fm['konten'], 0, 150); ?>...</p>
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
