<?php
include "koneksi.php";

$q = mysqli_real_escape_string($conn, $_GET['q'] ?? '');

$result = mysqli_query($conn, "
    SELECT * FROM articles
    WHERE status='published'
    AND (judul LIKE '%$q%' OR konten LIKE '%$q%')
    ORDER BY tanggal_publish DESC
");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Search Result</title>
    <link rel="stylesheet" href="CSS/home.css">
</head>
<body>

<h2 style="margin: 20px 0;">Search result for: <b><?= htmlspecialchars($q) ?></b></h2>

<?php if (mysqli_num_rows($result) == 0): ?>
    <p>Tidak ada hasil ditemukan.</p>
<?php endif; ?>

<?php while($r = mysqli_fetch_assoc($result)): ?>
    <div class="news-item">
        <h3>
            <a href="detail-isi-berita.php?id=<?= $r['id'] ?>">
                <?= htmlspecialchars($r['judul']) ?>
            </a>
        </h3>
        <p><?= substr(strip_tags($r['konten']), 0, 150) ?>...</p>
    </div>
<?php endwhile; ?>

</body>
</html>
