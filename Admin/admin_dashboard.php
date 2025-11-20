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
<<<<<<< HEAD

<!DOCTYPE html><html lang="id">
=======
<!DOCTYPE html>
<html lang="id">
>>>>>>> 33c3ba567ce62ec582fc27cae2365cbac703135f
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

    <!-- CSS -->
    <link rel="stylesheet" href="../CSS/dashboard.css">

    <!-- FONT AWESOME -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="role-admin">

<!-- SIDEBAR -->
<aside class="sidebar">
    <div class="brand">
        <h2 style="margin:0">Portal Berita</h2>
        <div class="badge">Admin</div>
    </div>

    <nav class="menu">

        <a href="admin_dashboard.php?menu=dashboard"
            class="<?= ($menu == 'dashboard') ? 'active' : '' ?>">
            <i class="fas fa-tachometer-alt"></i><span>Dashboard</span>
        </a>

        <a href="admin_dashboard.php?menu=berita_masuk"
            class="<?= ($menu == 'berita_masuk') ? 'active' : '' ?>">
            <i class="fas fa-inbox"></i><span>Data Berita Masuk</span>
        </a>

        <a href="admin_dashboard.php?menu=riwayat"
            class="<?= ($menu == 'riwayat') ? 'active' : '' ?>">
            <i class="fas fa-history"></i><span>Riwayat Publish</span>
        </a>

        <a href="admin_dashboard.php?menu=kategori"
            class="<?= ($menu == 'kategori') ? 'active' : '' ?>">
            <i class="fas fa-tags"></i><span>Kelola Kategori</span>
        </a>

        <a href="admin_dashboard.php?menu=user"
            class="<?= ($menu == 'user') ? 'active' : '' ?>">
            <i class="fas fa-users"></i><span>Kelola Pengguna</span>
        </a>

        <a href="admin_dashboard.php?menu=komentar"
            class="<?= ($menu == 'komentar') ? 'active' : '' ?>">
            <i class="fas fa-comments"></i><span>Kelola Komentar</span>
        </a>

        <a href="/PortalBeritaWeb/logoutadmin.php">
            <i class="fas fa-sign-out-alt"></i><span>Logout</span>
        </a>
    </nav>
</aside>


<!-- MAIN CONTENT -->
<main class="main">

    <!-- HEADER -->
    <header class="header">
        <h1>Halo, <?= htmlspecialchars($nama) ?></h1>
        <div>Anda login sebagai <b>Admin</b></div>
    </header>

    <!-- ===================== DASHBOARD ===================== -->
    <?php if ($menu == 'dashboard'): ?>
        <section class="card">
            <h3>Ringkasan</h3>
            <div class="body">
                <div class="stat">
                    <div class="item">
                        <div class="angka">128</div>
                        <div>Artikel Terbit</div>
                    </div>
                    <div class="item">
                        <div class="angka">5</div>
                        <div>Menunggu Terbit</div>
                    </div>
                    <div class="item">
                        <div class="angka">15</div>
                        <div>Jurnalis Aktif</div>
                    </div>
                    <div class="item">
                        <div class="angka">156K</div>
                        <div>Total Pembaca</div>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>


<!-- ===================== DATA BERITA MASUK ===================== -->
<?php if ($menu == 'berita_masuk'): ?>
<section class="card">
    <h3>Data Berita Masuk</h3>
    <p>Daftar berita dari jurnalis atau editor yang belum dipublish.</p>

    <?php
    include "../koneksi.php";

    $sql = "SELECT a.id, a.judul, a.tanggal_dibuat, 
                   u.nama_lengkap AS pengirim
            FROM articles a
            LEFT JOIN users u ON a.id_penulis = u.id
            WHERE a.status = 'pending'
            ORDER BY a.tanggal_dibuat DESC";

    $q = mysqli_query($conn, $sql);

    if (!$q) {
        echo "<div style='color:red;font-weight:bold'>SQL ERROR: " . mysqli_error($conn) . "</div>";
    }
    ?>

    <table class="table">
        <thead>
            <tr>
                <th>Judul</th>
                <th>Pengirim</th>
                <th>Tanggal Dibuat</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
        <?php if ($q && mysqli_num_rows($q) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($q)): ?>
            <tr>
                <td><?= htmlspecialchars($row['judul']) ?></td>
                <td><?= htmlspecialchars($row['pengirim']) ?></td>
                <td><?= htmlspecialchars($row['tanggal_dibuat']) ?></td>
                <td>
                    <a href="publish.php?id=<?= $row['id'] ?>" class="btn btn-publish">
                        <i class="fas fa-check"></i> Publish
                    </a>

                    <a href="hapus.php?id=<?= $row['id'] ?>" class="btn btn-delete"
                      onclick="return confirm('Yakin ingin menghapus berita ini?')">
                      <i class="fas fa-trash"></i> Hapus
                    </a>
                </td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="4">Tidak ada berita pending.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</section>
