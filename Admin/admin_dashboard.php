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
<!-- ========================== KELOLA KATEGORI ========================== -->
<?php if ($menu == 'kategori'): ?>

<section class="card dashboard-section">
    <h2 class="section-title">Kelola Kategori</h2>

    <!-- FORM TAMBAH KATEGORI -->
    <form action="kategori_tambah.php" method="POST" 
          style="margin-bottom:20px; display:flex; gap:10px;">
        <input type="text" name="nama_kategori" 
               placeholder="Nama kategori..." required
               style="padding:10px 14px; border-radius:8px; border:1px solid #cbd5e1; flex:1;">
        <button type="submit" class="btn-action btn-publish">Tambah</button>
    </form>
<?php if (isset($_GET['edit'])): 
    $editId = (int) $_GET['edit'];
    $edata = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM categories WHERE id=$editId"));
?>
    <div class="card" style="margin-bottom:20px;">
        <h3 class="section-title">Edit Kategori</h3>
        
        <form action="kategori_edit_proses.php" method="POST" 
              style="display:flex; flex-direction:column; gap:12px;">

            <input type="hidden" name="id" value="<?= $edata['id'] ?>">

            <input type="text" name="nama_kategori" 
                   value="<?= htmlspecialchars($edata['nama_kategori']) ?>"
                   placeholder="Nama kategori..." 
                   style="padding:10px; border-radius:8px; border:1px solid #cbd5e1;" required>

            <textarea name="deskripsi" 
                      style="padding:10px; border-radius:8px; border:1px solid #cbd5e1; height:80px;">
<?= htmlspecialchars($edata['deskripsi']) ?>
            </textarea>

            <select name="status" style="padding:10px; border-radius:8px; border:1px solid #cbd5e1;">
                <option value="active"   <?= ($edata['status']=='active')?'selected':'' ?>>Active</option>
                <option value="inactive" <?= ($edata['status']=='inactive')?'selected':'' ?>>Inactive</option>
            </select>

            <button class="btn-action btn-publish">Simpan Perubahan</button>
        </form>
    </div>
<?php endif; ?>

    <?php 
    $kat = mysqli_query($conn, "SELECT * FROM categories ORDER BY id DESC");
    ?>
<?php if (!isset($_GET['edit']) && mysqli_num_rows($kat)): ?>
    <table class="table-berita">

        <thead>
            <tr>
                <th>Nama Kategori</th>
                <th>Slug</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
        <?php while($row = mysqli_fetch_assoc($kat)): ?>
            <tr>
                <td><?= htmlspecialchars($row['nama_kategori']) ?></td>
                <td><?= htmlspecialchars($row['slug'] ?? '-') ?></td>
                <td>
                    <div class="action-buttons">
                        <a href="admin_dashboard.php?menu=kategori&edit=<?= $row['id'] ?>" 
                        class="btn-action btn-publish">Edit</a>
                        <a href="kategori_hapus.php?id=<?= $row['id'] ?>" 
                           class="btn-action btn-delete"
                           onclick="return confirm('Hapus kategori ini?')">
                           Hapus
                        </a>
                    </div>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

<?php endif; ?>
</section>
<?php endif; ?>
<?php if ($menu == 'user'): ?>
<?php if (isset($_GET['edit_user'])): 
    $uid = (int) $_GET['edit_user'];
    $u = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id=$uid"));
