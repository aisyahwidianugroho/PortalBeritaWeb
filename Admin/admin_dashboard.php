<?php
session_start();

// Validasi login admin
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: login.php?err=Silakan login sebagai Admin');
    exit;
}

$nama = $_SESSION['nama'] ?? 'Admin';

// Deteksi menu aktif
$menu = $_GET['menu'] ?? 'dashboard';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

    <!-- CSS -->
    <link rel="stylesheet" href="../CSS/admin_dashboard.css">
    <link rel="stylesheet" href="../CSS/dashboard.css">

    <!-- FONT AWESOME -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- CHART JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body class="role-admin">

<!-- SIDEBAR -->
<aside class="sidebar">
    <div class="brand">
        <h2 style="margin:0">Portal Berita</h2>
        <div class="badge">Admin</div>
    </div>

    <nav class="menu">
        <a href="admin_dashboard.php?menu=dashboard" class="<?= ($menu == 'dashboard') ? 'active' : '' ?>">
            <i class="fas fa-tachometer-alt"></i><span>Dashboard</span>
        </a>

        <a href="admin_dashboard.php?menu=berita_masuk" class="<?= ($menu == 'berita_masuk') ? 'active' : '' ?>">
            <i class="fas fa-inbox"></i><span>Data Berita Masuk</span>
        </a>

        <a href="admin_dashboard.php?menu=riwayat" class="<?= ($menu == 'riwayat') ? 'active' : '' ?>">
            <i class="fas fa-history"></i><span>Riwayat Publish</span>
        </a>

        <a href="admin_dashboard.php?menu=kategori" class="<?= ($menu == 'kategori') ? 'active' : '' ?>">
            <i class="fas fa-tags"></i><span>Kelola Kategori</span>
        </a>

        <a href="admin_dashboard.php?menu=user" class="<?= ($menu == 'user') ? 'active' : '' ?>">
            <i class="fas fa-users"></i><span>Kelola Pengguna</span>
        </a>

        <a href="admin_dashboard.php?menu=komentar" class="<?= ($menu == 'komentar') ? 'active' : '' ?>">
            <i class="fas fa-comments"></i><span>Kelola Komentar</span>
        </a>

        <a href="/PortalBeritaWeb/logoutadmin.php">
            <i class="fas fa-sign-out-alt"></i><span>Logout</span>
        </a>
    </nav>
</aside>

<!-- MAIN CONTENT -->
<main class="main">

<?php include __DIR__ . "/partials-admin/admin_header.php"; ?>
<?php include "../koneksi.php"; ?>


<!-- ========================== BERITA MASUK ========================== -->
<?php if ($menu == 'berita_masuk'): ?>

