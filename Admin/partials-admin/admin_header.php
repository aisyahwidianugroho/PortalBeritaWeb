<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$nama = $_SESSION['nama'] ?? 'Admin';
$initial = strtoupper(substr($nama, 0, 2));

$menu = $_GET['menu'] ?? 'dashboard';

$titles = [
    'dashboard'      => 'Dashboard Admin',
    'berita_masuk'   => 'Data Berita Masuk',
    'riwayat'        => 'Riwayat Publish',
    'kategori'       => 'Kelola Kategori',
    'user'           => 'Kelola Pengguna',
    'komentar'       => 'Kelola Komentar'
];
?>

<header class="header">
    <div class="header-left">
        <h1><?= $titles[$menu] ?? 'Admin Panel' ?></h1>
    </div>

    <div class="header-right">
        <div class="info">
            <div class="name"><?= htmlspecialchars($nama) ?></div>
            <div class="role">Admin</div>
        </div>

        <div class="avatar"><?= $initial ?></div>

        <a href="/PortalBeritaWeb/logoutadmin.php" class="logout-btn">
            Keluar
        </a>
    </div>
</header>