?>
    <div class="card" style="margin-bottom:20px;">
        <h3 class="section-title">Edit Pengguna</h3>

        <form action="user_edit_proses.php" method="POST" 
              style="display:flex; flex-direction:column; gap:12px;">

            <input type="hidden" name="id" value="<?= $u['id'] ?>">

            <input type="text" name="nama_lengkap" 
                   value="<?= $u['nama_lengkap'] ?>"
                   placeholder="Nama lengkap..."
                   style="padding:10px; border-radius:8px; border:1px solid #cbd5e1;">

            <input type="text" name="username" 
                   value="<?= $u['username'] ?>"
                   placeholder="Username..."
                   style="padding:10px; border-radius:8px; border:1px solid #cbd5e1;">

            <input type="email" name="email" 
                   value="<?= $u['email'] ?>"
                   placeholder="Email..."
                   style="padding:10px; border-radius:8px; border:1px solid #cbd5e1;">

            <select name="role" style="padding:10px; border-radius:8px; border:1px solid #cbd5e1;">
                <option value="admin"   <?= ($u['role']=='admin')?'selected':'' ?>>Admin</option>
                <option value="editor"  <?= ($u['role']=='editor')?'selected':'' ?>>Editor</option>
                <option value="jurnalis"<?= ($u['role']=='jurnalis')?'selected':'' ?>>Jurnalis</option>
            </select>

            <select name="status" style="padding:10px; border-radius:8px; border:1px solid #cbd5e1;">
                <option value="active"   <?= ($u['status']=='active')?'selected':'' ?>>Active</option>
                <option value="inactive" <?= ($u['status']=='inactive')?'selected':'' ?>>Inactive</option>
            </select>

            <button class="btn-action btn-publish">Simpan Perubahan</button>

        </form>
    </div>
<?php endif; ?>

<section class="card dashboard-section">
    <h2 class="section-title">Kelola Pengguna</h2>

    <?php 
    // Ambil daftar user yang ada
    $users = mysqli_query($conn, "
        SELECT id, username, nama_lengkap, email, role, status, tanggal_bergabung
        FROM users
        ORDER BY tanggal_bergabung DESC
    ");
    ?>

    <?php if ($users && mysqli_num_rows($users) > 0): ?>

    <table class="table-berita">
        <thead>
            <tr>
                <th>Nama Lengkap</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Tanggal Bergabung</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
        <?php while($u = mysqli_fetch_assoc($users)): ?>
            <tr>
                <td><?= htmlspecialchars($u['nama_lengkap']) ?></td>
                <td><?= htmlspecialchars($u['username']) ?></td>
                <td><?= htmlspecialchars($u['email']) ?></td>
                <td><?= ucfirst($u['role']) ?></td>

                <td>
                    <span class="badge-status 
                        <?= $u['status']=='active' ? 'badge-published' : 'badge-rejected' ?>">
                        <?= ucfirst($u['status']) ?>
                    </span>
                </td>

                <td><?= date('d M Y H:i', strtotime($u['tanggal_bergabung'])) ?></td>

                <td>
                    <a href="admin_dashboard.php?menu=user&edit_user=<?= $u['id'] ?>" 
                        class="btn-action btn-publish">Edit</a>
                    <a href="user_hapus.php?id=<?= $u['id'] ?>" 
                       class="btn-action btn-delete"
                       onclick="return confirm('Yakin ingin menghapus pengguna ini?')">
                       Hapus
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <?php else: ?>
        <p style="margin-top:10px;">Belum ada data pengguna.</p>
    <?php endif; ?>

</section>

<?php endif; ?>
<?php if ($menu == 'komentar'): ?>

<section class="card dashboard-section">
    <h2 class="section-title">Kelola Komentar</h2>

    <?php 
    // Ambil komentar beserta info artikel
    $komentar = mysqli_query($conn, "
        SELECT k.id, k.nama, k.email, k.isi, k.tanggal, a.judul 
        FROM comments k
        LEFT JOIN articles a ON k.id_artikel = a.id
        ORDER BY k.tanggal DESC
    ");
    ?>

    <?php if ($komentar && mysqli_num_rows($komentar) > 0): ?>
    <table class="table-berita">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Komentar</th>
                <th>Artikel</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
        <?php while($k = mysqli_fetch_assoc($komentar)): ?>
            <tr>
                <td><?= htmlspecialchars($k['nama']) ?></td>
                <td><?= htmlspecialchars($k['email']) ?></td>

                <td style="max-width:280px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                    <?= htmlspecialchars($k['isi']) ?>
                </td>

                <td><?= htmlspecialchars($k['judul']) ?></td>

                <td><?= date('d M Y H:i', strtotime($k['tanggal'])) ?></td>

                <td>
                    <a href="komentar_hapus.php?id=<?= $k['id'] ?>" 
                       class="btn-action btn-delete"
                       onclick="return confirm('Hapus komentar ini?')">
                       Hapus
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <?php else: ?>
        <p style="margin-top:10px;">Belum ada komentar.</p>
    <?php endif; ?>

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
