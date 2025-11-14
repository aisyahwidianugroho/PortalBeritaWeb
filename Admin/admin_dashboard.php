<?php
session_start();
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
  header('Location: login.php?err=Silakan login sebagai Admin'); exit;
}
$nama = $_SESSION['nama'] ?? 'Admin';
?>

<!DOCTYPE html><html lang="id">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="../CSS/dashboard.css">
</head>

<body class="role-admin">
<aside class="sidebar">
  <div class="brand"><h2 style="margin:0">Portal Berita</h2><div class="badge">Admin</div></div>
  <nav class="menu">
    <a href="#" class="active"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a>
    <a href="#publikasi"><i class="fas fa-globe"></i><span>Publikasi Artikel</span></a>
    <a href="#users"><i class="fas fa-users"></i><span>Kelola Pengguna</span></a>
    <a href="#kategori"><i class="fas fa-tags"></i><span>Kelola Kategori</span></a>
    <a href="#stat"><i class="fas fa-chart-bar"></i><span>Statistik</span></a>
    <a href="/PortalBeritaWeb/logoutadmin.php"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a>
  </nav>
</aside>

<main class="main">
  <header class="header">
    <h1>Halo, <?= htmlspecialchars($nama) ?></h1>
    <div>Anda login sebagai <b>Admin</b></div>
  </header>

  <section class="card">
    <h3>Ringkasan</h3>
    <div class="body">
      <div class="stat">
        <div class="item"><div style="font-size:22px;font-weight:700">128</div><div>Artikel Terbit</div></div>
        <div class="item"><div style="font-size:22px;font-weight:700">5</div><div>Menunggu Terbit</div></div>
        <div class="item"><div style="font-size:22px;font-weight:700">15</div><div>Jurnalis Aktif</div></div>
        <div class="item"><div style="font-size:22px;font-weight:700">156K</div><div>Total Pembaca</div></div>
      </div>
    </div>
  </section>

  <!-- Bagian lain (Publikasi / Users / Kategori / Statistik) bisa ditempatkan di sini -->
</main>
</body></html>