<section class="card dashboard-section">

    <h2 class="section-title">Data Berita Masuk</h2>

    <?php 
    $list = mysqli_query($conn, "
        SELECT a.*, u.nama_lengkap, c.nama_kategori
        FROM articles a
        LEFT JOIN users u ON a.id_penulis = u.id
        LEFT JOIN categories c ON a.id_kategori = c.id
        WHERE a.status = 'menunggu_admin'
        ORDER BY a.tanggal_dibuat DESC
    ");
    ?>

    <?php if (mysqli_num_rows($list)): ?>

    <table class="table-berita">
        <thead>
            <tr>
                <th>Judul</th>
                <th>Kategori</th>
                <th>Penulis</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
        <?php while($row = mysqli_fetch_assoc($list)): ?>
            <tr>
                <td><?= htmlspecialchars($row['judul']) ?></td>
                <td><?= htmlspecialchars($row['nama_kategori']) ?></td>
                <td><?= htmlspecialchars($row['nama_lengkap']) ?></td>
                <td><?= date('d M Y H:i', strtotime($row['tanggal_dibuat'])) ?></td>
                <td>
                    <a href="publish.php?id=<?= $row['id'] ?>" 
                       class="btn btn-sm btn-primary"
                       onclick="return confirm('Publish artikel ini?')">
                       Publish
                    </a>

                    <a href="hapus.php?id=<?= $row['id'] ?>" 
                       class="btn btn-sm btn-danger"
                       onclick="return confirm('Hapus artikel ini?')">
                       Hapus
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <?php else: ?>
        <p>Tidak ada berita menunggu admin.</p>
    <?php endif; ?>

</section>

<?php endif; ?>



<!-- ========================== DASHBOARD ADMIN ========================== -->
<?php
$publish  = mysqli_fetch_assoc(mysqli_query($conn,
            "SELECT COUNT(*) AS total FROM articles WHERE status='published'"))['total'] ?? 0;

$pending  = mysqli_fetch_assoc(mysqli_query($conn,
            "SELECT COUNT(*) AS total FROM articles WHERE status IN ('pending','menunggu_admin')"))['total'] ?? 0;

$jurnalis = mysqli_fetch_assoc(mysqli_query($conn,
            "SELECT COUNT(*) AS total FROM users WHERE role='jurnalis' AND status='active'"))['total'] ?? 0;

$pembacaRaw = mysqli_fetch_assoc(mysqli_query($conn,
            "SELECT SUM(views) AS total FROM articles"))['total'] ?? 0;

$pembaca = ($pembacaRaw >= 1000) ? round($pembacaRaw / 1000)."K" : $pembacaRaw;
?>


<?php if ($menu == 'dashboard'): ?>

<section class="card dashboard-section">

    <h2 class="section-title">Dashboard Admin</h2>

    <!-- STAT -->
    <div class="stat-box">
        <div class="stat-card">
            <div class="value" style="color:#2563eb;"><?= $publish ?></div>
            <div class="label">Artikel Terbit</div>
        </div>

        <div class="stat-card">
            <div class="value" style="color:#d97706;"><?= $pending ?></div>
            <div class="label">Menunggu Terbit</div>
        </div>

        <div class="stat-card">
            <div class="value" style="color:#047857;"><?= $jurnalis ?></div>
            <div class="label">Jurnalis Aktif</div>
        </div>

        <div class="stat-card">
            <div class="value" style="color:#7c3aed;"><?= $pembaca ?></div>
            <div class="label">Total Pembaca</div>
        </div>
    </div>


    <!-- ===================== STATISTIK ADMIN (2 GRAFIK) ===================== -->
    <div class="card" style="margin-top:20px;">
        <div class="card-header">
            <h3>Statistik Admin</h3>
        </div>

        <div class="card-body" style="display:grid; grid-template-columns: 1fr 1fr; gap:20px;">
            
            <!-- Bar Chart -->
            <div>
                <canvas id="adminBarChart" height="150"></canvas>
            </div>

            <!-- Line Chart -->
            <div>
                <canvas id="adminLineChart" height="150"></canvas>
            </div>

        </div>
    </div>

</section>

<?php endif; ?>

</main>


<!-- ===================== CHART SCRIPT ===================== -->
<script>
// BAR CHART
new Chart(document.getElementById('adminBarChart'), {
    type: 'bar',
    data: {
        labels: ['Terbit', 'Pending', 'Jurnalis', 'Pembaca'],
        datasets: [{
            label: 'Total',
            data: [
                <?= $publish ?>,
                <?= $pending ?>,
                <?= $jurnalis ?>,
                <?= is_numeric($pembacaRaw) ? $pembacaRaw : 0 ?>
            ],
            backgroundColor: ['#3b82f6','#f59e0b','#10b981','#9333ea'],
            borderRadius: 8
        }]
    },
    options: { scales: { y: { beginAtZero: true } } }
});


// LINE CHART (Total Pembaca Bulanan)
new Chart(document.getElementById('adminLineChart'), {
    type: 'line',
    data: {
        labels: ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'],
        datasets: [{
            label: 'Total Pembaca',
            data: [
                <?= is_numeric($pembacaRaw) ? $pembacaRaw : 0 ?>,0,0,0,0,0,0,0,0,0,0,0
            ],
            borderColor: '#9333ea',
            backgroundColor: 'rgba(147,51,234,0.2)',
            borderWidth: 2,
            tension: 0.3,
            pointRadius: 4
        }]
    },
    options: { scales: { y: { beginAtZero: true } } }
});
</script>

</body>
</html>