<?php endif; ?>


<!-- ===================== RIWAYAT ===================== -->
<?php if ($menu == 'riwayat'): ?>
<section class="card">
    <h3>Riwayat Berita Publish</h3>
    <p>Daftar berita yang sudah terbit.</p>

    <?php
    include "../koneksi.php";

    $sql = "SELECT a.id, a.judul, a.tanggal_publish,
                   u.nama_lengkap AS pengirim
            FROM articles a
            LEFT JOIN users u ON a.id_penulis = u.id
            WHERE a.status = 'dipublikasikan'
            ORDER BY a.tanggal_publish DESC";

    $q = mysqli_query($conn, $sql);

    if (!$q) {
        echo "<div style='color:red;font-weight:bold'>SQL ERROR: " . mysqli_error($conn) . "</div>";
    }
    ?>

    <table class="table">
        <thead>
            <tr>
                <th>Judul</th>
                <th>Pengirim</th>
                <th>Tanggal Publish</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
        <?php if ($q && mysqli_num_rows($q) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($q)): ?>
            <tr>
                <td><?= htmlspecialchars($row['judul']) ?></td>
                <td><?= htmlspecialchars($row['pengirim']) ?></td>
                <td><?= htmlspecialchars($row['tanggal_publish']) ?></td>
                <td>
                    <a href="unpublish.php?id=<?= $row['id'] ?>" 
                       class="btn btn-unpublish"
                       onclick="return confirm('Kembalikan ke pending?')">
                       <i class="fas fa-undo"></i> Unpublish
                    </a>

                    <a href="hapus.php?id=<?= $row['id'] ?>" 
                       class="btn btn-delete"
                       onclick="return confirm('Hapus berita ini secara permanen?')">
                       <i class="fas fa-trash"></i> Hapus
                    </a>
                </td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="4">Belum ada berita yang dipublish.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>

</section>
<?php endif; ?>

<!-- ===================== KATEGORI ===================== -->
<?php if ($menu == 'kategori'): ?>
<section class="card">
    <h3>Kelola Kategori</h3>
    <p>Tambah, edit, dan hapus kategori berita.</p>

    <a href="tambah_kategori.php" class="btn btn-publish" style="margin-bottom:15px;">
        <i class="fas fa-plus"></i> Tambah Kategori
    </a>

    <?php
    include "../koneksi.php";
    $q = mysqli_query($conn, "SELECT * FROM categories ORDER BY id DESC");
    ?>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Kategori</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
        <?php while ($row = mysqli_fetch_assoc($q)): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['nama_kategori']) ?></td>
                <td>
                    <a href="edit_kategori.php?id=<?= $row['id'] ?>" class="btn btn-publish">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="hapus_kategori.php?id=<?= $row['id'] ?>"
                       class="btn btn-delete"
                       onclick="return confirm('Hapus kategori ini?')">
                       <i class="fas fa-trash"></i> Hapus
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

</section>
<?php endif; ?>


<!-- ===================== USER ===================== -->
<?php if ($menu == 'user'): ?>
<section class="card">
    <h3>Kelola Pengguna</h3>
    <p>Atur akun jurnalis, editor, dan admin.</p>

    <a href="tambah_user.php" class="btn btn-publish">
        <i class="fas fa-plus"></i> Tambah Pengguna
    </a>

    <?php
    include "../koneksi.php";
    $q = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");

    if (!$q) {
        echo "<div style='color:red;font-weight:bold'>SQL ERROR: " . mysqli_error($conn) . "</div>";
    }
    ?>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Lengkap</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
        <?php if ($q && mysqli_num_rows($q) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($q)): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['nama_lengkap']) ?></td>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>

                <td>
                    <span class="badge-role <?= $row['role'] ?>">
                        <?= ucfirst($row['role']) ?>
                    </span>
                </td>

                <td>
                    <span class="badge-status <?= $row['status'] ?>">
                        <?= ucfirst($row['status']) ?>
                    </span>
                </td>

                <td>
                    <a href="edit_user.php?id=<?= $row['id'] ?>" class="btn btn-publish">
                        <i class="fas fa-edit"></i> Edit
                    </a>

                    <a href="hapus_user.php?id=<?= $row['id'] ?>"
                       class="btn btn-delete"
                       onclick="return confirm('Hapus pengguna ini?')">
                       <i class="fas fa-trash"></i> Hapus
                    </a>
                </td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="7">Tidak ada pengguna.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</section>
<?php endif; ?>


<!-- ===================== KOMENTAR ===================== -->
<?php if ($menu == 'komentar'): ?>
<section class="card">
    <h3>Kelola Komentar</h3>
    <p>Moderasi komentar pembaca.</p>
</section>
<?php endif; ?>

</main>

</body>
</html>
